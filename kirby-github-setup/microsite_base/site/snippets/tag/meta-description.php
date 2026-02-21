
<?php if($page->template() == 'service') : ?>
	<meta name="description" content="">
<?php elseif($page->template() == 'location') : ?>
	<meta name="description" content="<?= Str::template(html::decode($page->parent()->intro()->markdown()->short(360)), [
	    'location' => $page->title()
	]) ?>">
<?php else : ?>
	<meta name="description" content="<?= html::decode($page->intro()->markdown()->short(360)) ?>">
<?php endif ?>
