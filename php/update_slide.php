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
$slide_id = $_POST['slide_id'] ?? 0;
$content = $_POST['content'] ?? '';
$duration = $_POST['duration'] ?? 10;
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

if (empty($slide_id) || empty($content) || empty($start_date) || empty($end_date)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '슬라이드 ID와 내용은 필수입니다.']);
    exit;
}

// 보안: 이 슬라이드가 현재 로그인한 사용자의 소유인지 확인
$stmt = $conn->prepare("SELECT id FROM slides WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $slide_id, $user_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => '이 슬라이드�� 수정할 권한이 없습니다.']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// 슬라이드 업데이트
$stmt = $conn->prepare("UPDATE slides SET content = ?, duration = ?, start_date = ?, end_date = ? WHERE id = ?");
$stmt->bind_param("sisss", $content, $duration, $start_date, $end_date, $slide_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => '슬라이드가 성공적으로 수정되었습니다.']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => '슬라이드 수정 중 오류가 발생했습니다.']);
}

$stmt->close();
$conn->close();
?>
