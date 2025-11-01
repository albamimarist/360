<?php
header('Content-Type: application/json');

$uploadDir = 'pages/';
$maxSize = 50 * 1024 * 1024; // 50MB
$allowedTypes = ['image/jpeg','image/png','image/jpg','text/html','application/json','text/javascript','text/css'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['files'])) {
    echo json_encode(['error' => 'Geçersiz istek']);
    exit;
}

$files = $_FILES['files'];
$folderName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST['folder'] ?? 'panorama_' . time());
$targetDir = $uploadDir . $folderName . '/';

if (!file_exists($targetDir)) mkdir($targetDir, 0755, true);

$uploaded = [];
$errors = [];

for ($i = 0; $i < count($files['name']); $i++) {
    $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $files['name'][$i]);
    $targetPath = $targetDir . $fileName;

    if ($files['size'][$i] > $maxSize) {
        $errors[] = "$fileName: Çok büyük";
        continue;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $files['tmp_name'][$i]);
    if (!in_array($mime, $allowedTypes)) {
        $errors[] = "$fileName: Desteklenmeyen tür";
        continue;
    }

    if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
        $uploaded[] = $targetPath;
    } else {
        $errors[] = "$fileName: Kaydedilemedi";
    }
}

echo json_encode([
    'success' => count($errors) === 0,
    'folder' => $folderName,
    'files' => $uploaded,
    'errors' => $errors
]);
?>