<?php
Kirby::plugin('shawnbuckles/element', [
  'options' => [
    'default_style' => 'none'
  ],
'tags' => [
  'element' => [
      'attr' => [
        'class',
        'title',
        'text'
      ],
      'html' => function($tag) {

        if ($tag) {
          return snippet('kirbytags/element', ['class' => $tag->value, 'headline' => $tag->title, 'text' => $tag->text], true);
        } else {
          return '';
        }
      }
    ]
  ]
]);