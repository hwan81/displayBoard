<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => '로그인이 필요합니다.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'POST 메서드만 허용됩니다.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$content = $_POST['content'] ?? '';
$duration = $_POST['duration'] ?? 10;

if (empty($start_date) || empty($end_date) || empty($content)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '시작일, 종료일, 내용은 필수입니다.']);
    exit;
}

// 새 슬라이드 추가
$stmt = $conn->prepare("INSERT INTO slides (user_id, start_date, end_date, content, duration, slide_order) VALUES (?, ?, ?, ?, ?, 999)");
$stmt->bind_param("isssi", $user_id, $start_date, $end_date, $content, $duration);
$msg = "슬라이드 추가 중 오류가 발생했습니다.";
if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(['status' => 'success', 'message' => '슬라이드가 추가되었습니다.']);
} else {
    error_log("Failed to add slide: " . $stmt->error); // Add this line for detailed error logging
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $msg]);
}

$stmt->close();
$conn->close();
?>
