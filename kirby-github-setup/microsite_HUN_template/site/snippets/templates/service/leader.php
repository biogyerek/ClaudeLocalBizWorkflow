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

			<?= Str::template($page->leader()->kt(), [
              'service' => $page->title() ]) ?>
			<?php snippet('layouts/cta/leader', ['template' => $page]) ?>


          </div>
	</div>

</section>