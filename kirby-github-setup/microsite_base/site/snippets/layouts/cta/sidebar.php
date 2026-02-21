<div class="image">

	<?php if($image = $page->sidebarImage()->toFile()):
	$thumb = $image->thumb('sidebar') ?>

	<figure>

		<img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>" />
		
		<?php if ($image->caption()->isNotEmpty()) : ?>
		<figcaption><?= $image->caption()->kt() ?></figcaption>
		<?php endif ?>
		
	</figure>

	<?php endif ?>

</div>

<div class="sidebar-offer">
	<?= $page->sidebar()->kt() ?>
	<?php snippet('layouts/cta/call') ?>
	<div class="cta-subtext"><span><?php echo t('offerSubtext') ?></span></div>
</div>