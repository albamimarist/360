<?php
header('Content-Type: application/json');

$pagesDir = 'pages/';
$folders = [];

if (is_dir($pagesDir)) {
    $items = scandir($pagesDir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || !is_dir($pagesDir . $item)) continue;
        
        $folderPath = $pagesDir . $item;
        $files = scandir($folderPath);
        $images = [];
        $htmlFiles = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $fullPath = "$item/$file";

            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $images[] = $fullPath;
            } elseif ($ext === 'html') {
                $htmlFiles[] = $fullPath;
            }
        }

        if (!empty($images) || !empty($htmlFiles)) {
            $folders[] = [
                'name' => $item,
                'images' => $images,
                'html' => $htmlFiles
            ];
        }
    }
}

echo json_encode($folders);
?>