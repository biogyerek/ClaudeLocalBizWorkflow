<?php

Kirby::plugin('bucklespublishing/placeholders', [
    'fieldMethods' => [
        'replace' => function ($field, array $placeholders = []) {

            // replace the field value
            $field->value = Str::template($field->value, $placeholders);

            return $field;
        },
        'toOptions' => function($field) {
            $result = [];
            foreach ($field->toStructure() as $option) {
                // use only key/value pairs that are not empty
                if ($option->key()->isNotEmpty() && $option->value()->isNotEmpty()) {
                    // check if the block field is set to true and parse KirbyTags
                    if ($option->block()->toBool() === true) {
                        $result[$option->key()->value()] = kirbytags($option->value());
                    } else {
                        $result[$option->key()->value()] = $option->value()->html();
                    }
                }
            }
            return $result;
        }
    ],
    // Don't use both hooks but only one as required, remove or comment the one you don't need
    'hooks' => [
        'kirbytags:before' => function ($text) {

            // set the options from the config as defaults
            $defaults = option('bucklespublishing.placeholders.replacements', []);
            $options  = [];

            // if options from fields are set to true, get options from field
            if (option('bucklespublishing.placeholders.field')) {
                $field   = option('bucklespublishing.placeholders.field', 'placeholders');
                $options = site()->{$field}()->toOptions();
            }

            // merge defaults and options
            $options = array_merge($defaults, $options);

            return Str::template($text, $options);
        },
       'page.render:after' => function (string $contentType, string $html) {
            if ($contentType !== 'html') {
                return $html;
            }

            return Str::template($html, site()->placeholders()->toOptions());
        },
    ]
]);