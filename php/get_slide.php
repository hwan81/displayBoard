<?php
session_start();
require_once 'db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => '로그인이 필요합니다.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$slide_id = $_GET['slide_id'] ?? 0;

if (empty($slide_id)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '슬라이드 ID가 필요합니다.']);
    exit;
}

// 보안: 이 슬라이드가 현재 로그인한 사용자의 소유인지 확인
$stmt = $conn->prepare("SELECT id, content, duration, start_date, end_date FROM slides WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $slide_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $slide = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $slide]);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => '슬라이드를 찾을 수 없거나 권한이 없습니다.']);
}

$stmt->close();
$conn->close();
?>
