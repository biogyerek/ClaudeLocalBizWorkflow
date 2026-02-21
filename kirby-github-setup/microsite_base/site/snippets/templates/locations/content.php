<?php if($page->text()->IsNotEmpty()): ?>
<div class="container grid">
	
	<div class="prose">
	  <?= $page->locationstext()->kt() ?>
	</div>

	<?php snippet('layouts/sidebar') ?>

</div>
<?php endif ?>