<?php
$files = array_merge(
    glob(__DIR__ . '/resources/views/customer/*.blade.php'),
    glob(__DIR__ . '/resources/views/customer/*/*.blade.php'),
    [__DIR__ . '/resources/views/welcome.blade.php']
);

foreach ($files as $file) {
    if (is_file($file)) {
        $content = file_get_contents($file);
        if (strpos($content, '<link rel="icon"') === false) {
            $content = str_replace('<head>', "<head>\n    <link rel=\"icon\" type=\"image/png\" href=\"{{ asset('charmonti.png') }}\">", $content);
            file_put_contents($file, $content);
            echo "Updated " . basename($file) . "\n";
        }
    }
}
echo "Done.";
