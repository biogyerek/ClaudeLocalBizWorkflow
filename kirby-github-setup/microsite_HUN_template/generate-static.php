<?php

require 'kirby/bootstrap.php';

use JR\StaticSiteGenerator;

$kirby = new Kirby\Cms\App();
$list = StaticSiteGenerator::generateFromConfig($kirby);
$count = count($list);

echo "✅ Static site generated successfully!\n";
echo "📁 $count files generated/copied\n";
echo "📂 Output directory: ./static/\n";
echo "\n";

// Run post-generation fixes
echo "🔄 Running post-generation fixes...\n";
$fixScript = __DIR__ . '/post-generate-fixes.sh';
if (file_exists($fixScript)) {
    $output = [];
    $returnCode = 0;
    exec("bash " . escapeshellarg($fixScript) . " 2>&1", $output, $returnCode);

    foreach ($output as $line) {
        echo $line . "\n";
    }

    if ($returnCode === 0) {
        echo "\n✅ Post-generation fixes completed!\n";
    } else {
        echo "\n⚠️  Warning: Post-generation fixes encountered issues (exit code: $returnCode)\n";
    }
} else {
    echo "⚠️  Warning: post-generate-fixes.sh not found, skipping fixes\n";
}

echo "\n🎉 Static site generation complete!\n";
