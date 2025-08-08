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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 설정 정보 가져오기
    $stmt = $conn->prepare("SELECT api_area_code, api_school_code, hide_meal_info FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $settings = $result->fetch_assoc();
    $stmt->close();

    echo json_encode(['status' => 'success', 'data' => $settings]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 설정 정보 업데이트
    $area_code = $_POST['api_area_code'] ?? '';
    $school_code = $_POST['api_school_code'] ?? '';
    $hide_meal_info = isset($_POST['hide_meal_info']) ? 1 : 0;

    if (empty($area_code) || empty($school_code)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => '모든 필드를 입력해주세요.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET api_area_code = ?, api_school_code = ?, hide_meal_info = ? WHERE id = ?");
    $stmt->bind_param("ssii", $area_code, $school_code, $hide_meal_info, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'API 정보가 성공적으로 저장되었습니다.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => '정보 저장 중 오류가 발생했습니다.']);
    }
    $stmt->close();
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => '허용되지 않는 메서드입니다.']);
}

$conn->close();
?>
