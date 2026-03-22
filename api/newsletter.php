<?php
header('Content-Type: application/json');

require_once '../config.php';
require_once '../phpmailer/Exception.php';
require_once '../phpmailer/PHPMailer.php';
require_once '../phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$email = trim($_POST['email'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Valid email is required']);
    exit;
}

$email = htmlspecialchars($email);

// Save to DB (non-fatal)
try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        if (!$stmt->execute() && $conn->errno === 1062) {
            echo json_encode(['success' => false, 'message' => 'Email already subscribed']);
            $stmt->close(); $conn->close(); exit;
        }
        $stmt->close();
    }
    $conn->close();
} catch (Exception $e) {
    // DB unavailable — log to file
    file_put_contents(__DIR__ . '/../subscribers.log', date('Y-m-d H:i:s') . " | $email\n", FILE_APPEND | LOCK_EX);
}

// Send welcome email
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = ADMIN_EMAIL;
    $mail->Password   = GMAIL_APP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true,
        ]
    ];

    $mail->setFrom(ADMIN_EMAIL, 'Tesfaw Amare');
    $mail->addAddress($email);
    $mail->Subject = "Welcome to Tesfaw Amare's Newsletter";
    $mail->Body    = "Thank you for subscribing!\n\nYou'll receive updates about my latest projects and articles.\n\nBest regards,\nTesfaw Amare";
    $mail->send();
} catch (Exception $e) {
    // Welcome email failed — not critical
}

echo json_encode(['success' => true, 'message' => 'Successfully subscribed!']);
?>
