<?php
Kirby::plugin('shawnbuckles/offer', [
  'options' => [
    'default_style' => 'none'
  ],
'tags' => [
  'offer' => [
      'attr' => [
        'service',
      ],
      'html' => function($tag) {

        if ($tag) {
          return snippet('kirbytags/offer', ['class' => $tag->value, 'service' => $tag->service], true);
        } else {
          return '';
        }
      }
    ]
  ]
]);