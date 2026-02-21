<section class="articles-container">

	<div class="container">

		<div class="articles">

		<?php foreach($articles as $article): ?>

			<article class="article-card">

			<?php if($image = $article->coverimage()->toFile()):
			$thumb = $image->thumb('pick') ?>
				<div class="article-image">
					<a href="<?= $article->url() ?>">
						<?= $thumb ?>
					</a>
				</div>
			<?php endif ?>

				<div class="article-body">
					<?php if($article->date()->isNotEmpty()): ?>
					<time class="article-date" datetime="<?= $article->date()->toDate('Y-m-d') ?>">
						<?= $article->date()->toDate('Y. m. d.') ?>
					</time>
					<?php endif ?>

					<h2 class="article-heading">
						<a href="<?= $article->url() ?>"><?= $article->title()->kti() ?></a>
					</h2>

					<?php if($article->intro()->isNotEmpty()): ?>
					<p class="article-intro"><?= $article->intro()->excerpt(200) ?></p>
					<?php endif ?>

					<a class="btn btn--filled" href="<?= $article->url() ?>"><?php echo t('readMore') ?></a>
				</div>

			</article>

		<?php endforeach ?>

		</div>

		<?php if($pagination->hasPages()): ?>
		<nav class="pagination">
			<?php if($pagination->hasPrevPage()): ?>
			<a href="<?= $pagination->prevPageUrl() ?>" class="btn">&larr;</a>
			<?php endif ?>

			<?php if($pagination->hasNextPage()): ?>
			<a href="<?= $pagination->nextPageUrl() ?>" class="btn">&rarr;</a>
			<?php endif ?>
		</nav>
		<?php endif ?>

	</div>

</section>