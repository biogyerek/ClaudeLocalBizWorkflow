<!DOCTYPE html>
<html lang="<?= $site->lang() ?>">
<head>
  <?php snippet('layouts/head') ?>
  <?= Bnomei\Fingerprint::css('assets/css/layouts/service.css') ?>
</head>
<body>

  <?php snippet('layouts/header') ?>
  
  <?php snippet('templates/service/leader-toggle') ?>
  <!-- <?php snippet('templates/service/intro') ?> -->
  <?php snippet('templates/service/content') ?>
  <?php snippet('templates/service/locations') ?>
  <?php snippet('templates/service/contact') ?>

  <?php snippet('seo/schemas'); ?>

</body>

<?php snippet('layouts/footer') ?>

</html>