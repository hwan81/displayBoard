<?php
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
$schoolName = $_POST['schoolName'] ?? '';
$email = $_POST['email'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password-confirm'] ?? '';

// 유효성 검사
if (empty($schoolName) || empty($email) || empty($username) || empty($password)) {
    http_response_code(400); // Bad Request
    echo json_encode(['status' => 'error', 'message' => '모든 필드를 입력해주세요.']);
    exit;
}

if ($password !== $passwordConfirm) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '비밀번호가 일치하지 않습니다.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '유효하지 않은 이메일 형식입니다.']);
    exit;
}

// 아이디 또는 이메일 중복 확인
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    http_response_code(409); // Conflict
    echo json_encode(['status' => 'error', 'message' => '이미 사용 중인 아이디 또는 이메일입니다.']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// 비밀번호 해싱
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 데이터베이스에 사용자 추가
$stmt = $conn->prepare("INSERT INTO users (school_name, email, username, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $schoolName, $email, $username, $hashedPassword);

if ($stmt->execute()) {
    // 회원가입 성공 시, boards 테이블에도 정보 추가가 필요하지만,
    // API 키 정보가 없으므로 우선 users 테이블에만 추가합니다.
    // 추후 API 키를 입력받는 페이지를 만들고 boards 정보를 생성해야 합니다.
    http_response_code(201); // Created
    echo json_encode(['status' => 'success', 'message' => '회원가입이 완료되었습니다. 로그인 페이지로 이동합니다.']);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => '회원가입 중 오류가 발생했습니다.']);
}

$stmt->close();
$conn->close();
?>
