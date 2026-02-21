<section class="leader">

	<figure>

	    <?php if($image = $page->coverimage()->toFile()):
	    $thumb = $image->thumb('cover') ?>

	    <img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>" />

	    <?php elseif($image = $site->coverimage()->toFile()):
	    $thumb = $image->thumb('cover') ?>

	    <img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>" />
		
		<?php endif ?>

	</figure>

	<div class="leader-text-container">
		<div class="leader-text">
			
		<?php if($page->leader()->IsNotEmpty()) : ?>
			<?= $page->leader()->kt() ?>
		<?php else : ?>
			<h1><?= $page->title()->kti() ?></h1>
		<?php endif ?>
				
		</div>
	</div>

</section>