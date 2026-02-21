<!DOCTYPE html>
<html lang="<?= $site->lang() ?>">
<head>
  <?php snippet('layouts/head') ?>
  <?= Bnomei\Fingerprint::css('assets/css/layouts/locations.css') ?>
</head>
<body>

  <?php snippet('layouts/header') ?>

  <?php
  if ($page->showlocationsleader()->toBool() === true): ?>
  <?php snippet('templates/locations/leader') ?>
  <?php snippet('templates/locations/text-leader') ?>
  <?php else : ?>
  <?php snippet('templates/locations/text') ?>
  <?php endif ?>
  <?php snippet('templates/locations/locations') ?>
  <?php snippet('templates/locations/content') ?>
  <?php snippet('templates/home/contact') ?>
  <?php snippet('seo/schemas'); ?>

</body>

<?php snippet('layouts/footer') ?>

</html>