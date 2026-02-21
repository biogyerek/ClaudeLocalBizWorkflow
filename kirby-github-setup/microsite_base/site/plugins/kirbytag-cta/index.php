<?php
Kirby::plugin('shawnbuckles/cta', [
  'options' => [
    'default_style' => 'none'
  ],
'tags' => [
  'cta' => [
      'attr' => [
        'class'
      ],
      'html' => function($tag) {

        if ($tag) {
          return snippet('kirbytags/cta', ['class' => $tag->value], true);
        } else {
          return '';
        }
      }
    ]
  ]
]);