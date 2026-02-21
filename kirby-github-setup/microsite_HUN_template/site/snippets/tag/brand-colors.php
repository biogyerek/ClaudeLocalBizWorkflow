
	<style>

	:root {

			/* VARIABLES FROM PANEL */
<?php if($site->colorBrand()->isNotEmpty()) : ?>
			--color-menu-bg: <?= $site->colorBrand() ?>;
<?php else : ?>
			--color-menu-bg: var(--color-black);
<?php endif ?>
<?php if($site->colorText()->isNotEmpty()) : ?>
		  	--color-menu-text: <?= $site->colorText() ?>;
<?php else : ?>
			--color-menu-text: var(--color-white);
<?php endif ?>
<?php if($site->colorLinks()->isNotEmpty()) : ?>
			--color-highlight: <?= $site->colorLinks() ?>;
<?php else : ?>
			--color-highlight: var(--color-blaze);
<?php endif ?>
<?php if($site->colorUnderline()->isNotEmpty()) : ?>
			--color-link: <?= $site->colorUnderline() ?>;
<?php else : ?>
			--color-link: var(--color-yellow);
<?php endif ?>

<?php if($site->colorBtn()->isNotEmpty()) : ?>
			--color-cta-bg: <?= $site->colorBtn() ?>;
<?php else : ?>
			--color-cta-bg: var(--color-blaze);
<?php endif ?>
<?php if($site->colorBtnHover()->isNotEmpty()) : ?>
			--color-cta-hover-bg: <?= $site->colorBtnHover() ?>;
<?php else : ?>
			--color-cta-hover-bg: var(--color-blaze-800);
<?php endif ?>
<?php if($site->colorBtnText()->isNotEmpty()) : ?>
			--color-cta-text: <?= $site->colorBtnText() ?>;
<?php else : ?>
			--color-cta-text: var(--color-white);
<?php endif ?>



<?php if($site->logoWidth()->isNotEmpty()) : ?>
			--logo-width: <?= $site->logoWidth() ?>px;
<?php else : ?>
			--logo-width: 280px;
<?php endif ?>
<?php if($site->logoWidthMobile()->isNotEmpty()) : ?>
			--logo-width-mobile: <?= $site->logoWidthMobile() ?>px;
<?php else : ?>
			--logo-width-mobile: 280px;
<?php endif ?>

	}

	</style>
