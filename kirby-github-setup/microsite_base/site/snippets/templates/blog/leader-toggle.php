<?php
if ($page->showLeader()->toBool() === true): ?>

<?php snippet('templates/blog/leader') ?>

<div class="container">
<?php snippet('templates/blog/text-leader') ?>
</div>

<?php else : ?>

<div class="container">
<?php snippet('templates/blog/text') ?>
</div>

<?php endif ?>