<?php

return function($kirby, $site, $pages, $page) {

  $form = $kirby->controller('form' , compact('kirby'));

  $services = $site->index()->filterBy('template', 'in', ['service']);

  return A::merge($form , compact('services'));

};