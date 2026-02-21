
<?php
// Get the main keyword from page title
$keyword = $page->title();

if($page->template() == 'service') :
    // Service pages: include keyword in description
    $intro = $page->intro()->isNotEmpty() ? html::decode($page->intro()->markdown()->short(320)) : '';
    if (!empty($intro)) {
        $description = $keyword . ' - ' . $intro;
    } else {
        $description = $keyword . ' szolgáltatás Budapesten. Hívjon most gyors és szakszerű kivitelezésért!';
    }
?>
	<meta name="description" content="<?= $description ?>">
<?php elseif($page->template() == 'location') : ?>
	<meta name="description" content="<?= Str::template(html::decode($page->parent()->intro()->markdown()->short(360)), [
	    'location' => $page->title()
	]) ?>">
<?php else :
    // Other pages: prepend keyword to intro
    $intro = $page->intro()->isNotEmpty() ? html::decode($page->intro()->markdown()->short(320)) : '';
    if (!empty($intro)) {
        // Check if intro already starts with the keyword
        if (stripos($intro, $keyword) === false) {
            $description = $keyword . ' - ' . $intro;
        } else {
            $description = $intro;
        }
    } else {
        $description = $keyword;
    }
?>
	<meta name="description" content="<?= $description ?>">
<?php endif ?>
