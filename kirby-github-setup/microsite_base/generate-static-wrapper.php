<?php

/**
 * Static Site Generator Wrapper
 *
 * This PHP script wraps the bash-based wget static site generator.
 * It provides a PHP interface for easier integration with Kirby workflows.
 */

// Configuration
$siteUrl = getenv('SITE_URL') ?: 'http://localhost:8000';
$outputDir = './static';

// Parse command line arguments
$options = getopt('', ['url:', 'output:']);
if (isset($options['url'])) {
    $siteUrl = $options['url'];
}
if (isset($options['output'])) {
    $outputDir = $options['output'];
}

echo "🌐 Static Site Generator (PHP Wrapper)\n";
echo "======================================\n\n";

// Check if bash script exists
$bashScript = __DIR__ . '/generate-static.sh';
if (!file_exists($bashScript)) {
    echo "❌ Error: generate-static.sh not found!\n";
    echo "Expected location: $bashScript\n";
    exit(1);
}

// Make sure script is executable
chmod($bashScript, 0755);

// Build command
$cmd = sprintf(
    'bash %s --url %s --output %s 2>&1',
    escapeshellarg($bashScript),
    escapeshellarg($siteUrl),
    escapeshellarg($outputDir)
);

echo "📋 Executing: generate-static.sh\n";
echo "   Site URL: $siteUrl\n";
echo "   Output: $outputDir\n\n";

// Execute bash script and stream output
$descriptors = [
    0 => ['pipe', 'r'],  // stdin
    1 => ['pipe', 'w'],  // stdout
    2 => ['pipe', 'w']   // stderr
];

$process = proc_open($cmd, $descriptors, $pipes);

if (is_resource($process)) {
    // Close stdin
    fclose($pipes[0]);

    // Read stdout
    while (!feof($pipes[1])) {
        echo fgets($pipes[1]);
        flush();
    }
    fclose($pipes[1]);

    // Read stderr
    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    // Get exit code
    $returnCode = proc_close($process);

    if ($errors) {
        echo "\n⚠️  Errors/Warnings:\n";
        echo $errors;
    }

    if ($returnCode !== 0) {
        echo "\n❌ Generation failed with exit code: $returnCode\n";
        exit($returnCode);
    }

    echo "\n✅ Static site generation completed successfully!\n";
    exit(0);
} else {
    echo "❌ Error: Failed to execute generate-static.sh\n";
    exit(1);
}
