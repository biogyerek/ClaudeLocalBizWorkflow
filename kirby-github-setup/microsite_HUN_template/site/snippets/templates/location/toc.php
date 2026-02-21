<?php
$hasResources = $template->resources()->isNotEmpty();
$limit        = $hasResources ? 1 : 2;
?>

<nav aria-labelledby="toc-heading" class="toc">
  <h2 id="toc-heading" class="badge"><?php echo t('tocTitle') ?></h2>
  <ol class="pt-3">
    <?php foreach($template->text()->headlines() as $item): ?>
    <li><a href="<?= $item->id() ?>"><?= widont(Str::template($item->text(), [
              'location' => $page->title()
          ])) ?></a></li>
    <?php endforeach ?>

    <?php if($hasResources): ?>
      <li><a href="#resources">More information</a></li>
    <?php endif ?>
  </ol>
</nav>
