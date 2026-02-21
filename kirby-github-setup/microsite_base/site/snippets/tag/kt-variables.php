<?= Str::template($page->text()->kt(), [
    'siteName' => $site->title(), 'emailPrivacy' => $site->emailPrivacy()->value()
]) ?>