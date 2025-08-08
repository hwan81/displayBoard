<?php
session_start(); // 세션 시작

require_once 'db_config.php';

// 응답 형식 지정
header('Content-Type: application/json');

// POST 요청인지 확인
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'POST 메서드만 허용됩니다.']);
    exit;
}

// 입력 값 가져오기
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 유효성 검사
if (empty($username) || empty($password)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => '아이디와 비밀번호를 모두 입력해주세요.']);
    exit;
}

// 사용자 정보 조회
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($userId, $hashedPassword);
    $stmt->fetch();

    // 비밀번호 확인
    if (password_verify($password, $hashedPassword)) {
        // 로그인 성공
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;

        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => '로그인 성공!', 'redirect' => 'admin.php']);
    } else {
        // 비밀번호 불일치
        http_response_code(401); // Unauthorized
        echo json_encode(['status' => 'error', 'message' => '아이디 또는 비밀번호가 올바르지 않습니다.']);
    }
} else {
    // 사용자 없음
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => '아이디 또는 비밀번호가 올바르지 않습니다.']);
}

$stmt->close();
$conn->close();
?>
