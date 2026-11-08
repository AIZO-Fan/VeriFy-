const menu = document.querySelector(".menu-button");
menu?.addEventListener("click", () => {
    const open = document.querySelector("#navigation").classList.toggle("open");
    menu.setAttribute("aria-expanded", String(open));
});
const tabs = [...document.querySelectorAll("[data-step]")];
function selectTab(tab) {
    tabs.forEach((t) => {
        const active = t === tab;
        t.classList.toggle("selected", active);
        t.setAttribute("aria-selected", String(active));
        t.tabIndex = active ? 0 : -1;
        document.getElementById("step-" + t.dataset.step).hidden = !active;
    });
}
tabs.forEach((tab, i) => {
    tab.addEventListener("click", () => selectTab(tab));
    tab.addEventListener("keydown", (e) => {
        let n;
        if (e.key === "ArrowRight") n = (i + 1) % tabs.length;
        else if (e.key === "ArrowLeft") n = (i + tabs.length - 1) % tabs.length;
        else if (e.key === "Home") n = 0;
        else if (e.key === "End") n = tabs.length - 1;
        else return;
        e.preventDefault();
        selectTab(tabs[n]);
        tabs[n].focus();
    });
});
const track = document.querySelector(".simulator-track");
document
    .querySelector(".next")
    ?.addEventListener("click", () =>
        track.scrollBy({ left: track.clientWidth * 0.8 }),
    );
document
    .querySelector(".prev")
    ?.addEventListener("click", () =>
        track.scrollBy({ left: -track.clientWidth * 0.8 }),
    );
const quizzes = [
    {
        title: "Introduction to Digital Scam",
        message:
            "Pesan dari nomor asing: “Selamat! Anda menang hadiah. Kirim biaya administrasi sekarang.”",
        options: [
            "Transfer supaya hadiah tidak hangus",
            "Verifikasi lewat saluran resmi dan jangan transfer",
            "Bagikan nomor rekening dan OTP",
        ],
        correct: 1,
        explanation:
            "Hadiah yang meminta pembayaran di awal perlu dicurigai. Jangan menyerahkan uang atau data sensitif.",
    },
    {
        title: "Social Engineering",
        message:
            "Seseorang mengaku petugas bank dan meminta kode OTP untuk membatalkan transaksi.",
        options: [
            "Berikan OTP agar transaksi dibatalkan",
            "Akhiri percakapan dan hubungi bank melalui nomor resmi",
            "Minta ia menunggu lalu kirim PIN",
        ],
        correct: 1,
        explanation:
            "Jangan membagikan OTP, PIN, atau kata sandi. Identitas penelepon harus diverifikasi secara terpisah.",
    },
    {
        title: "Phishing & Malicious Link",
        message:
            "Pesan meminta login lewat tautan yang domainnya berbeda dari situs resmi.",
        options: [
            "Login karena ada logo perusahaan",
            "Klik dan isi kata sandi untuk memastikan",
            "Buka aplikasi resmi secara mandiri",
        ],
        correct: 2,
        explanation:
            "Logo dan tampilan situs bisa ditiru. Buka layanan melalui aplikasi atau alamat resmi yang Anda ketahui.",
    },
    {
        title: "Online Shopping & Investment Scams",
        message:
            "Penjual menawarkan diskon besar jika Anda mentransfer langsung di luar marketplace.",
        options: [
            "Tetap bertransaksi di platform dan periksa reputasi penjual",
            "Transfer sekarang sebelum barang habis",
            "Kirim foto kartu identitas sebagai jaminan",
        ],
        correct: 0,
        explanation:
            "Transaksi di luar platform dapat menghilangkan perlindungan yang disediakan platform.",
    },
    {
        title: "Account & Data Protection",
        message:
            "Anda memakai kata sandi yang sama untuk email, belanja, dan media sosial.",
        options: [
            "Tetap gunakan karena mudah diingat",
            "Buat kata sandi unik dan aktifkan autentikasi multifaktor",
            "Bagikan kata sandi pada teman untuk cadangan",
        ],
        correct: 1,
        explanation:
            "Kata sandi unik membantu membatasi dampak jika salah satu akun bocor.",
    },
    {
        title: "Scam Reporting & Prevention",
        message:
            "Anda baru sadar telah mentransfer uang ke pihak yang diduga menipu.",
        options: [
            "Hapus semua pesan agar tidak panik",
            "Kirim uang lagi ketika dijanjikan pengembalian dana",
            "Simpan bukti dan segera hubungi bank melalui saluran resmi",
        ],
        correct: 2,
        explanation:
            "Simpan bukti dan segera hubungi bank atau penyedia pembayaran. Pemulihan dana tidak dapat dijamin.",
    },
];
const dialog = document.querySelector("#quiz-dialog");
function openQuiz(id) {
    const q = quizzes[id - 1];
    if (!q || !dialog) return;
    document.querySelector("#quiz-title").textContent = q.title;
    document.querySelector("#quiz-message").textContent = q.message;
    const choices = document.querySelector("#quiz-options");
    choices.replaceChildren();
    const feedback = document.querySelector("#quiz-feedback");
    feedback.textContent = "";
    feedback.className = "";
    q.options.forEach((label, index) => {
        const button = document.createElement("button");
        button.textContent = label;
        button.addEventListener("click", () => {
            choices
                .querySelectorAll("button")
                .forEach((b) => (b.disabled = true));
            button.classList.add("chosen");
            feedback.className =
                index === q.correct ? "feedback-good" : "feedback-bad";
            feedback.textContent =
                (index === q.correct
                    ? "Tepat! "
                    : "Belum tepat. Jawaban yang disarankan: " +
                      q.options[q.correct] +
                      ". ") + q.explanation;
        });
        choices.append(button);
    });
    dialog.showModal();
}
document
    .querySelectorAll("[data-sim]")
    .forEach((b) =>
        b.addEventListener("click", () => openQuiz(Number(b.dataset.sim))),
    );
document
    .querySelectorAll(".close-dialog")
    .forEach((b) => b.addEventListener("click", () => dialog.close()));
dialog?.addEventListener("click", (e) => {
    const r = dialog.getBoundingClientRect();
    if (
        e.clientX < r.left ||
        e.clientX > r.right ||
        e.clientY < r.top ||
        e.clientY > r.bottom
    )
        dialog.close();
});
const topic = Number(new URLSearchParams(location.search).get("topic"));
if (dialog && topic >= 1 && topic <= 6) openQuiz(topic);

/* =========================================
   VERIFY NAVBAR
   ========================================= */

(() => {
    const header = document.getElementById('verifyHeader');
    const toggle = document.getElementById('verifyMenuToggle');
    const navigation = document.getElementById('verifyNavigation');

    if (!header || !toggle || !navigation) {
        return;
    }

    const mobileQuery = window.matchMedia('(max-width: 1000px)');

    function updateHeader() {
        header.classList.toggle('is-scrolled', window.scrollY > 12);
    }

    function setMenuOpen(open, returnFocus = false) {
        navigation.classList.toggle('is-open', open);

        toggle.setAttribute('aria-expanded', String(open));

        toggle.setAttribute(
            'aria-label',
            open ? 'Tutup navigasi' : 'Buka navigasi'
        );

        if (returnFocus) {
            toggle.focus();
        }
    }

    toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';
        setMenuOpen(!isOpen);
    });

    navigation.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            setMenuOpen(false);
        });
    });

    document.addEventListener('click', (event) => {
        if (!header.contains(event.target)) {
            setMenuOpen(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (
            event.key === 'Escape' &&
            toggle.getAttribute('aria-expanded') === 'true'
        ) {
            setMenuOpen(false, true);
        }
    });

    header.addEventListener('focusout', (event) => {
        if (!header.contains(event.relatedTarget)) {
            setMenuOpen(false);
        }
    });

    mobileQuery.addEventListener('change', () => {
        setMenuOpen(false);
    });

    window.addEventListener('scroll', updateHeader, { passive: true });

    updateHeader();
})();

/* =========================================
   VERIFY STAT COUNTERS
   ========================================= */

(() => {
    const counters = document.querySelectorAll(
        '[data-verify-counter]'
    );

    if (!counters.length) {
        return;
    }

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    );

    const formatter = new Intl.NumberFormat('id-ID');

    function animateCounter(element) {
        const target = Number(element.dataset.verifyCounter);

        if (!Number.isFinite(target) || target < 0) {
            return;
        }

        if (reducedMotion.matches) {
            element.textContent = formatter.format(target);
            return;
        }

        const duration = 1000;
        let startTime = null;

        function update(timestamp) {
            if (startTime === null) {
                startTime = timestamp;
            }

            if (reducedMotion.matches) {
                element.textContent = formatter.format(target);
                return;
            }

            const progress = Math.min(
                (timestamp - startTime) / duration,
                1
            );

            const easedProgress = 1 - Math.pow(1 - progress, 3);

            element.textContent = formatter.format(
                Math.round(target * easedProgress)
            );

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }

    if (!('IntersectionObserver' in window)) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                animateCounter(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { threshold: 0.5 }
    );

    counters.forEach((counter) => {
        observer.observe(counter);
    });
})();

/* =========================================
   VERIFY — PHONE DEMO & SCROLL HINT
   Versi tanpa tombol Jeda
   ========================================= */

(() => {
    function initVerifyHero() {
        const reducedMotion = window.matchMedia(
            '(prefers-reduced-motion: reduce)'
        );

        /* Scroll ke How It Works */
        const scrollHint = document.querySelector(
            '.verify-scroll-hint'
        );

        scrollHint?.addEventListener('click', (event) => {
            const target = document.getElementById(
                'how-it-works'
            );

            if (!target) {
                return;
            }

            event.preventDefault();

            target.scrollIntoView({
                behavior: reducedMotion.matches
                    ? 'instant'
                    : 'smooth',
                block: 'start'
            });
        });

        /* Ilustrasi ponsel */
        const demo = document.querySelector(
            '[data-phone-demo]'
        );

        if (!demo || demo.dataset.initialized === 'true') {
            return;
        }

        const scenes = [
            ...demo.querySelectorAll('[data-phone-scene]')
        ];

        const dots = [
            ...demo.querySelectorAll('[data-phone-dot]')
        ];

        if (scenes.length < 2) {
            return;
        }

        demo.dataset.initialized = 'true';

        let currentScene = 0;
        let timer = null;

        const duration = 4500;

        function showScene(index) {
            currentScene = index;

            scenes.forEach((scene, sceneIndex) => {
                const active = sceneIndex === index;

                scene.hidden = !active;
                scene.classList.toggle(
                    'is-visible',
                    active
                );
            });

            dots.forEach((dot, dotIndex) => {
                const active = dotIndex === index;

                dot.classList.toggle(
                    'is-active',
                    active
                );

                dot.setAttribute(
                    'aria-pressed',
                    String(active)
                );
            });
        }

        function stopAutoplay() {
            if (timer !== null) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        function startAutoplay() {
            stopAutoplay();

            /*
             * Hormati pengaturan pengurangan animasi.
             * Tombol titik tetap bisa digunakan.
             */
            if (document.hidden || reducedMotion.matches) {
                return;
            }

            timer = window.setInterval(() => {
                showScene(
                    (currentScene + 1) % scenes.length
                );
            }, duration);
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                showScene(index);

                /* Setelah dipilih, autoplay mulai lagi */
                startAutoplay();
            });
        });

        document.addEventListener(
            'visibilitychange',
            startAutoplay
        );

        reducedMotion.addEventListener(
            'change',
            startAutoplay
        );

        showScene(0);
        startAutoplay();
    }

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initVerifyHero,
            { once: true }
        );
    } else {
        initVerifyHero();
    }
})();