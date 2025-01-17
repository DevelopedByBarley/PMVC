export function alert() {
  console.log('Alert is already running!')
  const alert = document.getElementById('alert');
  if (alert) {
    hideAlert(alert)
  }
}

function hideAlert(alert) {
  setTimeout(() => {

    alert.classList.remove('show');
  }, 2000)
}