(() => {
    "use strict";

    const form = document.getElementById("verifyRegisterForm");

    if (!form) return;

    const submit = document.getElementById("verifyRegisterSubmit");
    const status = document.getElementById("verifyRegisterStatus");

    const submitLabel = submit.querySelector("[data-register-submit-label]");

    const arrow = submit.querySelector("[data-register-submit-arrow]");

    const spinner = submit.querySelector(".verify-register-spinner");

    const inputs = [
        form.querySelector('[name="name"]'),
        form.querySelector('[name="email"]'),
        form.querySelector('[name="password"]'),
    ].filter(Boolean);

    let submitting = false;

    /*
     * Validasi custom aktif hanya setelah script siap.
     */
    form.noValidate = true;

    function errorElement(input) {
        return document.getElementById(`register-${input.name}-error`);
    }

    function describeError(input, visible) {
        const errorId = `register-${input.name}-error`;

        const descriptions = new Set(
            (input.getAttribute("aria-describedby") || "")
                .split(/\s+/)
                .filter(Boolean),
        );

        if (visible) {
            descriptions.add(errorId);
        } else {
            descriptions.delete(errorId);
        }

        if (descriptions.size) {
            input.setAttribute("aria-describedby", [...descriptions].join(" "));
        } else {
            input.removeAttribute("aria-describedby");
        }
    }

    function getMessage(input) {
        const value = input.value;

        if (input.name === "name" && !value.trim()) {
            return "Masukkan nama lengkap kamu";
        }

        if (input.validity.valueMissing) {
            return (
                {
                    name: "Masukkan nama lengkap kamu",
                    email: "Masukkan alamat email kamu",
                    password: "Masukkan password untuk akunmu",
                }[input.name] || "Kolom ini wajib diisi"
            );
        }

        if (input.name === "email" && input.validity.typeMismatch) {
            return "Masukkan email yang valid, misalnya nama@email.com";
        }

        if (input.minLength > 0 && value.length < input.minLength) {
            return `Gunakan minimal ${input.minLength} karakter.`;
        }

        if (input.maxLength > 0 && value.length > input.maxLength) {
            return `Gunakan maksimal ${input.maxLength} karakter.`;
        }

        if (!input.validity.valid) {
            return input.validationMessage;
        }

        return "";
    }

    function validate(input) {
        const message = getMessage(input);
        const error = errorElement(input);
        const invalid = Boolean(message);

        input.classList.toggle("is-invalid", invalid);

        if (invalid) {
            input.setAttribute("aria-invalid", "true");
        } else {
            input.removeAttribute("aria-invalid");
        }

        describeError(input, invalid);

        if (error) {
            error.textContent = message;
            error.hidden = !invalid;
        }

        return !invalid;
    }

    function setSubmitting(value) {
        submitting = value;
        submit.disabled = value;

        submitLabel.textContent = value ? "Membuat akun…" : "Buat akun";

        spinner.hidden = !value;
        arrow.hidden = value;

        if (value) {
            form.setAttribute("aria-busy", "true");
            status.textContent = "Permintaan pendaftaran sedang dikirim.";
        } else {
            form.removeAttribute("aria-busy");
            status.textContent = "";
        }
    }

    /*
     * Saat pengguna memperbaiki input, hapus pesan lama.
     * Jangan lakukan validasi ulang sampai tombol submit ditekan.
     */
    inputs.forEach((input) => {
        input.addEventListener("input", () => {
            input.classList.remove("is-invalid");
            input.removeAttribute("aria-invalid");

            describeError(input, false);

            const error = errorElement(input);

            if (error) {
                error.textContent = "";
                error.hidden = true;
            }

            status.textContent = "";
        });
    });

    form.addEventListener("submit", (event) => {
        if (submitting) {
            event.preventDefault();
            return;
        }

        /*
         * Rapikan nama dan email, bukan password.
         */
        form.elements.name.value = form.elements.name.value.trim();

        form.elements.email.value = form.elements.email.value.trim();

        let firstInvalid = null;

        inputs.forEach((input) => {
            if (!validate(input) && !firstInvalid) {
                firstInvalid = input;
            }
        });

        if (firstInvalid) {
            event.preventDefault();

            status.textContent = "Periksa kembali kolom yang ditandai.";

            firstInvalid.focus();
            return;
        }

        /*
         * Biarkan form melakukan POST biasa.
         * Tidak menggunakan fetch atau membuat akun di browser.
         */
        setSubmitting(true);
    });

    /*
     * Pulihkan tombol jika kembali melalui Back/Forward.
     */
    window.addEventListener("pageshow", () => {
        setSubmitting(false);
    });

    /* Tampilkan / sembunyikan password */

    const password = form.querySelector('[name="password"]');

    const toggle = document.getElementById("verifyRegisterPasswordToggle");

    if (toggle && password) {
        const showIcon = toggle.querySelector('[data-password-icon="show"]');

        const hideIcon = toggle.querySelector('[data-password-icon="hide"]');

        toggle.hidden = false;

        function setPasswordVisible(visible) {
            password.type = visible ? "text" : "password";

            toggle.setAttribute("aria-pressed", String(visible));

            toggle.setAttribute(
                "aria-label",
                visible ? "Sembunyikan password" : "Tampilkan password",
            );

            showIcon.hidden = visible;
            hideIcon.hidden = !visible;
        }

        toggle.addEventListener("click", () => {
            setPasswordVisible(password.type === "password");
        });

        window.addEventListener("pagehide", () => {
            setPasswordVisible(false);
        });

        window.addEventListener("pageshow", () => {
            setPasswordVisible(false);
        });
    }
})();
