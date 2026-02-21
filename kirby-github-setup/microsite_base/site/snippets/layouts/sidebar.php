<sidebar class="sidebar">

	<aside class="sticky">

	<?php if($page->sidebar()->IsNotEmpty()) : ?>

		<?php snippet('layouts/cta/sidebar', ['page' => $page]) ?>

	<?php elseif($site->sidebar()->IsNotEmpty()) : ?>

		<?php snippet('layouts/cta/sidebar', ['page' => $site]) ?>

	<?php else : ?>

	<?php endif ?>

	</aside>

</div>