<?php
header('Content-Type: application/json');

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR])) {
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $error['message']]);
    }
});

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

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

$name    = htmlspecialchars($name);
$email   = htmlspecialchars($email);
$subject = htmlspecialchars($subject);
$message = htmlspecialchars($message);

$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Save to DB (non-fatal)
try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssss", $name, $email, $subject, $message, $ip_address, $user_agent);
        $stmt->execute();
        $stmt->close();
    }
    $conn->close();
} catch (Exception $e) {
    // DB unavailable, continue
}

// Send email via Gmail SMTP
function sendMail($to, $subject, $body, $replyTo = null) {
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

    $mail->setFrom(ADMIN_EMAIL, 'Tesfaw Amare Portfolio');
    $mail->addAddress($to);
    if ($replyTo) $mail->addReplyTo($replyTo);

    $mail->Subject = $subject;
    $mail->Body    = $body;

    return $mail->send();
}

try {
    // Notify admin
    $adminBody  = "New contact message from your portfolio:\n\n";
    $adminBody .= "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
    sendMail(ADMIN_EMAIL, "Portfolio Contact: $subject", $adminBody, $email);

    // Auto-reply to sender
    $replyBody  = "Hi $name,\n\nThank you for reaching out! I received your message and will get back to you as soon as possible.\n\nBest regards,\nTesfaw Amare";
    sendMail($email, "Thank you for contacting Tesfaw Amare", $replyBody);

    echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
} catch (Exception $e) {
    // Email failed — log to file as last resort
    $log = date('Y-m-d H:i:s') . " | $name | $email | $subject\n$message\n---\n";
    file_put_contents(__DIR__ . '/../messages.log', $log, FILE_APPEND | LOCK_EX);
    echo json_encode(['success' => false, 'message' => 'Mail error: ' . $e->getMessage()]);
}
?>
