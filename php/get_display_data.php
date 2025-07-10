<?php
require_once 'db_config.php';

header('Content-Type: application/json');

// URL 파라미터로 어떤 사용자의 게시판을 표시할지 받습니다. (예: /display.html?username=test)
$username = $_GET['username'] ?? '';

// username이 없으면, 예시로 첫 번째 사용자를 찾습니다.
if (empty($username)) {
    $result = $conn->query("SELECT username FROM users ORDER BY id ASC LIMIT 1");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['username'];
    } else {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => '표시할 사용자가 없습니다.']);
        $conn->close();
        exit;
    }
}

// 사용자의 정보(id, school_name, api 코드) 조회
$stmt = $conn->prepare("SELECT id, school_name, api_area_code, api_school_code FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();
$user_info = $user_result->fetch_assoc();
$stmt->close();

if (!$user_info || empty($user_info['api_area_code']) || empty($user_info['api_school_code'])) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => '사용자 또는 API 정보를 찾을 수 없습니다.']);
    $conn->close();
    exit;
}

$user_id = $user_info['id'];

// 한국 시간대로 설정
date_default_timezone_set('Asia/Seoul');
// 오늘 날짜의 슬라이드 목록 조회
$current_date = date('Y-m-d');
$stmt = $conn->prepare("SELECT content, duration FROM slides WHERE user_id = ? AND start_date <= ? AND end_date >= ? ORDER BY slide_order ASC");
$stmt->bind_param("iss", $user_id, $current_date, $current_date);
$stmt->execute();
$slides_result = $stmt->get_result();
$slides = $slides_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode([
    'status' => 'success',
    'user_info' => [
        'school_name' => $user_info['school_name'],
        'api_area_code' => $user_info['api_area_code'],
        'api_school_code' => $user_info['api_school_code']
    ],
    'slides' => $slides
]);

$conn->close();
?>
