<?php
if ($page->showFAQ()->toBool() === true): ?>
<section class="faq">

	<div class="container">

		<h4><?php echo t('subtitleFAQ') ?></h4>

		<?php if($page->titleFAQ()->IsNotEmpty()) : ?>
		<h2><?= $page->titleFAQ()->kti() ?></h2>
		<?php else : ?>
		<h2><?php echo t('titleFAQ') ?></h2>
		<?php endif ?>

		<div class="question">

		<?php $items = $page->faq()->toStructure();
		foreach ($items as $item): ?>
			<details <?php e($item->toggle()->bool(), 'open') ?>>
				<?php if ($item->question()->isNotEmpty()): ?>
				<summary><h3><?= $item->question() ?></h3></summary>
				<?php endif ?>
				<div class="content"><p><?= $item->answer()->text()->kti() ?></p></div>
			</details>
		<?php endforeach ?>

		</div>
	</div>

</section>
<?php endif ?>