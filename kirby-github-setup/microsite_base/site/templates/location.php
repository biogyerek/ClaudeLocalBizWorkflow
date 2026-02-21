<!DOCTYPE html>
<html lang="<?= $site->lang() ?>">
<head>
  <?php snippet('layouts/head') ?>
  <?= Bnomei\Fingerprint::css('assets/css/layouts/location.css') ?>
</head>
<body>

  <?php snippet('layouts/header') ?>

  <?php
  // Check if location page has showLeader field (individual content)
  // Otherwise fallback to parent's showTemplateLeader (template content)
  $showLeader = $template->showLeader()->isNotEmpty()
    ? $template->showLeader()->toBool()
    : ($parentTemplate->showTemplateLeader()->toBool() ?? true);

  if ($showLeader === true): ?>

  <?php snippet('templates/location/leader', ['template' => $template]) ?>
  <?php snippet('templates/location/text-leader') ?>
  <?php else : ?>
  <?php snippet('templates/location/text') ?>
  <?php endif ?>
  <?php snippet('templates/location/sublocations', ['sublocations' => $sublocations]) ?>
  <?php snippet('templates/home/contact') ?>
  <?php snippet('seo/schemas'); ?>

</body>

<?php snippet('layouts/footer') ?>

</html>