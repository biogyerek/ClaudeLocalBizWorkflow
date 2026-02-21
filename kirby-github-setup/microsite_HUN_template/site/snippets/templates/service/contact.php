<section class="contact">

	<div class="container">
		<div class="contact-grid">

			<div>
				<h3><?php echo t('subtitleContact') ?></h3>
				<h2><?php echo t('titleContact') ?></h2>
				<p><?= $page->contactText()->kti() ?></p>
				<?php snippet('layouts/cta/call') ?>
				<div class="contact-map">
					<div class="responsive-iframe">
						<?= $site->map() ?>
					</div>
				</div>
			</div>
			<div class="contact-form">
				<?php snippet('form/contact-form-v2') ?>
			</div>
		</div>
	</div>

</section>