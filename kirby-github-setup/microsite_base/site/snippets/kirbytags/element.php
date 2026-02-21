<?php if($class == 'locations' && $elementLocations->count()): ?>

<aside class="element">
	<div class="service-locations">

		<?php if($headline !== null): ?>
		<h2><?= $headline ?></h2>

		<?php elseif($page->intendedTemplate() == 'locations'):?>
		<h2><?php echo t('elementLocationsTitle') ?></h2>

		<?php elseif($page->intendedTemplate() == 'service'):?>
		<h2><?php echo t('elementLocationsTitle') ?></h2>

		<?php elseif($page->intendedTemplate() == 'location'):?>
		<h2><?php echo t('elementLocationsTitle') ?></h2>

		<?php elseif($page->intendedTemplate() == 'sublocation'):?>
		<h2><?php echo t('elementLocationsTitle') ?></h2>
		<?php endif ?>

		<?php if($text !== null): ?>
		<?= $text->kt() ?>
		<?php endif ?>

		<section class="service-locations-list">

			<?php foreach($elementLocations as $location): ?>		

		  	<div class="service-location">
				<h4><a href="<?= $location->slug() ?>"><?= $location->title()->html() ?></a></h4>
			</div>

			<?php endforeach ?>

		</section>

<!-- 		<?php if($site->map()->IsNotEmpty()): ?>
		<div class="contact-map">
			<div class="responsive-iframe">
				<?= $site->map() ?>
			</div>
		</div>
		<?php endif ?> -->

	</div>
</aside>



<?php elseif($class == 'sublocations' && $elementSublocations->count() && $page->intendedTemplate() == 'location'): ?>

<aside class="element">
	<div class="service-locations">

		<?php if($headline !== null): ?>
		<h2><?= $headline ?></h2>

		<?php elseif($page->intendedTemplate() == 'location'):?>
		<h2><?php echo t('elementLocationTitle') ?></h2>

		<?php elseif($page->intendedTemplate() == 'sublocation'):?>
		<h2><?php echo t('elementSublocationTitle') ?></h2>
		<?php endif ?>

		<?php if($text !== null): ?>
		<?= $text->kt() ?>
		<?php endif ?>

		<section class="service-locations-list">

			<?php foreach($elementSublocations as $location): ?>		

		  	<div class="service-location">
				<h4><a href="<?= $location->slug() ?>"><?= $location->title()->html() ?></a></h4>
			</div>

			<?php endforeach ?>

		</section>

<!-- 		<?php if($site->map()->IsNotEmpty()): ?>
		<div class="contact-map">
			<div class="responsive-iframe">
				<?= $site->map() ?>
			</div>
		</div>
		<?php endif ?> -->

	</div>
</aside>




<?php elseif($class == 'sublocations' && $elementSublocations->count() && $page->intendedTemplate() == 'sublocation'): ?>

<aside class="element">
	<div class="service-locations">

		<?php if($headline !== null): ?>
		<h2><?= $headline ?></h2>
		<?php else: ?>
		<h2><?php echo t('elementSublocationTitle') ?></h2>
		<?php endif ?>

		<?php if($text !== null): ?>
		<span><?= $text ?></span>
		<?php endif ?>

		<section class="service-locations-list">

			<?php foreach($elementSublocations as $location): ?>		

		  	<div class="service-location">
				<h4><a href="<?= $location->slug() ?>"><?= $location->title()->html() ?></a></h4>
			</div>

			<?php endforeach ?>

		</section>

<!-- 		<?php if($site->map()->IsNotEmpty()): ?>
		<div class="contact-map">
			<div class="responsive-iframe">
				<?= $site->map() ?>
			</div>
		</div>
		<?php endif ?> -->

	</div>
</aside>


<?php elseif($class == 'services'): ?>

<aside class="element">
	<div class="services">

		<div>

			<?php if($headline !== null): ?>
			<h2><?= $headline ?></h2>
			<?php else: ?>
			<h2><?php echo t('elementServicesTitle') ?></h2>
			<?php endif ?>

			<?php if($text !== null): ?>
			<span><?= $text ?></span>
			<?php endif ?>

			<?php snippet('layouts/cta/call') ?>
		</div>

		<section class="services-list">

		<?php if($services->count()) : ?>
			<?php foreach($services as $service): ?>		

		  	<div class="service">
				<h4><a href="<?= $service->url() ?>"><?= $service->title()->html() ?></a></h4>
			</div>

			<?php endforeach ?>
		<?php endif ?>

		</section>

	</div>
</aside>

<?php endif ?>