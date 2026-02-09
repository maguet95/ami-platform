import './bootstrap';

// Theme toggle (dark/light) - dark by default
function initTheme() {
    const stored = localStorage.getItem('ami-theme');
    if (stored === 'light') {
        document.documentElement.classList.remove('dark');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('ami-theme', 'dark');
    }
}

window.toggleTheme = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('ami-theme', isDark ? 'dark' : 'light');
};

initTheme();
