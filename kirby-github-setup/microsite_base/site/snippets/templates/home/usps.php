<?php
if ($page->showUSPs()->toBool() === true): ?>
<section class="usp-container">

	<div class="container">
		<?php if($page->titleUSPs()->IsNotEmpty()): ?>
		<h2><?= $page->titleUSPs()->kti() ?></h2>
		<?php else: ?>
		<h2><?php echo t('titleUSPs') ?></h2>
		<?php endif ?>

		<div class="usps">

		<?php 
		$items = $page->usps()->toStructure();
		foreach($items as $usp): ?>

			<div class="usp-box">
			<?php if($image = $usp->icon()->toFile()): 
				$thumb = $image->thumb('icon') ?>
				<img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>" />
			<?php endif?>
				<h3 class="usp-heading"><?= $usp->title()->kti() ?></h3>
				<span class="usp-description"><?= $usp->text()->kti() ?></span>
			</div>

		<?php endforeach ?>

		</div>
		
	<?php if($aboutPage = $page->contactPage()->toPage()) : ?>
		<div class="usp-button">
			<a href="<?= $aboutPage->url() ?>" class="btn btn--filled"><?php echo t('aboutUs') ?></a>
		</div>
	<?php endif ?>

	</div>

</section>
<?php endif ?>