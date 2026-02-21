<?php
if ($page->showServices()->toBool() === true): ?>
<section class="services-container">

	<div class="container">

		<?php if($page->titleServices()->IsNotEmpty()): ?>
		<h2><?= $page->titleServices()->kti() ?></h2>
		<?php else: ?>
		<h2><?php echo t('titleServices') ?></h2>
		<?php endif ?>

		<div class="services">

		<?php foreach($services as $service): ?>

		<?php if($image = $service->coverimage()->toFile()): 
		$thumb = $image->thumb('pick') ?>

			<div class="service-box" style="background-image: url(<?= $thumb->url() ?>); background-size: cover; background-repeat: no-repeat;">
			<?php else: ?>
			<div class="service-box">
			<?php endif ?>

				<h3 class="service-heading"><?= $service->title()->kti() ?></h3>
				<span class="service-description"><?= $service->intro()->kti() ?></span>
				<a class="btn btn--filled" href="<?= $service->url() ?>"><?php echo t('readMore') ?></a>
			</div>

		<?php endforeach ?>
		
		</div>

	</div>

</section>
<?php endif ?>