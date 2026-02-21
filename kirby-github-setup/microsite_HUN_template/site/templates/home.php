<!DOCTYPE html>
<html lang="<?= $site->lang() ?>">
<head>
  <?php snippet('layouts/head') ?>
  <?= Bnomei\Fingerprint::css('assets/css/layouts/home.css') ?>
</head>
<body>

  <?php snippet('layouts/header') ?>

  <?php snippet('templates/home/leader') ?>
  <?php snippet('templates/home/text') ?>
  <?php snippet('templates/home/services') ?>
  <?php snippet('templates/home/usps') ?>
  <?php snippet('templates/home/faq') ?>
  <?php snippet('templates/home/contact') ?>
  
  <?php snippet('seo/schemas'); ?>

</body>

<?php snippet('layouts/footer') ?>

</html>