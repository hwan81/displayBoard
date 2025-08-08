<?php
// 데이터베이스 연결 설정 예제
// 이 파일을 복사하여 db_config.php로 이름을 변경하고 실제 정보로 수정하세요.

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username'); // 실제 데이터베이스 사용자 이름으로 변경
define('DB_PASSWORD', 'your_password'); // 실제 데이터베이스 비밀번호로 변경
define('DB_NAME', 'your_database'); // 실제 데이터베이스 이름으로 변경

// 데이터베이스 연결
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// 연결 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 문자 인코딩 설정
$conn->set_charset("utf8mb4");

// 나이스 교육정보 개방 포털 API 키
// https://open.neis.go.kr/ 에서 발급받은 API 키로 변경하세요.
define('NEIS_API_KEY', 'your_neis_api_key'); // 실제 API 키로 변경
?>
