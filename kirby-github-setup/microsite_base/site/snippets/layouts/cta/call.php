<?php if ($site->phone()->isNotEmpty()): ?>
 <a class="btn btn--call" href="tel:<?= Html::decode($site->phone()) ?>"><?php echo t('callCTA') ?> <?= Html::decode($site->phone()) ?></a>
<?php elseif ($site->estimate()->isNotEmpty()): ?>
 <a class="btn btn--cta" href="<?= $site->estimate()->toPage() ?>"><?php echo t('ctaEstimate') ?></a>
<?php elseif ($site->contact()->isNotEmpty()): ?>
 <a class="btn btn--cta" href="<?= $site->contact()->toPage() ?>"><?php echo t('ctaContact') ?></a>
<?php elseif($contact = $site->children()->findBy('template', 'contact')): ?>
 <a class="btn btn--cta" href="<?php echo $contact->url() ?>"><?php echo t('ctaContact') ?></a>
<?php else: ?>
<?php endif ?>