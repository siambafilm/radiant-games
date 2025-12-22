<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$dataFile = __DIR__ . '/../data/works.json';

// Получение списка работ
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists($dataFile)) {
        readfile($dataFile);
    } else {
        echo json_encode(['works' => []]);
    }
    exit;
}

// Добавление новой работы (для обработки формы)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (json_last_error() !== JSON_ERROR_NONE || !$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
        exit;
    }
    
    // Проверка обязательных полей
    $required = ['title', 'description', 'image', 'category'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['error' => "Field '$field' is required"]);
            exit;
        }
    }
    
    // Загрузка данных
    $data = [];
    if (file_exists($dataFile)) {
        $data = json_decode(file_get_contents($dataFile), true);
    }
    
    if (!isset($data['works'])) {
        $data['works'] = [];
    }
    
    // Добавление новой работы
    $newWork = [
        'title' => trim($input['title']),
        'description' => trim($input['description']),
        'image' => trim($input['image']),
        'category' => trim($input['category'])
    ];
    
    $data['works'][] = $newWork;
    
    // Сохранение
    if (file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode(['success' => true, 'work' => $newWork]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to save data']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>