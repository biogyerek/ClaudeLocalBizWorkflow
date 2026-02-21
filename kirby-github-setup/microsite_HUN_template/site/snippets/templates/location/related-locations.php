<div class="service-locations container">

	<h2><?php echo t('titleLocationsRelated') ?></h2>

	<section class="service-locations-list columns">

	<?php if($sublocations->count()) : ?>
		<?php foreach($sublocations as $sublocation): ?>

	  	<div class="service-location">
			<h3>
				<a href="<?= $sublocation->url() ?>"><?= $sublocation->title()->html() ?></a>
			</h3>
		</div>

		<?php endforeach ?>
	<?php endif ?>

	</section>

</div>