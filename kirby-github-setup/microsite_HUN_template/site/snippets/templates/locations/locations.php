<?php if(!isset($sublocations) || $sublocations->count() == 0) : ?>
<div class="container">

	<h2><?php echo t('directoryLocationsTitle') ?></h2>

	<div class="service-locations">

		<section class="service-locations-list columns">

		<?php if($locations->count()) : ?>
			<?php foreach($locations as $location): ?>

		  	<div class="service-location">
				<?php
				// Try to get location name from CSV mapping
				$displayName = $location->title()->html();

				if (isset($locationNames)) {
					// Extract the actual location name from the slug
					// The slug format is usually fahaz-kivitelezes-budapest-1-kerulet
					$slug = $location->slug();

					// Try to find matching location name in CSV
					foreach ($locationNames as $csvSlug => $csvName) {
						if (strpos($slug, $csvSlug) !== false) {
							$displayName = $csvName;
							break;
						}
					}
				}
				?>
				<h3><a href="<?= $location->url() ?>"><?= $displayName ?></a></h3>
			</div>

			<?php endforeach ?>
		<?php endif ?>

		</section>

	</div>

</div>
<?php endif ?>