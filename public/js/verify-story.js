(() => {
    "use strict";

    const dialog = document.getElementById("verify-story-dialog");

    if (!dialog) return;

    const $ = (name) => document.getElementById(`verify-story-${name}`);

    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

    const panels = [
        "decision",
        "payment",
        "consequence",
        "reflection",
        "save-confirm",
    ];

    let scenario = null;
    let currentNodeId = null;
    let history = [];
    let opener = null;
    let busy = false;
    let generation = 0;

    const pendingTimers = new Map();
    /*
     * Susun ulang panel hasil:
     * skor di atas, tanda mencurigakan dapat dilipat.
     */
    const reflectionPanel = $("reflection");
    const scorePanel = $("score");

    if (reflectionPanel && scorePanel) {
        reflectionPanel.prepend(scorePanel);
    }

    const signalsPanel = $("signals");

    if (signalsPanel && !signalsPanel.querySelector("details")) {
        const title = signalsPanel.querySelector("h4");
        const list = $("signals-list");

        const details = document.createElement("details");
        details.className = "verify-story-signals-details";

        const summary = document.createElement("summary");
        summary.textContent =
            title?.textContent || "Tanda yang perlu diperhatikan";

        title?.remove();

        details.append(summary, list);
        signalsPanel.append(details);
    }
    const PENDING_RESULT_KEY = "verify.pendingSimulationResult.v1";

    let attemptUuid = null;
    let startedAt = null;
    let completedAt = null;
    let pendingResult = null;

    function createAttemptUuid() {
        if (window.crypto?.randomUUID) {
            return window.crypto.randomUUID();
        }

        if (!window.crypto?.getRandomValues) {
            return null;
        }

        const bytes = new Uint8Array(16);
        window.crypto.getRandomValues(bytes);

        bytes[6] = (bytes[6] & 0x0f) | 0x40;
        bytes[8] = (bytes[8] & 0x3f) | 0x80;

        const hex = Array.from(bytes, (byte) =>
            byte.toString(16).padStart(2, "0"),
        ).join("");

        return [
            hex.slice(0, 8),
            hex.slice(8, 12),
            hex.slice(12, 16),
            hex.slice(16, 20),
            hex.slice(20),
        ].join("-");
    }

    function resetSavePanel() {
        pendingResult = null;

        hidden("score");
        hidden("save-confirm");

        $("save-status").textContent = "";

        $("save-button").disabled = false;
        $("save-button").textContent =
            dialog.dataset.authenticated === "true"
                ? "Simpan & selesai"
                : "Login & simpan";

        $("save-later").disabled = false;
        $("score-breakdown").replaceChildren();
    }

    function renderScore(node) {
        // Mendukung format node.scoring maupun field langsung pada node.
        const scoring = node.scoring ?? node;

        const valid =
            Number.isInteger(scoring.score) &&
            Number.isInteger(scoring.max_score) &&
            scoring.max_score > 0 &&
            scoring.score >= 0 &&
            scoring.score <= scoring.max_score;

        const breakdownList = $("score-breakdown");
        breakdownList.replaceChildren();

        hidden("score", !valid);

        if (!valid) {
            console.warn("Data skor outcome tidak valid:", scoring);
            return;
        }

        $("score-value").textContent = String(scoring.score);
        $("score-max").textContent = String(scoring.max_score);

        $("score-progress").max = scoring.max_score;
        $("score-progress").value = scoring.score;

        const breakdown = scoring.breakdown ?? [];

        // Format lama: array berisi criterion dan description.
        // Format seeder: object berisi pasangan judul dan penjelasan.
        const entries = Array.isArray(breakdown)
            ? breakdown
            : typeof breakdown === "object" && breakdown !== null
              ? Object.entries(breakdown).map(([criterion, description]) => ({
                    criterion,
                    description,
                }))
              : [];

        entries.forEach((entry) => {
            if (!entry || typeof entry !== "object") return;

            const item = document.createElement("li");

            const title = document.createElement("strong");
            title.textContent = String(entry.criterion ?? "");

            const description = document.createElement("span");
            description.textContent = String(entry.description ?? "");

            item.append(title, document.createTextNode(" "), description);

            breakdownList.append(item);
        });
    }

    function prepareResult(node) {
        completedAt = new Date().toISOString();

        /*
         * Ini hanya data permintaan penyimpanan.
         * Skor dan hasil resmi nanti ditentukan backend.
         */
        pendingResult = {
            attempt_uuid: attemptUuid,
            scenario_id: scenario.id,
            scenario_version: scenario.version,
            outcome_node_id: currentNodeId,
            decision_path: history.map((entry) => ({
                node_id: entry.node_id,
                choice_id: entry.choice_id,
                action: entry.action,
                next: entry.next,
            })),
            started_at: startedAt,
            completed_at: completedAt,
        };

        renderScore(node);
    }

    function showSaveConfirmation() {
        if (!pendingResult) return;

        panels.forEach((name) => hidden(name));

        hidden("context");
        hidden("conversation");

        hidden("save-confirm", false);

        $("save-description").textContent =
            dialog.dataset.authenticated === "true"
                ? "Simpan skor dan perjalanan keputusanmu ke riwayat belajar akun VeriFy."
                : "Login untuk menyimpan skor dan perjalanan keputusanmu ke akun VeriFy.";

        dialog.scrollTop = 0;

        $("save-title").focus({
            preventScroll: true,
        });
    }

    function backToResult() {
        hidden("save-confirm");
        hidden("reflection", false);

        dialog.scrollTop = 0;

        $("reflection-title").focus({
            preventScroll: true,
        });
    }

    async function saveProgress() {
        if (!pendingResult || !pendingResult.attempt_uuid) {
            $("save-status").textContent =
                "Hasil latihan belum siap. Silakan kembali ke hasil.";
            return;
        }

        const authenticated = dialog.dataset.authenticated === "true";
        const loginUrl = dialog.dataset.loginUrl;

       // 1. JIKA BELUM LOGIN: Titipkan format JSON API ke Session Storage dan arahkan ke Login
        if (!authenticated) {
            try {
                const pathData = history.map(entry => entry.choice_id || entry.action);
                const outcomeNode = scenario.story.nodes[currentNodeId];
                const scoring = outcomeNode.scoring ?? outcomeNode;

                // Kita simpan format payload API-nya langsung agar gampang dieksekusi setelah login
                sessionStorage.setItem(
                    PENDING_RESULT_KEY,
                    JSON.stringify({
                        api_payload: {
                            scenario_id: pendingResult.scenario_id,
                            decision_path: pathData,
                            score: scoring.score,
                            max_score: scoring.max_score,
                            scenario_version: pendingResult.scenario_version
                        }
                    }),
                );
            } catch (error) {
                console.error(error);
                $("save-status").textContent = "Hasil belum dapat dititipkan. Silakan coba lagi.";
                return;
            }

            $("save-button").disabled = true;
            $("save-button").textContent = "Menuju login…";
            $("save-status").textContent = "Mengarahkan ke halaman login...";
            
            if (loginUrl) {
                window.location.assign(loginUrl);
            }
            return;
        }

        // 2. JIKA SUDAH LOGIN: Tembak ke API Backend (Tugas Kamu Beraksi Di Sini)
        $("save-button").disabled = true;
        $("save-button").textContent = "Menyimpan...";
        $("save-status").textContent = "Sedang menyimpan hasil simulasi...";

        try {
            // Kita bungkus data history decision path hanya dengan array ID
            const pathData = history.map(entry => entry.choice_id || entry.action);
            
            // Ambil informasi skor
            const outcomeNode = scenario.story.nodes[currentNodeId];
            const scoring = outcomeNode.scoring ?? outcomeNode;

            const response = await fetch('/api/simulator/attempts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    scenario_id: pendingResult.scenario_id,
                    decision_path: pathData,
                    score: scoring.score,
                    max_score: scoring.max_score,
                    scenario_version: pendingResult.scenario_version
                })
            });

            const result = await response.json();

            if (response.ok) {
                $("save-button").textContent = "Tersimpan ✔";
                $("save-status").textContent = "Mantap! Hasil latihanmu berhasil disimpan ke riwayat akun.";
                
                // Tambahan: Otomatis tutup dialog setelah 2 detik
                setTimeout(() => {
                    dialog.close();
                }, 2000);
            } else {
                $("save-button").disabled = false;
                $("save-button").textContent = "Simpan & selesai";
                $("save-status").textContent = result.message || "Gagal menyimpan, silakan coba lagi.";
            }
        } catch (error) {
            console.error("Error API:", error);
            $("save-button").disabled = false;
            $("save-button").textContent = "Simpan & selesai";
            $("save-status").textContent = "Terjadi kesalahan saat menghubungi server.";
        }
    }

    function hidden(name, value = true) {
        $(name).hidden = value;
    }

    function cancelTasks() {
        generation += 1;

        pendingTimers.forEach((resolve, timer) => {
            clearTimeout(timer);
            resolve(false);
        });

        pendingTimers.clear();
        busy = false;
    }

    function pause(ms, token) {
        if (token !== generation || !dialog.open) {
            return Promise.resolve(false);
        }

        return new Promise((resolve) => {
            const timer = setTimeout(
                () => {
                    pendingTimers.delete(timer);

                    resolve(token === generation && dialog.open);
                },
                reducedMotion.matches ? 0 : ms,
            );

            pendingTimers.set(timer, resolve);
        });
    }

    function setStage(name) {
        dialog.querySelectorAll("[data-story-stage]").forEach((element) => {
            const active = element.dataset.storyStage === name;

            element.classList.toggle("is-active", active);

            if (active) {
                element.setAttribute("aria-current", "step");
            } else {
                element.removeAttribute("aria-current");
            }
        });
    }

    function focusHeading(name) {
        const element = $(name);

        element.focus({ preventScroll: true });
        element.scrollIntoView({
            block: "nearest",
            behavior: "auto",
        });
    }

    function makeButton(label, callback) {
        const button = document.createElement("button");

        button.type = "button";
        button.textContent = label;
        button.addEventListener("click", callback);

        return button;
    }

    function appendMessage(message) {
        const sender = message.sender;

        const bubble = document.createElement("div");

        const variant =
            {
                contact: "incoming",
                user: "outgoing",
                system: "system",
            }[sender] || "system";

        bubble.className = `verify-story-message is-${variant}`;

        const author = document.createElement("span");

        author.className = "verify-story-message-author";

        author.textContent =
            {
                contact: scenario.story.contact.name,
                user: "Kamu",
                system: "Simulasi",
            }[sender] || "Simulasi";

        const text = document.createElement("p");
        text.textContent = message.text;

        bubble.append(author, text);
        $("messages").append(bubble);

        bubble.scrollIntoView({
            block: "nearest",
            behavior: "auto",
        });
    }

    async function playMessages(messages, token) {
        for (const message of messages) {
            if (token !== generation || !dialog.open) {
                return false;
            }

            const isContact = message.sender === "contact";

            hidden("typing", !isContact);

            const active = await pause(isContact ? 850 : 300, token);

            if (!active) return false;

            hidden("typing");
            appendMessage(message);
        }

        return true;
    }

    function recordChoice(choice, action = "choice") {
        history.push({
            node_id: currentNodeId,
            choice_id: choice.id,
            action,
            label: choice.label,
            next: choice.next,
        });
    }

    function showError(message) {
        panels.forEach((name) => hidden(name));

        hidden("typing");
        hidden("decision", false);

        $("decision-title").textContent = "Cerita belum dapat dilanjutkan";

        $("decision-description").textContent = message;
        $("choices").replaceChildren();

        $("choices").append(makeButton("Tutup simulasi", () => dialog.close()));

        busy = false;
        focusHeading("decision-title");
    }

    async function selectChoice(choice) {
        if (busy) return;

        busy = true;
        hidden("decision");

        recordChoice(choice);

        if (choice.reply !== null && choice.reply !== undefined) {
            appendMessage({
                sender: "user",
                text: choice.reply,
            });
        }

        await enterNode(choice.next);
    }

    function showChoices(node) {
        $("decision-title").textContent =
            node.prompt || "Apa yang ingin kamu lakukan?";

        $("decision-description").textContent =
            "Pilih tindakan untuk melanjutkan cerita.";

        $("choices").replaceChildren();

        node.choices.forEach((choice) => {
            $("choices").append(
                makeButton(choice.label, () => selectChoice(choice)),
            );
        });

        busy = false;
        hidden("decision", false);

        focusHeading("decision-title");
    }

    function showPayment(node) {
        $("payment-title").textContent = node.title;
        $("payment-purpose").textContent = node.purpose;
        $("payment-recipient").textContent = node.recipient;

        $("payment-amount").textContent = new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: node.currency,
            maximumFractionDigits: 0,
        }).format(node.amount);

        dialog.querySelector('[data-story-payment="confirm"]').textContent =
            node.confirm.label;

        dialog.querySelector('[data-story-payment="cancel"]').textContent =
            node.cancel.label;

        hidden("payment-processing");
        hidden("payment-actions", false);
        hidden("payment", false);

        busy = false;
        focusHeading("payment-title");
    }

    async function selectPayment(action) {
        if (busy) return;

        const node = scenario.story.nodes[currentNodeId];

        if (!node || node.type !== "payment") return;

        busy = true;

        const selected = node[action];
        const token = generation;

        recordChoice(selected, action);

        if (action === "confirm") {
            hidden("payment-actions");
            hidden("payment-processing", false);

            $("announcement").textContent = "Memproses pembayaran fiktif.";

            if (!(await pause(1100, token))) return;

            $("announcement").textContent = "Proses pembayaran fiktif selesai.";
        }

        await enterNode(selected.next);
    }

    function renderReflection(node) {
        const reflection = node.reflection;

        $("reflection-summary").textContent = reflection.summary;

        $("learning-what").textContent = reflection.what_happened;

        $("learning-why").textContent = reflection.why_it_matters;

        $("learning-alternative").textContent = reflection.safer_alternative;

        $("signals-list").replaceChildren();

        reflection.signals.forEach((signal) => {
            const item = document.createElement("li");
            item.textContent = signal;
            $("signals-list").append(item);
        });

        hidden("signals", reflection.signals.length === 0);

        $("history-list").replaceChildren();

        history.forEach((entry) => {
            const item = document.createElement("li");
            item.textContent = entry.label;
            $("history-list").append(item);
        });
    }

    function showOutcome(node) {
        dialog.dataset.outcome =
            {
                positive: "safe",
                warning: "risky",
                neutral: "neutral",
            }[node.tone] || "neutral";

        $("consequence-title").textContent = node.title;
        $("consequence-description").textContent = node.description;

        renderReflection(node);
        prepareResult(node);

        /*
         * Layar hasil tidak lagi menumpuk di bawah chat.
         */
        panels.forEach((name) => hidden(name));

        hidden("context");
        hidden("conversation");
        hidden("typing");

        hidden("reflection", false);

        $("reflection-title").textContent = node.title;

        $("reflection-summary").textContent = [
            node.description,
            node.impact?.text,
        ]
            .filter(Boolean)
            .join(" ");

        dialog
            .querySelectorAll(
                ".verify-story-history, .verify-story-signals-details",
            )
            .forEach((details) => {
                details.open = false;
            });

        const footer = dialog.querySelector(".verify-story-footer");
        if (footer) footer.hidden = true;

        setStage("reflection");
        busy = false;

        /*
         * Tampilkan bagian paling atas agar skor langsung terlihat.
         */
        dialog.scrollTop = 0;
        $("reflection-title").focus({ preventScroll: true });
    }

    async function enterNode(nodeId) {
        const node = scenario.story.nodes[nodeId];

        if (!node) {
            showError("Adegan tujuan tidak ditemukan.");
            return;
        }

        currentNodeId = nodeId;
        busy = true;

        const token = generation;

        panels.forEach((name) => hidden(name));
        hidden("typing");

        setStage(node.stage);

        if (node.type === "conversation") {
            const completed = await playMessages(node.messages, token);

            if (completed) showChoices(node);
            return;
        }

        if (node.type === "payment") {
            showPayment(node);
            return;
        }

        if (node.type === "outcome") {
            showOutcome(node);
            return;
        }

        showError("Jenis adegan ini belum didukung.");
    }

    async function loadScenario(topicId) {
        const headers = {
            Accept: "application/json",
        };

        async function getJson(url) {
            const response = await fetch(url, {
                method: "GET",
                headers,
                credentials: "same-origin",
            });

            const contentType = response.headers.get("content-type") || "";

            if (!contentType.includes("application/json")) {
                throw new Error(
                    `Server tidak mengirim JSON. Periksa alamat endpoint: ${url}`,
                );
            }

            const body = await response.json();

            if (!response.ok) {
                throw new Error(
                    body.message ||
                        `Gagal mengambil data (${response.status}).`,
                );
            }

            return body;
        }

        const listUrl = new URL(
            "/api/simulator/scenarios",
            window.location.origin,
        );

        listUrl.searchParams.set("topic_id", String(topicId));

        const listResponse = await getJson(listUrl);

        if (!Array.isArray(listResponse.data)) {
            throw new Error("Format daftar skenario tidak sesuai.");
        }

        const scenarios = listResponse.data;

        if (scenarios.length === 0) {
            return null;
        }

        /*
         * Sementara pilih satu cerita secara acak dalam topik.
         * Nanti bisa diganti menjadi daftar pilihan skenario.
         */
        const selected =
            scenarios[Math.floor(Math.random() * scenarios.length)];

        if (!selected.slug) {
            throw new Error("Slug skenario tidak tersedia.");
        }

        const detailUrl = new URL(
            `/api/simulator/scenarios/${encodeURIComponent(selected.slug)}`,
            window.location.origin,
        );

        const detailResponse = await getJson(detailUrl);
        const scenario = detailResponse.data;

        if (
            !scenario ||
            !scenario.story ||
            typeof scenario.story !== "object" ||
            Array.isArray(scenario.story)
        ) {
            throw new Error(
                "Format detail cerita tidak sesuai. Periksa cast story pada model.",
            );
        }

        if (Number(scenario.topic_id) !== Number(topicId)) {
            throw new Error("Topik cerita tidak sesuai.");
        }

        return scenario;
    }

    function resetView() {
        const footer = dialog.querySelector(".verify-story-footer");

        if (footer) footer.hidden = false;

        dialog.scrollTop = 0;

        panels.forEach((name) => hidden(name));

        hidden("typing");
        hidden("context", false);
        hidden("conversation", false);

        $("messages").replaceChildren();
        $("choices").replaceChildren();
        $("signals-list").replaceChildren();
        $("history-list").replaceChildren();

        $("announcement").textContent = "";

        dialog.querySelector(".verify-story-history").open = false;

        delete dialog.dataset.outcome;

        $("title").textContent = "Menyiapkan cerita…";
        $("context-title").textContent = "Memuat skenario";
        $("context-description").textContent =
            "Sebentar, cerita sedang disiapkan.";

        $("contact-name").textContent = "Pengirim fiktif";
        $("contact-avatar").textContent = "S";
        $("contact-status").textContent = "Simulasi";

        setStage("situation");
    }

    function startScenario(data) {
        scenario = data;

        attemptUuid = createAttemptUuid();
        startedAt = new Date().toISOString();
        completedAt = null;

        resetSavePanel();

        if (
            scenario.story?.schema_version !== 1 ||
            !scenario.story.nodes ||
            !scenario.story.nodes[scenario.story.start]
        ) {
            showError("Struktur cerita tidak didukung.");
            return;
        }

        $("title").textContent = scenario.title;

        $("context-title").textContent = scenario.story.context.title;

        $("context-description").textContent =
            scenario.story.context.description;

        $("contact-name").textContent = scenario.story.contact.name;

        $("contact-avatar").textContent = scenario.story.contact.avatar_text;

        $("contact-status").textContent = scenario.story.contact.subtitle;

        enterNode(scenario.story.start);
    }

    async function openTopic(topicId, trigger) {
        cancelTasks();

        history = [];
        scenario = null;
        currentNodeId = null;
        opener = trigger;

        resetView();

        if (!dialog.open) dialog.showModal();

        document.body.classList.add("verify-story-open");
        focusHeading("context-title");

        const token = generation;

        try {
            const data = await loadScenario(topicId);

            if (token !== generation || !dialog.open) return;

            if (!data) {
                hidden("conversation");

                $("title").textContent = "Cerita belum tersedia";

                $("context-title").textContent =
                    "Belum ada skenario aktif untuk topik ini";

                $("context-description").textContent =
                    "Silakan pilih topik lain atau coba kembali setelah cerita ditambahkan.";

                return;
            }

            startScenario(data);
        } catch (error) {
            if (token !== generation || !dialog.open) return;

            console.error(error);

            showError(
                "Data cerita belum berhasil dimuat. Periksa file data contoh dan coba lagi.",
            );
        }
    }

    document.querySelectorAll("[data-story-sim]").forEach((trigger) => {
        trigger.addEventListener("click", () => {
            openTopic(Number(trigger.dataset.storySim), trigger);
        });
    });

    dialog.querySelectorAll("[data-story-close]").forEach((trigger) => {
        trigger.addEventListener("click", () => {
            dialog.close();
        });
    });

    dialog.querySelectorAll("[data-story-payment]").forEach((trigger) => {
        trigger.addEventListener("click", () => {
            selectPayment(trigger.dataset.storyPayment);
        });
    });

    dialog
        .querySelector("[data-story-show-reflection]")
        .addEventListener("click", () => {
            hidden("consequence");
            hidden("reflection", false);

            setStage("reflection");
            focusHeading("reflection-title");
        });

    dialog
        .querySelector("[data-story-restart]")
        .addEventListener("click", () => {
            if (!scenario) return;

            cancelTasks();
            history = [];

            const data = scenario;

            resetView();
            startScenario(data);
        });

    $("save-button").addEventListener("click", saveProgress);

    $("save-later").addEventListener("click", () => {
        /*
         * Hapus titipan hanya jika milik percobaan yang sedang dibuka.
         */
        try {
            const raw = sessionStorage.getItem(PENDING_RESULT_KEY);
            const draft = raw ? JSON.parse(raw) : null;

            if (draft?.payload?.attempt_uuid === pendingResult?.attempt_uuid) {
                sessionStorage.removeItem(PENDING_RESULT_KEY);
            }
        } catch (error) {
            console.error(error);
        }

        dialog.close();
    });

    dialog
        .querySelector("[data-story-finish]")
        .addEventListener("click", showSaveConfirmation);

    dialog
        .querySelector("[data-story-back-result]")
        .addEventListener("click", backToResult);

    dialog.addEventListener("close", () => {
        cancelTasks();
        hidden("typing");

        document.body.classList.remove("verify-story-open");

        if (opener?.isConnected) opener.focus();
    });
})();
