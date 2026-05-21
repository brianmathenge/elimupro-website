<?php
// Contact form handler for ELIMUPRO International
// Emails go to: info@elimupro.com

$receiving_email_address = 'info@elimupro.com';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = isset($_POST["name"]) ? strip_tags(trim($_POST["name"])) : '';
$email = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
$message = isset($_POST["message"]) ? strip_tags(trim($_POST["message"])) : '';

// Validate inputs
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

// Prepare email
$subject = "New Inquiry from ELIMUPRO Website - $name";

$email_body = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #EE7600; color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0 0 0; font-size: 14px; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 8px 8px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #EE7600; }
        .message-box { background: white; padding: 15px; border-radius: 5px; border: 1px solid #eee; margin-top: 5px; }
        .footer { text-align: center; padding: 15px; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>ELIMUPRO International</h2>
            <p>New Website Inquiry</p>
        </div>
        <div class='content'>
            <div class='field'>
                <span class='label'>Name:</span><br>
                " . htmlspecialchars($name) . "
            </div>
            <div class='field'>
                <span class='label'>Email:</span><br>
                " . htmlspecialchars($email) . "
            </div>
            <div class='field'>
                <span class='label'>Message:</span>
                <div class='message-box'>" . nl2br(htmlspecialchars($message)) . "</div>
            </div>
            <div class='field'>
                <span class='label'>Submitted:</span> " . date('F j, Y \a\t g:i A') . "<br>
                <span class='label'>IP Address:</span> " . $_SERVER['REMOTE_ADDR'] . "
            </div>
        </div>
        <div class='footer'>
            <p>This email was sent from the ELIMUPRO International website contact form.</p>
            <p>&copy; " . date('Y') . " ELIMUPRO International. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
";

// Email headers
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$headers .= "From: ELIMUPRO Website <noreply@elimupro.com>\r\n";
$headers .= "Reply-To: $name <$email>\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Send email
if (mail($receiving_email_address, $subject, $email_body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully. We will get back to you soon.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error sending your message. Please try again later or email us directly at info@elimupro.com.']);
}
?>