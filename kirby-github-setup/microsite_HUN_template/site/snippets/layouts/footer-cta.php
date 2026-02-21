<div class="sticky-footer">

	<aside class="sticks">

	<?php if($page->sidebar()->IsNotEmpty()) : ?>

		<?php snippet('layouts/cta/sticky', ['page' => $page]) ?>

	<?php elseif($site->sidebar()->IsNotEmpty()) : ?>

		<?php snippet('layouts/cta/sticky', ['page' => $site]) ?>

	<?php else : ?>

	<?php endif ?>

	</aside>

</div>