<div class="toc text <?php if($page->toc()->isFalse()) :?>hidden<?php endif ?>">

  <?php if($page->toc_alt()->isTrue()) : ?>

  <h4 class="toc"><?= $page->toc_title() ?></h4>

  <?php else : ?>

    <h4 class="toc">Op deze pagina:</h4>

  <?php endif ?>

    <ol>

      <?php foreach($page->text()->locationheadlines() as $headline): ?>

        <li><a href="<?= Str::template($headline->url(), [
            'location' => $page->title()]) ?>"><span><?= Str::template($headline->text(), ['location' => $page->title()]) ?></span></a></li>

      <?php endforeach ?>
    </ol>

</div>