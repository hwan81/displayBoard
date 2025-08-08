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
$date_filter = isset($_GET['date']) ? $_GET['date'] : null;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null; // 페이징을 위한 limit 추가
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0; // 페이징을 위한 offset 추가

// 기본 쿼리
$sql = "SELECT id, content, duration, slide_order, start_date, end_date, created_at FROM slides WHERE user_id = ?";
$params = ['i', $user_id];

if ($date_filter) {
    // 특정 날짜 필터링 (오늘의 슬라이드)
    $sql .= " AND ? BETWEEN start_date AND end_date";
    $params[0] .= 's';
    $params[] = $date_filter;
    $sql .= " ORDER BY slide_order ASC";
} else {
    // 모든 슬라이드 조회 시 최신순 정렬 및 제한
    $sql .= " ORDER BY created_at DESC";

    // 모든 슬라이드 조회 시 기본적으로 최대 100개로 제한
    if ($limit === null) {
        $limit = 100;
    }
}

// 페이징 적용
if ($limit !== null) {
    $sql .= " LIMIT ? OFFSET ?";
    $params[0] .= 'ii';
    $params[] = $limit;
    $params[] = $offset;
}

$stmt = $conn->prepare($sql);

// Dynamically bind parameters
$stmt->bind_param(...$params);

$stmt->execute();
$result = $stmt->get_result();
$slides = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 모든 슬라이드 조회 시 총 개수도 함께 반환
$total_count = null;
if ($date_filter === null) {
    $count_stmt = $conn->prepare("SELECT COUNT(*) as total FROM slides WHERE user_id = ?");
    $count_stmt->bind_param("i", $user_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_count = $count_result->fetch_assoc()['total'];
    $count_stmt->close();
}

$response = [
    'status' => 'success',
    'data' => $slides
];

if ($total_count !== null) {
    $response['total_count'] = $total_count;
    $response['limit'] = $limit;
    $response['offset'] = $offset;
}

echo json_encode($response);

$conn->close();
?>
