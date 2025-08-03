export function toast() {
  console.log('Toast is already running!')
  const toast = document.getElementById('toast');
  if (toast) {
    hideToast(toast)
  }
}

function hideToast(toast) {
  setTimeout(() => {

    toast.classList.remove('show');
  }, 2000)


  document.addEventListener('DOMContentLoaded', function () {
    const toastElement = document.getElementById('toast');
    if (toastElement) {
      const toast = new bootstrap.Toast(toastElement);

      // Auto hide after delay
      setTimeout(() => {
        toast.hide();
      }, 5000); // Set delay in milliseconds (e.g., 5000ms = 5s)

      // Remove from DOM after hide
      toastElement.addEventListener('hidden.bs.toast', function () {
        this.remove();
      });
    }
  });
}