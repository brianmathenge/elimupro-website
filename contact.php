<?php
$receiving_email_address = 'info@elimupro.com';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name = strip_tags(trim($_POST["name"]));
$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
$message = strip_tags(trim($_POST["message"]));

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

$subject = "New Inquiry from ELIMUPRO Website - $name";
$email_body = "
<html>
<head><style>body{font-family:Arial,sans-serif;line-height:1.6;}.header{background:#EE7600;color:white;padding:20px;border-radius:8px 8px 0 0;}.content{background:#f9f9f9;padding:20px;border:1px solid #ddd;}.field{margin-bottom:15px;}.label{font-weight:bold;color:#EE7600;}</style></head>
<body>
<div style='max-width:600px;margin:0 auto;'>
<div class='header'><h2 style='margin:0;'>ELIMUPRO International</h2><p style='margin:5px 0 0 0;'>New Website Inquiry</p></div>
<div class='content'>
<p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
<p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
<p><strong>Date:</strong> " . date('F j, Y \a\t g:i A') . "</p>
</div>
</div>
</body>
</html>
";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: ELIMUPRO Website <noreply@elimupro.com>\r\n";
$headers .= "Reply-To: $name <$email>\r\n";

if (mail($receiving_email_address, $subject, $email_body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message.']);
}
?>