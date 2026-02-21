<?php
Kirby::plugin('shawnbuckles/call', [
  'options' => [
    'default_style' => 'none'
  ],
'tags' => [
  'call' => [
      'attr' => [
        'headline'
      ],
      'html' => function($tag) {

        if ($tag) {
          return snippet('kirbytags/call', ['text' => $tag->value], true);
        } else {
          return '';
        }
      }
    ]
  ]
]);