<div class="prose">



</div>

<div class="image">

	<?php if($image = $page->directoryCoverimage()->toFile()):
	$thumb = $image->thumb('cover') ?>

	<figure>

		<img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>" />
		
		<?php if ($image->caption()->isNotEmpty()) : ?>
		<figcaption><?= $image->caption()->kt() ?></figcaption>
		<?php endif ?>
		
	</figure>

	<?php endif ?>

</div>
