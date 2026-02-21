  <div class="container">
    <h2><?php echo t('directoryLocationsTitle') ?></h2>
		<div class="service-locations">

			<section class="service-locations-list columns">

			<?php if($locations->count()) : ?>
				<?php foreach($locations as $location): ?>		

			  	<div class="service-location">
						<h3><a href="<?= $location->slug() ?>"><?= $location->title()->html() ?></a></h3>
					</div>

				<?php endforeach ?>
			<?php endif ?>

			</section>

		</div>
  </div>