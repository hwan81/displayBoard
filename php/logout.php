<?php
session_start();

// 한국 시간대로 설정
date_default_timezone_set('Asia/Seoul');

// 모든 세션 변수 지우기
$_SESSION = array();

// 세션 쿠키 삭제
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 세션 파기
session_destroy();

header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => '로그아웃되었습니다.']);
?>
