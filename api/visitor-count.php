<?php
header('Content-Type: application/json');
require_once '../config.php';

$ip_address   = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$user_agent   = $_SERVER['HTTP_USER_AGENT'] ?? '';
$page_visited = $_SERVER['REQUEST_URI'] ?? '/';
$visit_date   = date('Y-m-d');

$total_visitors = 0;

try {
    $conn = getDBConnection();

    $stmt = $conn->prepare(
        "INSERT INTO visitor_stats (ip_address, user_agent, page_visited, visit_date, visit_count)
         VALUES (?, ?, ?, ?, 1)
         ON DUPLICATE KEY UPDATE visit_count = visit_count + 1, last_visit = CURRENT_TIMESTAMP"
    );
    if ($stmt) {
        $stmt->bind_param("ssss", $ip_address, $user_agent, $page_visited, $visit_date);
        $stmt->execute();
        $stmt->close();
    }

    $result = $conn->query("SELECT COUNT(DISTINCT ip_address) as total FROM visitor_stats");
    if ($result) {
        $row = $result->fetch_assoc();
        $total_visitors = (int)$row['total'];
    }

    $conn->close();
} catch (Exception $e) {
    // DB unavailable — return a static count
    $total_visitors = 1;
}

echo json_encode(['success' => true, 'count' => $total_visitors]);
?>
