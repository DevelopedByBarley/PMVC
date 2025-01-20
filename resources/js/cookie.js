export function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

export function setCookie(name, value, days) {
  let expires = "";
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

export function cookie() {
  const cookieBannerCon = document.getElementById('cookie-banner-container');
  const analyticalCheckbox = document.getElementById('analytical-checkbox');
  const marketingCheckbox = document.getElementById('marketing-checkbox');

  const bannerAcceptNecessaryBtn = document.getElementById('banner-cookie-consent-accept-necessary');
  const bannerAcceptAllBtn = document.getElementById('banner-cookie-consent-accept-all');
  const submitCookeConsentModalBtn = document.getElementById('submit-consent-modal');
  const cookieModal = new bootstrap.Modal(document.getElementById('cookie-modal'));

  submitCookeConsentModalBtn.addEventListener('click', function (e) {
    e.preventDefault();
    const cookieLevel = getConsentLevel();
    setCookie('cookie-consent', cookieLevel, 30)

    cookieModal.hide();
    cookieBannerCon.classList.add('d-none');

  })

  bannerAcceptNecessaryBtn.addEventListener('click', () => {
    setCookie('cookie-consent', 1, 30);
    cookieBannerCon.classList.add('d-none');
    console.log('Elfogadva: Szükséges sütik');
  });

  bannerAcceptAllBtn.addEventListener('click', () => {
    setCookie('cookie-consent', 9, 30);
    cookieBannerCon.classList.add('d-none');

    startAnalyticsCookies();
    startMarketingCookies();
    console.log('Elfogadva: Összes sütik');
  });

  analyticalCheckbox.addEventListener('change', function () {
    const consentLevel = getConsentLevel();
    console.log('Marketing cookies state:', this.checked);
    console.log(consentLevel);
  });

  marketingCheckbox.addEventListener('change', function () {
    const consentLevel = getConsentLevel();
    console.log('Marketing cookies state:', this.checked);
    console.log(consentLevel);
  });


  function getConsentLevel() {
    let level = 1; // Necessary cookies

    if (analyticalCheckbox.checked) {
      level += 3; // Add Analytical cookies
    }
    if (marketingCheckbox.checked) {
      level += 5; // Add Marketing cookies
    }
    return level;
  }

  function startAnalyticsCookies() {
    console.log('Analytics cookies started');

    // Google Analytics script betöltése, ha nincs betöltve
    if (!window.ga) {
      const script = document.createElement('script');
      script.src = 'https://www.googletagmanager.com/gtag/js?id=G-';
      script.async = true; // Aszinkron betöltés
      document.head.appendChild(script);

      script.onload = () => {
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-1CFFXW8V1H');
        console.log('Google Analytics script loaded and configured');
      };
    } else {
      console.log('Google Analytics is already loaded');
    }
  }


  function startMarketingCookies() {
    console.log('Marketing cookies started');
  }


  const runCookiesByLevel = (cookieLevel) => {
    switch (cookieLevel) {
      case 1:
        console.log('Necessary consent approved!');
        break;

      case 4:
        console.log('Necessary and Analytical consent approved!');
        startAnalyticsCookies();
        break;

      case 6:
        console.log('Necessary and Marketing consent approved!');
        startMarketingCookies();
        break;

      case 9:
        console.log('Necessary, Analytical, and Marketing consent approved!');
        startAnalyticsCookies();
        startMarketingCookies();
        break;

      default:
        console.log('No or invalid cookie consent.');
        break;
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const cookieLevel = parseInt(getCookie('cookie-consent'), 10);
    if (isNaN(cookieLevel)) {
      cookieBannerCon.classList.remove('d-none');
      return;
    }
    runCookiesByLevel(cookieLevel);
  });

}