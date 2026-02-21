
<?php if($page->template() == 'location') : ?>
	<title><?= Str::template($page->parent()->headline()->kti(), [
	    'location' => $page->title()
	]) ?> - <?= $site->title() ?></title>
<?php elseif($page->template() == 'sublocation') : ?>
	<title><?= Str::template($page->parent()->parent()->headline()->kti(), [
	    'location' => $page->title()
	]) ?> - <?= $site->title() ?></title>
<?php else: ?>
<?php endif ?>

<?php if($page->template() == 'home') : ?>
	<link rel="canonical" href="<?= $page->url() ?>" />
<?php else: ?>
	<link rel="canonical" href="<?= $site->url() ?>/<?= $page->slug() ?>" />
<?php endif ?>