<summary class="since">
	<h4><?php echo t('summaryTitle') ?></h4>

	<?= Str::template($template->summary()->kt(), [
	    'location' => $page->title()
	]) ?>

</summary>