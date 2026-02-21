<!DOCTYPE html>
<html lang="<?= $site->lang() ?>">
<head>
  <?php snippet('layouts/head') ?>
  <?= Bnomei\Fingerprint::css('assets/css/layouts/article.css') ?>
</head>
<body>

  <?php snippet('layouts/header') ?>

  <?php snippet('templates/article/leader-toggle') ?>
  <?php snippet('templates/article/content') ?>
  <?php snippet('templates/service/contact') ?>

  <?php snippet('seo/schemas'); ?>

</body>

<?php snippet('layouts/footer') ?>

</html>