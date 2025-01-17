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
}