<div class="branding">
	
  <a href="<?= $site->url() ?>" rel="home">

  <?php if($logo = $site->logo()->toFile()): ?>
    <img src="<?= $logo->url() ?>" class="logo" alt="<?= $site->title()->html()?> Logo" aria-label="Go to homepage" />
  <?php else : ?>
    <img src="<?= $kirby->url() ?>/assets/images/logo-white.png" class="logo" alt="<?= $site->title()->html()?> Logo" aria-label="Go to homepage" />
  <?php endif ?>
  </a>

</div>