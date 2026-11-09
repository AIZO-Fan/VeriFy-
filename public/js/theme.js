const THEME_KEY = 'verify-theme';

const getSavedTheme = () => {
    try {
        const theme = localStorage.getItem(THEME_KEY);

        return theme === 'light' || theme === 'dark' ? theme : null;
    } catch {
        return null;
    }
};

const getPreferredTheme = () => {
    const savedTheme = getSavedTheme();

    if (savedTheme) {
        return savedTheme;
    }

    const prefersDark = window.matchMedia(
        '(prefers-color-scheme: dark)'
    ).matches;

    return prefersDark ? 'dark' : 'light';
};

const applyTheme = (theme) => {
    document.documentElement.setAttribute('data-theme', theme);

    try {
        localStorage.setItem(THEME_KEY, theme);
    } catch {
        // localStorage mungkin diblokir; tema tetap aktif untuk sesi ini.
    }

    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        const isActive = button.dataset.themeOption === theme;

        button.setAttribute('aria-pressed', String(isActive));
    });
};

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getPreferredTheme());

    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        button.addEventListener('click', () => {
            applyTheme(button.dataset.themeOption);
        });
    });
});