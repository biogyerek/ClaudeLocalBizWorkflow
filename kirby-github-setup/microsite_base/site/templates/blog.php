<!DOCTYPE html>
<html lang="<?= $site->lang() ?>">
<head>
  <?php snippet('layouts/head') ?>
  <?= Bnomei\Fingerprint::css('assets/css/layouts/blog.css') ?>
</head>
<body>

  <?php snippet('layouts/header') ?>

  <?php snippet('templates/blog/leader-toggle') ?>
  <?php snippet('templates/blog/content') ?>
  <?php snippet('templates/blog/articles') ?>
  <?php snippet('templates/service/contact') ?>

  <?php snippet('seo/schemas'); ?>

</body>

<?php snippet('layouts/footer') ?>

</html>