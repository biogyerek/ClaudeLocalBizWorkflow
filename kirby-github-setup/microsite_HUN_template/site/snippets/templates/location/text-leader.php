<div class="container grid">

  <div class="prose">

    <?= Str::template($template->intro()->kt(), [
        'location' => $page->title()
    ]) ?>


    <?= Str::template($template->answer()->kt(), [
        'location' => $page->title()
    ]) ?>


    <?php
    if ($template->showSummary()->toBool() === true): ?>
    <?php snippet('templates/location/summary', ['page' => $template]) ?>
    <?php endif ?>
    <?php
    if ($template->toc()->toBool() === true): ?>
    <?php snippet('templates/location/toc', ['page' => $template]) ?>
    <?php endif ?>

    <?= Str::template($template->text()->kt()->anchorHeadlines(), [
        'location' => $page->title()
    ]) ?>

  </div>

  <?php snippet('layouts/sidebar') ?>

</div>