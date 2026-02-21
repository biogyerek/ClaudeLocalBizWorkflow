<section class="content">

<div class="container grid">

  <div class="prose">

    <?= Str::template($page->text()->kt(), ['service' => $page->title()]) ?>

  </div>

  <?php snippet('layouts/sidebar') ?>

</div>

</section>