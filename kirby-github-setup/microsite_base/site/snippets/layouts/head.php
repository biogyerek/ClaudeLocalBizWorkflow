	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">

	<?php snippet('tag/title') ?>
	<?php snippet('seo/head'); ?>

	<meta property="og:title" content="<?= $page->title() ?>">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="<?= $site->title() ?>">
	<meta property="og:url" content="<?= $page->url() ?>">
	<meta http-equiv="Content-Security-Policy" content="block-all-mixed-content" />

	<?= Bnomei\Fingerprint::css('assets/css/index.css') ?>
	<?php snippet('tag/brand-colors') ?>
	<?php snippet('tag/custom-css') ?>


	<?php snippet('tag/whatconverts') ?>
	<?php snippet('tag/conversion-tracking') ?>
	<?php snippet('tag/googlefonts') ?>
	<?php snippet('favicon') ?>
	<?php snippet('tag/schema') ?>

