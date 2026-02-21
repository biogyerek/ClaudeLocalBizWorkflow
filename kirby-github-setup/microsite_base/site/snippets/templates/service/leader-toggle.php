<?php
if ($page->showLeader()->toBool() === true): ?>

<?php snippet('templates/service/leader', ['template' => $page]) ?>

<?php else : ?>

<div class="container">
<?php snippet('templates/service/text', ['template' => $page]) ?>
</div>

<?php endif ?>