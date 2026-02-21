<div class="location_feed">

	<section class="location_feed_list columns">

	<?php if($areas->count()) : ?>
		<?php foreach($areas as $area): ?>


	  <article class="location_feed_article">

	    <div class="location_feed_list_image">
	    <?php if($image = $area->coverimage()->toFile()):
	      $thumb = $image->thumb('preview');
	    ?>
		<a href="<?= $area->url() ?>"><img src="<?= $thumb->url() ?>" alt="<?= $thumb->alt() ?>"></a>
	    <?php endif ?>
		</div>

	  	<div class="location_feed_list_info">
			<h3><a href="<?= $area->url() ?>"><?= $area->title()->html() ?></a></h3>
		</div>

	  </article>

		<?php endforeach ?>
	<?php endif ?>

	</section>

	<?php if($areas->pagination()->hasPages()): ?>
	<nav class="pagination">

	  <?php if($areas->pagination()->hasNextPage()): ?>
	  <a class="pagination-item-right" href="<?= $areas->pagination()->nextPageURL() ?>">
	    ‹ older posts
	  </a>
	  <?php endif ?>

	  <?php if($areas->pagination()->hasPrevPage()): ?>
	  <a class="pagination-item-left" href="<?= $areas->pagination()->prevPageURL() ?>">
	    newer posts ›
	  </a>
	  <?php endif ?>

	</nav>
	<?php endif ?>


</div>
