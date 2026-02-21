<?php

$originalTag = Kirby\Text\KirbyTag::$types['image'];
Kirby::plugin('bucklespublishing/images', [
    'tags' => [
		'image' => [
			'attr' => $originalTag['attr'],

			'html' => function($tag) use ($originalTag)  {
				
				$file = $tag->file($tag->value());
				$image = $tag->file($tag->attr('image'));


		        if (!$image) {
		            return $result = $originalTag['html']($tag);
		        }


            	$imageResized = $image->thumb('kirbytext');
            	$imageUrl = $imageResized->url();

       			$result = $originalTag['html']($tag);

            	$pattern = '/<img.*?>/i';


				// build a new image tag with the srcset
				$image = Html::img($imageUrl, [
				    'width'  => $tag->width ?? $file->width(),
				    'height' => $tag->height ?? $file->height(),
				    'class'  => $tag->imgclass,
				    'title'  => $tag->title,
				    'alt'    => $tag->alt ?? $file->alt(),
				    'decoding' => 'async',
				    'loading' => 'lazy',
				]);

                // replace the old image tag
                $result = preg_replace($pattern, $image , $result);


                // // build a new image tag with the resized image
                // $image = Html::img($imageUrl);

				// // add lazy-load
                // $result = str_replace('<img', '<img loading="lazy"', $image);


				return $result;
			}
		]
    ]
]);