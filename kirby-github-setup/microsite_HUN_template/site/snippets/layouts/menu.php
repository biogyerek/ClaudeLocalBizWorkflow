<?php
$menu = $site->menu()->toStructure();
if ($menu->isNotEmpty()) : ?>

<nav class="menu" id="menu" role="navigation">

  <ul>

    <?php foreach ($menu as $item): ?>

      <?php if ($mainMenuItem = $item->mainMenu()->toPage()) : ?>

      <li class="menu-item"><a <?php e($mainMenuItem->isOpen() && $item->hasSubmenu()->isTrue(), 'class="active hasSubmenu"') ?><?php e($item->isButton()->isTrue(), 'class="btn btn--menu"') ?><?php e($mainMenuItem->isOpen(), 'class="active"') ?> <?php e($item->hasSubmenu()->isTrue(), 'class="hasSubmenu"') ?> class="" href="<?= $mainMenuItem->url() ?>"><?= $mainMenuItem->title() ?></a>

      <?php endif ?>


      <?php $subMenu = $item->subMenu()->toPages(); ?>

      <?php if ($item->hasSubmenu()->isTrue() && $subMenu->isNotEmpty()) : ?>

        <ul class="submenu">

          <?php foreach ($subMenu as $subItem) : ?>

          <li class="submenu-item"><a href="<?= $subItem->url() ?>"><?= $subItem->title() ?></a></li>

          <?php endforeach ?>
        </ul>
      </li>
      <?php endif ?>

      <?php if ($phoneNumber = $item->isCall()->isTrue() && $site->phone()->isNotEmpty()) : ?>

      <li class="menu-item"><a class="call-cta" href="tel:<?= Html::decode($site->phone()) ?>"><?php echo t('callCTA') ?> <?= Html::decode($site->phone()) ?></a></li>
      <?php endif ?>

    <?php endforeach ?>

  </ul>

</nav>


<?php elseif ($menu->isEmpty()) : ?>

<?php $items = $site->children()->listed();
$mainMenuItems = $items->notTemplate(['service', 'services', 'estimate']);
$servicesPages = $items->template('services');
$servicePages = $items->template('service');
$estimatePages = $items->template('estimate');
if($mainMenuItems->isNotEmpty()):
?>

<nav class="menu" id="menu" role="navigation">

  <ul>

    <?php foreach($servicesPages as $servicePage): ?>

      <li class="menu-item">
        <a class="hasSubmenu" href="<?= $servicePage->url() ?>"><?= $servicePage->title() ?></a>


          <ul class="submenu">

            <?php foreach ($servicePages as $subItem) : ?>

            <li class="submenu-item"><a href="<?= $subItem->url() ?>"><?= $subItem->title() ?></a></li>

            <?php endforeach ?>
          </ul>
        </li>

    <?php endforeach ?>

    <?php foreach($mainMenuItems as $mainMenuItem): ?>

      <li class="menu-item">
        <a class="" href="<?= $mainMenuItem->url() ?>"><?= $mainMenuItem->title() ?></a>
      </li>

    <?php endforeach ?>

    <?php foreach($estimatePages as $estimatePage): ?>

      <li class="menu-item">
        <a class="btn btn--menu" href="<?= $estimatePage->url() ?>"><?= $estimatePage->title() ?></a>
      </li>

    <?php endforeach ?>

    <?php if ($site->phone()->isNotEmpty()) : ?>

    <li class="menu-item"><a class="call-cta" href="tel:<?= Html::decode($site->phone()) ?>"><?php echo t('callCTA') ?> <?= Html::decode($site->phone()) ?></a></li>
    <?php endif ?>

  </ul>

</nav>

<?php endif ?>

<?php endif ?>

<nav class="menuToggle">

  <button onclick="burger()" class="hamburger hamburger--slider" id="hamburger" type="button" aria-label="Toggle navigation menu">

    <span class="hamburger-box">
      <span class="hamburger-inner"></span>
    </span>
      
  </button>

</nav>

<script>
function burger() {
  var element = document.getElementById("menu");
  element.classList.toggle("show");
  var element = document.getElementById("hamburger");
  element.classList.toggle("is-active");
}
</script>