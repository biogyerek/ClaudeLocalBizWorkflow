<!-- Cookie Consent Banner -->
<div id="cookieConsent" class="cookie-consent" style="display:none;">
  <div class="cookie-consent-overlay"></div>
  <div class="cookie-consent-modal">
    <h2><?php echo t('cookieTitle', 'Süti beállítások') ?></h2>
    <p><?php echo t('cookieText', 'Sütiket használunk a jobb felhasználói élmény biztosítása érdekében. A sütik segítenek nekünk megérteni, hogyan használja weboldalunkat, és lehetővé teszik, hogy személyre szabott tartalmat nyújtsunk Önnek.') ?></p>
    <div class="cookie-buttons">
      <button id="acceptAll"><?php echo t('cookieAcceptAll', 'Összes elfogadása') ?></button>
      <button id="rejectAll"><?php echo t('cookieReject', 'Csak szükséges') ?></button>
    </div>
  </div>
</div>

<!-- Cookie Settings Icon -->
<button id="cookieSettingsIcon" class="cookie-settings-icon" title="<?php echo t('cookieSettings', 'Cookie beállítások') ?>">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24" fill="currentColor">
    <path d="M257.5 27.6c-22.8 0-41.5 18.7-41.5 41.5c0 6.9 1.7 13.4 4.7 19.1L81.9 216.8c-5.7-3-12.2-4.7-19.1-4.7c-22.8 0-41.5 18.7-41.5 41.5s18.7 41.5 41.5 41.5c6.9 0 13.4-1.7 19.1-4.7L220.7 419.2c-3 5.7-4.7 12.2-4.7 19.1c0 22.8 18.7 41.5 41.5 41.5s41.5-18.7 41.5-41.5c0-6.9-1.7-13.4-4.7-19.1L433.1 290.4c5.7 3 12.2 4.7 19.1 4.7c22.8 0 41.5-18.7 41.5-41.5s-18.7-41.5-41.5-41.5c-6.9 0-13.4 1.7-19.1 4.7L294.3 87.9c3-5.7 4.7-12.2 4.7-19.1c0-22.8-18.7-41.5-41.5-41.5zm0 32.8c4.8 0 8.7 3.9 8.7 8.7s-3.9 8.7-8.7 8.7s-8.7-3.9-8.7-8.7s3.9-8.7 8.7-8.7zM123.2 243.6c-5.4 3.4-11.9 5.4-18.9 5.4c-19.6 0-35.5-15.9-35.5-35.5s15.9-35.5 35.5-35.5c7 0 13.5 2 18.9 5.4l-0.2 0.3 0.2-0.3zm79.2 0.3c-3.4-5.4-5.4-11.9-5.4-18.9c0-19.6 15.9-35.5 35.5-35.5s35.5 15.9 35.5 35.5c0 7-2 13.5-5.4 18.9l-0.3-0.2 0.3 0.2zm79.2 0c-5.4-3.4-11.9-5.4-18.9-5.4c-19.6 0-35.5 15.9-35.5 35.5s15.9 35.5 35.5 35.5c7 0 13.5-2 18.9-5.4l-0.2-0.3 0.2 0.3zm146.8 9.2c4.8 0 8.7 3.9 8.7 8.7s-3.9 8.7-8.7 8.7s-8.7-3.9-8.7-8.7s3.9-8.7 8.7-8.7zM257.5 451.6c4.8 0 8.7 3.9 8.7 8.7s-3.9 8.7-8.7 8.7s-8.7-3.9-8.7-8.7s3.9-8.7 8.7-8.7z"/>
  </svg>
</button>

<style>
/* Cookie Consent Modal */
.cookie-consent {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 99999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.cookie-consent-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  animation: fadeIn 0.3s ease;
}

.cookie-consent-modal {
  position: relative;
  background: white;
  border-radius: 12px;
  padding: 2rem;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
  animation: slideUp 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.cookie-consent-modal h2 {
  margin: 0 0 1rem 0;
  font-size: 1.5rem;
  color: #333;
}

.cookie-consent-modal p {
  margin: 0 0 1.5rem 0;
  color: #666;
  line-height: 1.6;
  font-size: 0.938rem;
}

.cookie-buttons {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.cookie-buttons button {
  width: 100%;
  padding: 0.875rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

#acceptAll {
  background: #28a745;
  color: white;
}

#acceptAll:hover {
  background: #218838;
}

#rejectAll {
  background: #e9ecef;
  color: #333;
}

#rejectAll:hover {
  background: #dee2e6;
}

/* Cookie Settings Icon */
.cookie-settings-icon {
  position: fixed;
  bottom: 20px;
  left: 20px;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: var(--color-accent, #007bff);
  color: white;
  border: none;
  cursor: pointer;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
}

.cookie-settings-icon:hover {
  background: var(--color-accent-dark, #0056b3);
  transform: scale(1.1);
}

.cookie-settings-icon svg {
  width: 24px;
  height: 24px;
}

@media (max-width: 768px) {
  .cookie-consent-modal {
    padding: 1.5rem;
    margin: 1rem;
  }

  .cookie-consent-modal h2 {
    font-size: 1.25rem;
  }

  .cookie-consent-modal p {
    font-size: 0.875rem;
  }

  .cookie-buttons button {
    font-size: 0.938rem;
    padding: 0.75rem 1rem;
  }

  .cookie-settings-icon {
    width: 45px;
    height: 45px;
    bottom: 15px;
    left: 15px;
  }
}
</style>

<script>
// Google Consent Mode v2 - Default state (denied)
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}

gtag('consent', 'default', {
  'ad_storage': 'denied',
  'ad_user_data': 'denied',
  'ad_personalization': 'denied',
  'analytics_storage': 'denied',
  'functionality_storage': 'granted',
  'personalization_storage': 'denied',
  'security_storage': 'granted'
});

// Cookie Consent Logic
(function() {
  const COOKIE_NAME = 'cookie_consent';
  const COOKIE_DURATION = 365; // days

  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return JSON.parse(decodeURIComponent(parts.pop().split(';').shift()));
    return null;
  }

  function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = `expires=${date.toUTCString()}`;
    document.cookie = `${name}=${encodeURIComponent(JSON.stringify(value))};${expires};path=/;SameSite=Lax`;
  }

  function updateConsent(analytics, marketing) {
    // Update Google Consent Mode v2
    gtag('consent', 'update', {
      'ad_storage': marketing ? 'granted' : 'denied',
      'ad_user_data': marketing ? 'granted' : 'denied',
      'ad_personalization': marketing ? 'granted' : 'denied',
      'analytics_storage': analytics ? 'granted' : 'denied'
    });

    // Update Facebook Pixel consent
    if (typeof fbq !== 'undefined') {
      if (marketing) {
        fbq('consent', 'grant');
      } else {
        fbq('consent', 'revoke');
      }
    }
  }

  function showBanner() {
    document.getElementById('cookieConsent').style.display = 'block';
  }

  function hideBanner() {
    document.getElementById('cookieConsent').style.display = 'none';
  }

  function saveConsent(analytics, marketing) {
    const consent = {
      necessary: true,
      analytics: analytics,
      marketing: marketing,
      timestamp: new Date().toISOString()
    };

    setCookie(COOKIE_NAME, consent, COOKIE_DURATION);
    updateConsent(analytics, marketing);
    hideBanner();
  }

  // Check existing consent
  const existingConsent = getCookie(COOKIE_NAME);

  if (existingConsent) {
    // Apply existing consent
    updateConsent(existingConsent.analytics, existingConsent.marketing);
  } else {
    // Show banner for new visitors
    showBanner();
  }

  // Event listeners
  document.getElementById('acceptAll').addEventListener('click', function() {
    saveConsent(true, true);
  });

  document.getElementById('rejectAll').addEventListener('click', function() {
    saveConsent(false, false);
  });

  document.getElementById('cookieSettingsIcon').addEventListener('click', function() {
    showBanner();
  });
})();
</script>
