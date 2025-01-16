import { getCookie } from './cookie.js';

/**
 * @example
 *  
 *    <div class="form-outline">
 *        <label class="form-label" for="form3Example3">Email address</label>
 *        <input name="email" type="email" id="form3Example3" class="form-control" validators='{
 *           "name": "email",
 *            "required": true,
 *            "email": true,
 *            "minLength": 12,
 *            "maxLength": 50
 *        }' />
 *    </div>
 */

export function validator() {

  console.log('Validator is already running!');

  const lang = getCookie('lang') ? getCookie('lang') : 'en';


  function checkValidators(options, inputValue, targetElement) {
    let errors = [];

    Object.keys(options).forEach(key => {
      let value = options[key];

      switch (key) {
        case "required":
          if (value === true) {
            const requiredMessage = {
              en: "This field is required.",
              hu: "A mező kitöltése kötelező."
            };

            if (inputValue.trim().length === 0) {
              errors.push(requiredMessage[lang]);
            }
          }
          break;

        case "noSpaces":
          if (value === true && inputValue.includes(" ")) {
            errors.push("A mező értéke nem tartalmazhat szóközt!");
          }
          break;

        case "num":
          if (typeof value === "boolean" && value === true) {
            // Csak akkor ad hibát, ha bármilyen nem szám karakter található
            if (!/^\d+$/.test(inputValue)) {
              errors.push("A mező értéke csak szám lehet!");
            }
          }
          break;


        case "minLength":
          if (typeof value === 'number' && inputValue.trim().length < value) {
            const minLengthMessage = {
              en: `The length of the field cannot be less than ${value}`,
              hu: `A mező hossza nem lehet kevesebb mint ${value}.`
            };
            errors.push(minLengthMessage[lang]);
          }
          break;

        case "maxLength":
          if (typeof value === 'number' && inputValue.trim().length > value) {
            const maxLengthMessage = {
              en: `The length of the field cannot be more than ${value}`,
              hu: `A mező hossza nem lehet több mint ${value}.`
            };
            errors.push(maxLengthMessage[lang]);
          }
          break;

        case "hasNum":
          if (value === true && !/\d/.test(inputValue.trim())) {
            errors.push("A mezőnek tartalmaznia kell legalább egy számot!");
          }
          break;

        case "hasUppercase":
          if (value === true && !/[A-Z]/.test(inputValue)) {
            errors.push("A mezőnek tartalmaznia kell legalább egy nagybetűt!");
          }
          break;

        case "split":
          if (value === true) {
            const nameParts = inputValue.split(" ");
            if ((inputValue !== "" && nameParts.length < 2) || (nameParts.length >= 2 && nameParts[1].length === 0)) {
              errors.push("Az mező értékének minimum 2 szóból kell állnia");
            }
          }
          break;

        case "password":
          if (value === true) {
            const passwordValue = inputValue.trim();
            const hasUpperCase = /[A-Z]/.test(passwordValue);
            const hasLowerCase = /[a-z]/.test(passwordValue);
            const hasNumber = /\d/.test(passwordValue);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(passwordValue);
            const isLengthValid = passwordValue.length >= 8;

            if (passwordValue === "") {
              errors.push("Kérlek add meg a jelszavadat!");
            }
            if (!hasUpperCase) {
              errors.push("A jelszónak tartalmaznia kell legalább egy nagybetűt!");
            }
            if (!hasLowerCase) {
              errors.push("A jelszónak tartalmaznia kell legalább egy kisbetűt!");
            }
            if (!hasNumber) {
              errors.push("A jelszónak tartalmaznia kell legalább egy számot!");
            }
            if (!hasSpecialChar) {
              errors.push("A jelszónak tartalmaznia kell legalább egy speciális karaktert!");
            }
            if (!isLengthValid) {
              errors.push("A jelszónak legalább 8 karakter hosszúnak kell lennie!");
            }
          }
          break;

        case "match":
          if (value) {
            const match = targetElement.parentElement.parentElement.querySelector(`[name="${value}"]`);


            if (inputValue !== match.value && inputValue !== '') {
              errors.push("A 2 jelszó nem megegyező!");
            } else {
              match.setCustomValidity("");
              match.style.border = "2px solid lightgreen";
              const alert = targetElement.parentElement.parentElement.querySelector(`#${value}-validator-alert`)
              if (alert) {
                alert.remove();
              }

            }
          }
          break;

        case "email":
          if (value === true) {
            const emailMessage = {
              en: "Please enter a valid e-mail address.",
              hu: "Kérem adjon meg érvényes e-mail címet."
            };
            const emailValue = inputValue.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(emailValue)) {
              errors.push(emailMessage[lang]);
            }
          }
          break;

        case "phone":
          if (value === true) {
            const phoneValue = inputValue.trim();
            const phonePattern = /^(?:\+36|06)\d{9}$|^(?:\+36-\d{2}-\d{3}-\d{4})$/;
            if (!phonePattern.test(phoneValue)) {
              errors.push("A telefonszámnak a következő formátumok valamelyikét kell követnie: +36-30-551-1234, +36305511234, vagy 06305511234");
            }
          }
          break;

        default:
          break;
      }
    });

    // Set custom validity and border color based on errors
    if (errors.length > 0) {
      targetElement.setCustomValidity(errors[0]); // Set only the first error as custom validity
      targetElement.style.border = "2px solid salmon";
    } else {
      targetElement.setCustomValidity(""); // Clear custom validity
      targetElement.style.border = "2px solid lightgreen";
    }

    return errors;
  }

  const forms = document.querySelectorAll('form');
  forms.forEach(form => {
    let inputElements = form.querySelectorAll("[validators]");

    inputElements.forEach(inputElement => {
      let options = JSON.parse(inputElement.getAttribute("validators"));
      let name = options.name;
      let targetElement = inputElement.parentElement.querySelector(`[name="${name}"]`);

      let inputAlert = document.createElement("div");
      inputAlert.id = `${name}-validator-alert`;
      inputAlert.style.color = "red";
      inputAlert.style.marginTop = ".5rem";
      targetElement.parentNode.insertBefore(inputAlert, inputElement.nextSibling);

      inputElement.addEventListener("input", function (e) {
        let errors = checkValidators(options, e.target.value, targetElement);
        inputAlert.innerHTML = ""; // Clear previous error messages
        errors.forEach(error => {
          let errorElement = document.createElement("div");
          errorElement.textContent = error;
          inputAlert.appendChild(errorElement);
        });
      });
    });
  });
}