<?php
header('Content-Type: application/json');
require_once '../config.php';

$conn = getDBConnection();

$sql = "SELECT id, title, description, image_url, technologies, project_url, github_url, created_at 
        FROM projects 
        WHERE status = 'active' 
        AND title NOT IN ('E-Commerce Platform', 'Task Management App')
        ORDER BY created_at DESC";

$result = $conn->query($sql);

$projects = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Rename Portfolio Website to correct project name
        if ($row['title'] === 'Portfolio Website') {
            $row['title'] = 'Vital Event Registration and Management System';
            $row['description'] = 'A comprehensive system for registering and managing vital events such as births, deaths, and marriages.';
            $row['technologies'] = 'HTML5, CSS3, JavaScript, MySQL,PHP';
            $row['github_url'] = 'https://github.com/Tesfaw12/Vital-Event-Registration-and-Management-for-South-Gondar';
        }
        $projects[] = $row;
    }
}

echo json_encode(['success' => true, 'projects' => $projects]);

$conn->close();
?>
