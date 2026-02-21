<?php if($site->googleAnalytics()->isNotEmpty()): ?>
<!-- Google tag (gtag.js) with Consent Mode v2 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $site->googleAnalytics() ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}

  // Initialize Google Analytics
  gtag('js', new Date());
  gtag('config', '<?= $site->googleAnalytics() ?>', {
    'anonymize_ip': true,
    'cookie_flags': 'SameSite=None;Secure'
  });
</script>
<?php endif ?>
