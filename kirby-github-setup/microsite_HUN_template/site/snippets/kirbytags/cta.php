<?php if($boxes = $site->ctaBoxes()->toStructure()) : ?>

<?php foreach($boxes as $box): ?>

<?php if($class == $box->tag()): ?>

	<?php if ($box->isCall()->toBool() === true): ?>

<aside class="cta-box call">
	<h3><?= $box->text() ?></h3>
	<a class="btn btn--box-call" href="tel:<?= Html::decode($site->phone()) ?>"><?= $box->cta() ?></a>
</aside>

	<?php elseif($box->isCall()->toBool() === false): ?>

<aside class="cta-box">
	<h3><?= $box->text() ?></h3>
	<a class="btn btn--box" href="<?= $box->link()->toPage()->url() ?>"><?= $box->cta() ?></a>
</aside>

	<?php endif ?>

<?php endif ?>

<?php endforeach ?>

<?php endif ?>

<!-- <aside class="cta-box">
	<h3>The CTA tag <?= $class ?> doesn't exist yet.</h3>
</aside> -->