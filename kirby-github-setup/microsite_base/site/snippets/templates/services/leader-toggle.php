<?php
if ($page->showLeader()->toBool() === true): ?>

<?php snippet('templates/services/leader') ?>

<div class="container">
<?php snippet('templates/services/text-leader') ?>
</div>

<?php else : ?>

<div class="container">
<?php snippet('templates/services/text') ?>
</div>

<?php endif ?>