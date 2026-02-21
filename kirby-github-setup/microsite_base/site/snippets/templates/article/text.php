<div class="prose">

	<h1><?= $page->title() ?></h1>
	<?php if($page->date()->isNotEmpty()): ?>
	<time class="article-meta" datetime="<?= $page->date()->toDate('Y-m-d') ?>">
		<?= $page->date()->toDate('Y. m. d.') ?>
	</time>
	<?php endif ?>
	<?php snippet('tag/kt-variables') ?>

</div>

<?php snippet('layouts/sidebar') ?>