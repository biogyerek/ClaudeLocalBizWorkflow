<?php

return function($kirby, $site, $pages, $page) {

  $form = $kirby->controller('form' , compact('kirby'));

  $articles = $site->index()->filterBy('template', 'in', ['article']);
  $articles = $articles->sortBy('date', 'desc')->paginate(10);

  $pagination = $articles->pagination();

  return A::merge($form , compact('articles', 'pagination'));

};