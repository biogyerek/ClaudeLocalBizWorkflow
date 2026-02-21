<?php
// Get the main keyword from page title
$keyword = $page->title();

// Generate meta description based on template
if($page->template() == 'service') :
    $intro = $page->intro()->isNotEmpty() ? html::decode($page->intro()->markdown()->short(320)) : '';
    if (!empty($intro)) {
        $description = $keyword . ' - ' . $intro;
    } else {
        $description = $keyword . ' szolgáltatás Budapesten. Hívjon most gyors és szakszerű kivitelezésért!';
    }
elseif($page->template() == 'location') :
    $description = Str::template(html::decode($page->parent()->intro()->markdown()->short(360)), [
        'location' => $keyword
    ]);
else :
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
endif;
?>
<meta name="description" content="<?= $description ?>">
<meta property="og:description" content="<?= $description ?>">
<meta name="twitter:description" content="<?= $description ?>">
