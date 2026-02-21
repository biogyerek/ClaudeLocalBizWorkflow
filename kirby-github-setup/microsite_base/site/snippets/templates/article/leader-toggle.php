<?php
if ($page->showLeader()->toBool() === true): ?>

<?php snippet('templates/article/leader') ?>

<div class="container">
<?php snippet('templates/article/text-leader') ?>
</div>

<?php else : ?>

<div class="container">
<?php snippet('templates/article/text') ?>
</div>

<?php endif ?>