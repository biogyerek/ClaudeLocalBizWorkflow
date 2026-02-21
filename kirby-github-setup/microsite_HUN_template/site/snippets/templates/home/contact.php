<section class="contact">

	<div class="container">
		<div class="contact-grid">

			<div>
				<h3><?php echo t('subtitleContact') ?></h3>
				<!-- The following repeating snippet could be refactored by passing a variable -->
				<?php if($site->titleContact()->IsNotEmpty()) : ?>
				<h2><?= $site->titleContact()->kti() ?></h2>
				<?php else : ?>
				<h2><?php echo t('titleContact') ?></h2>
				<?php endif ?>


				<?= $site->contactText()->kt() ?>
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