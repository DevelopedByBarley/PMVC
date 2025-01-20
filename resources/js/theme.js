export function theme() {
  const themeToggle = document.getElementById('theme-toggle') || null;

  document.addEventListener('DOMContentLoaded', () => {
    let defaultTheme = localStorage.getItem('theme') || document.documentElement.getAttribute('data-bs-theme');
    if (defaultTheme === 'dark') {
      if (themeToggle) themeToggle.setAttribute('checked', true)
    }
    document.documentElement.setAttribute('data-bs-theme', defaultTheme);
  })

  if (themeToggle) {
    themeToggle.onchange = () => {
      let defaultTheme = localStorage.getItem('theme') || document.documentElement.getAttribute('data-bs-theme');
      let theme = setColorTheme(defaultTheme);
      localStorage.setItem('theme', theme);
      document.documentElement.setAttribute('data-bs-theme', theme);
    }

    function setColorTheme(theme) {
      return theme === 'dark' ? 'light' : 'dark';
    }
  }
}