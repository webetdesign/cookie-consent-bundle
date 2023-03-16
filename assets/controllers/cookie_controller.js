import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static values = {
    show: Boolean,
    categories: Array
  };
  consentEventHandler(){
    document.addEventListener('cookie-consent-form-submit-successful', (e)=>{
      const detail = JSON.parse(e.detail);
      function checkConsent (property){
        return (detail.hasOwnProperty(`cookie_consent[${property}]`) && detail[`cookie_consent[${property}]`]);
      }

      if (checkConsent("use_all_cookies")){
        this.allowAll()
      } else if (checkConsent("use_only_functional_cookies")) {
        this.disallowAll()
      }else {
        this.categoriesValue.forEach((cat)=>{
          if(checkConsent(cat) === "true"){
            if (dataLayer.filter(obj =>{ return obj.event === `rgpd_activate_${cat}`}).length === 0){
              dataLayer.push({
                event: `rgpd_activate_${cat}`,
              });
            }
          }
        })
      }
    });
  }
  cookieConsentConfig(){
    const cookieConsent = document.querySelector('.ch-cookie-consent');
    const cookieConsentForm = document.querySelector('.ch-cookie-consent__form');
    const cookieConsentFormBtn = document.querySelectorAll('.ch-cookie-consent__btn');
    const cookieConsentCategoryDetails = document.querySelector('.ch-cookie-consent__category-group');
    const cookieConsentCategoryDetailsToggle = document.querySelector('.ch-cookie-consent__toggle-details');
    const showCookie = ()=> {
      this.element.classList.remove('d-none');
      setTimeout(() => {
        this.element.classList.remove('hidden');
      }, 100);

    }
    const hideCookie= ()=> {
      this.element.classList.add('hidden');
      setTimeout(() => {
        this.element.classList.add('d-none');
      }, 300);
    }
    if (cookieConsentForm) {
      // Submit form via ajax
      for (var i = 0; i < cookieConsentFormBtn.length; i++) {
        var btn = cookieConsentFormBtn[i];
        btn.addEventListener('click', function (event) {
          event.preventDefault();

          var formAction = cookieConsentForm.action ? cookieConsentForm.action : location.href;
          var xhr = new XMLHttpRequest();

          xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
              hideCookie();

              const data = new FormData(cookieConsentForm);

              const value = Object.fromEntries(data.entries());
              value[event.target.name] = 'true';

              var buttonEvent = new CustomEvent('cookie-consent-form-submit-successful', {
                detail: JSON.stringify(value),
              });
              document.dispatchEvent(buttonEvent);
            }
          };
          xhr.open('POST', formAction);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.send(serializeForm(cookieConsentForm, event.target));

          // Clear all styles from body to prevent the white margin at the end of the page
          document.body.style.marginBottom = null;
          document.body.style.marginTop = null;
        }, false);
      }
    }
    if (cookieConsentCategoryDetails && cookieConsentCategoryDetailsToggle) {
      cookieConsentCategoryDetailsToggle.addEventListener('click', function () {
        const detailsIsHidden = cookieConsentCategoryDetails.classList.contains('hidden');
        if(detailsIsHidden) {
          cookieConsentCategoryDetails.classList.remove('hidden')
        }else{
          cookieConsentCategoryDetails.classList.add('hidden')
        }
        cookieConsentCategoryDetailsToggle.querySelector('.ch-cookie-consent__toggle-details-hide').classList.toggle('d-none');
        cookieConsentCategoryDetailsToggle.querySelector('.ch-cookie-consent__toggle-details-show').classList.toggle('d-none');
        document.querySelector('#cookie_consent_use_all_cookies').classList.toggle('hide');
        document.querySelector('#cookie_consent_save').classList.toggle('hide');
      });
    }
    function serializeForm (form, clickedButton) {
      var serialized = [];

      for (var i = 0; i < form.elements.length; i++) {
        var field = form.elements[i];

        if ((field.type !== 'checkbox' && field.type !== 'radio' && field.type !== 'button') ||
          field.checked) {
          serialized.push(encodeURIComponent(field.name) + '=' + encodeURIComponent(field.value));
        }
      }

      serialized.push(encodeURIComponent(clickedButton.getAttribute('name')) + '=');

      return serialized.join('&');
    }
    if (typeof window.CustomEvent !== 'function') {
      function CustomEvent (event, params) {
        params = params || { bubbles: false, cancelable: false, detail: undefined };
        var evt = document.createEvent('CustomEvent');
        evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
        return evt;
      }

      CustomEvent.prototype = window.Event.prototype;

      window.CustomEvent = CustomEvent;
    }
    if (this.showValue) {
      setTimeout(() => {
        showCookie();
      }, 750);
    }
    document.querySelector('#cookie-toggler').addEventListener('click', () => {
      this.element.classList.contains('d-none') ? showCookie() : hideCookie();
    });
  }
  connect () {
    this.consentEventHandler();
    this.cookieConsentConfig();
  }
  allowAll(){
    document.querySelectorAll('.ch-cookie-consent__category-toggle input[value=true]').forEach((el)=>{
      el.checked = true;
    });
    document.querySelectorAll('.ch-cookie-consent__category-toggle input[value=false]').forEach((el)=>{
      el.checked = false;
    });

  }
  disallowAll(){
    document.querySelectorAll('.ch-cookie-consent__category-toggle input[value=true]').forEach((el)=>{
      el.checked = false;
    });
    document.querySelectorAll('.ch-cookie-consent__category-toggle input[value=false]').forEach((el)=>{
      el.checked = true;
    });
  }
}