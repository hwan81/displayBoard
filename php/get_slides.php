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

$sql = "SELECT id, content, duration, slide_order, start_date, end_date FROM slides WHERE user_id = ?";
$params = ['i', $user_id];

if ($date_filter) {
    $sql .= " AND ? BETWEEN start_date AND end_date";
    $params[0] .= 's';
    $params[] = $date_filter;
}

$sql .= " ORDER BY slide_order ASC";

$stmt = $conn->prepare($sql);

// Dynamically bind parameters
$stmt->bind_param(...$params);

$stmt->execute();
$result = $stmt->get_result();
$slides = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode(['status' => 'success', 'data' => $slides]);

$conn->close();
?>
