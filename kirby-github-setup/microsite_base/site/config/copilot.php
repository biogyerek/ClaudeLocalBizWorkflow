<?php

return [
    'providers' => [
        'openai' => [
            'model' => 'gpt-4o-2024-08-06',
            'apiKey' => getenv('OPENAI_API_KEY') ?: '',
            'systemPrompt' => ' Provide responses in plain text and format using plain Markdown syntax.',
            'temperature' => 0.6,
            'maxGenerationTokens' => 16384,
        ],
    ],
];