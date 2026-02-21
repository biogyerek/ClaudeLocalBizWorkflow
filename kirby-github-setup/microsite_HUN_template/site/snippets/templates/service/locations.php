  <div class="container">
    <h2><?php echo t('directoryLocationsTitle') ?></h2>
		<div class="service-locations">

			<section class="service-locations-list columns">

			<?php
			// Load locations.csv to get proper display names
			$csvFilePath = kirby()->root('content') . '/locations.csv';
			$csvData = [];
			if (file_exists($csvFilePath)) {
				$csv = csv($csvFilePath, ';');
				foreach ($csv as $row) {
					$mainLocation = $row['MainLocation'];
					$subLocation = $row['SubLocation'] ?? null;
					$slug = Str::slug($mainLocation);

					if ($subLocation) {
						// Sublocation (e.g., Budapest 1. kerület)
						$subSlug = Str::slug($subLocation);
						$csvData['melegburkolo-' . $slug . '-' . $subSlug] = $subLocation;
					} else {
						// Main location (e.g., Budapest)
						$csvData['melegburkolo-' . $slug] = $mainLocation;
					}
				}
			}
			?>

			<?php if($locations->count()) : ?>
				<?php foreach($locations as $location): ?>

			  	<div class="service-location">
						<?php
						// Get display title from CSV data, fallback to cleaned title
						$displayTitle = $csvData[$location->slug()] ?? str_replace('melegburkolo-', '', $location->title());
						?>
						<h3><a href="<?= $location->slug() ?>"><?= $displayTitle ?></a></h3>
					</div>

				<?php endforeach ?>
			<?php endif ?>

			</section>

		</div>
  </div>