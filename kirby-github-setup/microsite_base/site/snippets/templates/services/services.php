<section class="services-container">

	<div class="container">

		<div class="services">

		<?php foreach($services as $service): ?>

			<div class="service">

			<?php if($image = $service->coverimage()->toFile()): 
			$thumb = $image->thumb('pick') ?>
				<div>
					<?= $thumb ?>
				</div>
			<?php else: ?>
				<div></div>
			<?php endif ?>

				<div class="prose">
					<h2 class="service-heading"><?= $service->title()->kti() ?></h2>
					<p class="service-description"><?= $service->description()->kti() ?></p>
					<a class="btn btn--filled" href="<?= $service->url() ?>"><?php echo t('readMore') ?></a>
				</div>

			</div>

		<?php endforeach ?>

		</div>

	</div>

</section>