(() => {
    'use strict';

    const form = document.getElementById('verifyLoginForm');

    if (!form) return;

    const email = form.querySelector('[name="email"]');
    const password = form.querySelector('[name="password"]');

    const inputs = [email, password];

    const submit = document.getElementById(
        'verifyLoginSubmit'
    );

    const status = document.getElementById(
        'verifyLoginStatus'
    );

    const submitLabel = submit.querySelector(
        '[data-login-submit-label]'
    );

    const arrow = submit.querySelector(
        '[data-login-submit-arrow]'
    );

    const spinner = submit.querySelector(
        '.verify-login-spinner'
    );

    let submitting = false;

    /*
     * JavaScript siap:
     * gunakan pesan validasi di bawah input.
     */
    form.noValidate = true;

    function getErrorElement(input) {
        return document.getElementById(
            `login-${input.name}-error`
        );
    }

    function describeError(input, visible) {
        const errorId = `login-${input.name}-error`;

        const descriptions = new Set(
            (input.getAttribute('aria-describedby') || '')
                .split(/\s+/)
                .filter(Boolean)
        );

        if (visible) {
            descriptions.add(errorId);
        } else {
            descriptions.delete(errorId);
        }

        if (descriptions.size) {
            input.setAttribute(
                'aria-describedby',
                [...descriptions].join(' ')
            );
        } else {
            input.removeAttribute('aria-describedby');
        }
    }

    function getMessage(input) {
        if (input.validity.valueMissing) {
            return input.name === 'email'
                ? 'Masukkan alamat email kamu.'
                : 'Masukkan password akunmu.';
        }

        if (
            input.name === 'email' &&
            input.validity.typeMismatch
        ) {
            return 'Periksa kembali format email kamu.';
        }

        if (!input.validity.valid) {
            return input.validationMessage;
        }

        return '';
    }

    function validate(input) {
        const message = getMessage(input);
        const invalid = Boolean(message);
        const error = getErrorElement(input);

        input.classList.toggle('is-invalid', invalid);

        if (invalid) {
            input.setAttribute('aria-invalid', 'true');
        } else {
            input.removeAttribute('aria-invalid');
        }

        describeError(input, invalid);

        error.textContent = message;
        error.hidden = !invalid;

        return !invalid;
    }

    function clearError(input) {
        input.classList.remove('is-invalid');
        input.removeAttribute('aria-invalid');

        describeError(input, false);

        const error = getErrorElement(input);

        error.textContent = '';
        error.hidden = true;
    }

    function setSubmitting(value) {
        submitting = value;
        submit.disabled = value;

        submitLabel.textContent = value
            ? 'Sedang masuk…'
            : 'Login';

        spinner.hidden = !value;
        arrow.hidden = value;

        if (value) {
            form.setAttribute('aria-busy', 'true');

            status.textContent =
                'Permintaan login sedang dikirim.';
        } else {
            form.removeAttribute('aria-busy');
            status.textContent = '';
        }
    }

    /*
     * Tidak ada validasi saat blur atau mengetik.
     * Mengetik hanya membersihkan pesan lama.
     *
     * Kedua pesan dibersihkan karena error autentikasi
     * "Email atau password salah" terkait pasangan field.
     */
    inputs.forEach((input) => {
        input.addEventListener('input', () => {
            inputs.forEach(clearError);
            status.textContent = '';
        });
    });

    form.addEventListener('submit', (event) => {
        if (submitting) {
            event.preventDefault();
            return;
        }

        email.value = email.value.trim();

        /*
         * Password tidak di-trim atau diubah.
         */
        let firstInvalid = null;

        inputs.forEach((input) => {
            if (!validate(input) && !firstInvalid) {
                firstInvalid = input;
            }
        });

        if (firstInvalid) {
            event.preventDefault();

            status.textContent =
                'Periksa kembali kolom yang ditandai.';

            firstInvalid.focus();
            return;
        }

        /*
         * Biarkan form POST ke controller.
         */
        setSubmitting(true);
    });

    window.addEventListener('pageshow', () => {
        setSubmitting(false);
    });

    /* Tampilkan / sembunyikan password */

    const toggle = document.getElementById(
        'verifyLoginPasswordToggle'
    );

    if (toggle) {
        const showIcon = toggle.querySelector(
            '[data-login-password-icon="show"]'
        );

        const hideIcon = toggle.querySelector(
            '[data-login-password-icon="hide"]'
        );

        toggle.hidden = false;

        function setPasswordVisible(visible) {
            password.type = visible ? 'text' : 'password';

            toggle.setAttribute(
                'aria-pressed',
                String(visible)
            );

            toggle.setAttribute(
                'aria-label',
                visible
                    ? 'Sembunyikan password'
                    : 'Tampilkan password'
            );

            showIcon.hidden = visible;
            hideIcon.hidden = !visible;
        }

        toggle.addEventListener('click', () => {
            setPasswordVisible(
                password.type === 'password'
            );
        });

        window.addEventListener('pagehide', () => {
            setPasswordVisible(false);
        });

        window.addEventListener('pageshow', () => {
            setPasswordVisible(false);
        });
    }
})();