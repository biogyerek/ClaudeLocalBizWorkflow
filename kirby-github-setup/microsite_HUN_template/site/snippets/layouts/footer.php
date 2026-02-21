<?php snippet('layouts/footer-cta') ?>

<footer class="" role="contentinfo">

  <div class="footer">

    <div>
      <h3><?php echo t('footerTitleAbout') ?></h3>
      <h4><?= $site->tagline()->kti() ?></h4>
      <p><?= $site->mission()->kti() ?></p>
    </div>

    <div <?php e($site->footerCenterText()->isTrue(), 'class="center"') ?>>

      <?php $menu = $site->quickLinks()->toPages();
      if($menu->isNotEmpty()):
      ?>

      <h3><?php echo t('footerTitleQuickLinks') ?></h3>

      <ul class="footer-menu">
        <?php foreach($menu as $menuItem): ?>
        <li class="footer-menu-item"><a<?php e($menuItem->isOpen(), ' class="active"') ?> href="<?= $menuItem->url() ?>"><?= $menuItem->title()->html() ?></a></li>
        <?php endforeach ?>
      </ul>

      <?php endif ?>


    </div>

    <div <?php e($site->footerCenterText()->isTrue(), 'class="center"') ?>>

      <?php $menu = $site->footerLinks()->toPages();
      if($menu->isNotEmpty()):
      ?>

      <h3><?php echo t('footerTitleLinks') ?></h3>

      <ul class="footer-menu">
        <?php foreach($menu as $menuItem): ?>
        <li class="footer-menu-item"><a<?php e($menuItem->isOpen(), ' class="active"') ?> href="<?= $menuItem->url() ?>"><?= $menuItem->title()->html() ?></a></li>
        <?php endforeach ?>
      </ul>

      <?php endif ?>


    </div>

    <div <?php e($site->footerCenterText()->isTrue(), 'class="center"') ?>>
      <h3><?php echo t('footerTitleContact') ?></h3>
      <h4><?= $site->title()->kti() ?></h4>
      <p class="footer-address"><?= $site->address()->kti() ?></p>
<!--         <p class="footer-directions"><a href="<?= $site->directions()->html() ?>">Get Directions</p>
      <p class="footer-address"><?= $site->phone()->kti() ?></p> -->

    </div>

  <?php if ($site->footerShowSocials()->toBool() === true): ?>

    <div>
      <h3><?php echo t('footerTitleFollow') ?></h3>
      <?php snippet('layouts/facebook-like') ?>
    </div>

  <?php endif ?>

  </div>

  <div class="copyright">
    <p>© <?= date("Y"); ?> <?= $site->title()->kti() ?> 
      <?php if($site->footerCopyright()->IsNotEmpty()) : ?>
      <?= $site->footerCopyright()->kti() ?>
      <?php endif ?></p>
  </div>

</footer>

<?php snippet('cookie-consent') ?>
<?php echo snippet('matomo-proxy') ?>

<script src="<?= url('assets/js/form-handler.js') ?>"></script>
