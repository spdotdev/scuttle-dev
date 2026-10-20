// Theme toggle for the sub-pages. The home page has its own, richer script (main.js).
const html = document.documentElement;
const toggle = document.getElementById('theme-toggle');
let theme = 'dark';
try {
  theme = localStorage.getItem('theme') || 'dark';
} catch (e) {
  theme = 'dark';
}

function apply(next) {
  html.setAttribute('data-theme', next);
  if (toggle) {
    toggle.textContent = next === 'dark' ? 'Light mode' : 'Dark mode';
    toggle.setAttribute('aria-label', next === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode');
  }
}

apply(theme);

if (toggle) {
  toggle.addEventListener('click', () => {
    const next = html.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    try {
      localStorage.setItem('theme', next);
    } catch (e) {
      // Storage can be blocked; the choice then only lasts for this page.
    }
    apply(next);
  });
}
