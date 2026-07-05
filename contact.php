<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? 'Portfolio Contact');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
        exit;
    }

    $stmt = $conn->prepare('INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $to = 'nayangharat886@gmail.com';
        $headers = "From: $email\r\nReply-To: $email\r\n";
        $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        mail($to, $subject, $body, $headers);

        echo json_encode(['success' => true, 'message' => 'Thanks! Your message has been saved and sent.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Unable to save your message.']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
