<?php

return function($kirby, $site, $pages, $page) {

  $form = $kirby->controller('form' , compact('kirby'));

  return $form;

};