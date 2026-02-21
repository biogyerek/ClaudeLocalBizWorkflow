
<?php if($page->template() == 'location') : ?>
	<title><?= Str::template($page->parent()->headline()->kti(), [
	    'location' => $page->title()
	]) ?> - <?= $site->site_name() ?></title>
<?php elseif($site->site_name()->IsNotEmpty()) : ?>
	<title><?= $page->title()->html() ?> - <?= $site->site_name() ?></title>
<?php else: ?>
	<title><?= $page->title()->html() ?> - <?= $site->title() ?></title>
<?php endif ?>
	<link rel="canonical" href="<?= $page->url() ?>" />
