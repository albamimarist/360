<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['folder'])) {
    echo json_encode(['error' => 'Geçersiz istek']);
    exit;
}

$folder = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['folder']);
$path = 'pages/' . $folder;

if (is_dir($path)) {
    deleteDirectory($path);
    deleteDirectory('pages/thumbs/' . $folder);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Klasör yok']);
}

function deleteDirectory($dir) {
    if (!is_dir($dir)) return;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        $path = $dir . '/' . $item;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    rmdir($dir);
}
?>