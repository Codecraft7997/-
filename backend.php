<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
$file = 'predictions.json';

if ($method === 'GET') {
    if (file_exists($file)) {
        $data = file_get_contents($file);
        $predictions = json_decode($data, true);
        echo json_encode(['records' => $predictions]);
    } else {
        echo json_encode(['records' => []]);
    }
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['name']) || !isset($input['winner']) || !isset($input['score1']) || !isset($input['score2'])) {
        echo json_encode(['result' => 'error', 'message' => 'بيانات ناقصة']);
        exit;
    }

    $predictions = [];
    if (file_exists($file)) {
        $data = file_get_contents($file);
        $predictions = json_decode($data, true) ?? [];
    }

    $input['time'] = date("Y-m-d H:i:s");
    $predictions[] = $input;

    if (file_put_contents($file, json_encode($predictions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
        echo json_encode(['result' => 'success']);
    } else {
        echo json_encode(['result' => 'error', 'message' => 'فشل الحفظ']);
    }

    exit;
}

echo json_encode(['result' => 'error', 'message' => 'طريقة الطلب غير مدعومة']);
?>
