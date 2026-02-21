<?php if($sublocations->count()) : ?>
<div class="service-locations container">

	<h2><?php echo t('titleLocations') ?></h2>

	<section class="service-locations-list columns">

	<?php foreach($sublocations as $sublocation): ?>

	  	<div class="service-location">
			<h3>
				<a href="<?= $sublocation->url() ?>"><?= $sublocation->title()->html() ?></a>
			</h3>

		</div>

	<?php endforeach ?>

	</section>

</div>
<?php endif ?>
