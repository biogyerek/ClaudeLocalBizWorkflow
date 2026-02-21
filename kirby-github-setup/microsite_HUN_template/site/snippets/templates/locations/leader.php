<section class="leader">

	<figure>

	    <?php if($image = $page->locationscoverimage()->toFile()):
	    $thumb = $image->thumb('cover') ?>

	    <img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>" />

	    <?php elseif($image = $site->coverimage()->toFile()):
	    $thumb = $image->thumb('cover') ?>

	    <img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>" />
		
		<?php endif ?>

	</figure>

	<div class="leader-text-container">
		<div class="leader-text">

		<?php if($page->locationsleader()->IsNotEmpty()): ?>
			<?= $page->locationsleader()->kt() ?>
		<?php else: ?>
			<h1><?= $page->title()->kti() ?></h1>
			<?php snippet('layouts/cta/call') ?>
		<?php endif?>

          </div>
	</div>

</section>