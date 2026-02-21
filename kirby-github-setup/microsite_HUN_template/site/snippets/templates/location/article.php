<h1><?= Str::template($template->headline()->kti(), [
    'location' => $page->title()
]) ?></h1>


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