<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Проверка аутентификации (простая проверка пароля)
$validPassword = 'admin123'; // Замените на свой пароль
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$providedPassword = str_replace('Bearer ', '', $authHeader);

if ($providedPassword !== $validPassword) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Проверка метода
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Проверка загруженного файла
if (empty($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES['image'];

// Проверка ошибок загрузки
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'File upload error: ' . $file['error']]);
    exit;
}

// Проверка типа файла
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, GIF and WEBP are allowed.']);
    exit;
}

// Проверка размера файла (максимум 5MB)
$maxSize = 5 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'File is too large. Maximum size is 5MB.']);
    exit;
}

// Создаем папку для загрузок, если ее нет
$uploadDir = __DIR__ . '/../../uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Генерируем уникальное имя файла
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = uniqid('work_') . '.' . $extension;
$filePath = $uploadDir . $fileName;

// Перемещаем файл
if (move_uploaded_file($file['tmp_name'], $filePath)) {
    // Возвращаем имя файла для использования в форме
    echo json_encode(['success' => true, 'filename' => $fileName]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file']);
}
?>