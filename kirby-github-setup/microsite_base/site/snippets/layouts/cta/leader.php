<?php if($template->cta()->IsNotEmpty()) :
$ctaLink = $template->cta()->toPage()->url() ;
elseif ($site->estimate()->isNotEmpty()):
$ctaLink = $site->estimate()->toPage()->url();
endif ?>

<aside class="leader-cta">
	<a class="btn btn--offer" href="<?= $ctaLink ?>/"><?php echo t('offerCTA') ?></a>
	<div class="cta-subtext"><span><?php echo t('offerSubtext') ?></span></div>
</aside>