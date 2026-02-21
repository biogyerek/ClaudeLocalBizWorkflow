<?php foreach($services as $service): ?>

<?php if($image = $service->coverimage()): 
$thumb = $image->thumb('pick') ?>

	<div class="service-box" style="background-image: url(<?= $thumb->url() ?>); background-size: cover; background-repeat: no-repeat;">
	<?php else: ?>
	<div class="service-box">
	<?php endif ?>

		<h3 class="service-heading"><?= $service->title()->kti() ?></h3>
		<span class="service-description"><?= $service->serviceIntro()->kti() ?></span>
		<a class="btn btn--filled" href="<?= $service->url() ?>"><?php echo t('readMore') ?></a>
	</div>

<?php endforeach ?>