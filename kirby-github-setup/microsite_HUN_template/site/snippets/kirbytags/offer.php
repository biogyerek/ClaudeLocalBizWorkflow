<?php if($boxes = $site->offerBoxes()->toStructure()) : ?>

<?php foreach($boxes as $box): ?>

<?php if($class == $box->tag()): ?>

	<?php if ($box->isCall()->toBool() === true): ?>

<aside class="offer-box">

	<span>
		<h3><?= $box->headline() ?> <?= $service ?></h3>
		<?= $box->text()->kt() ?>
	</span>
	
	<a class="btn btn--call" href="tel:<?= Html::decode($site->phone()) ?>"><?= $box->cta() ?></a>
	<div class="cta-subtext"><span><?php echo t('offerSubtext') ?></span></div>

</aside>

	<?php elseif($box->isCall()->toBool() === false): ?>


<aside class="offer-box">

	<span>
		<h3><?= $box->headline() ?> <?= $service ?></h3>
		<?= $box->text()->kt() ?>
	</span>
	
	<a class="btn btn--offer" href="<?= $box->link()->toPage()->url() ?>"><?= $box->cta() ?></a>
	<div class="cta-subtext"><span><?php echo t('offerSubtext') ?></span></div>

</aside>

	<?php endif ?>

<?php endif ?>

<?php endforeach ?>

<?php endif ?>

<!-- <aside class="cta-box">
	<h3>The CTA tag <?= $class ?> doesn't exist yet.</h3>
</aside> -->
