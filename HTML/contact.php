<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);
    
    // Validate inputs
    $errors = [];
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // If no errors, proceed with sending email
    if (empty($errors)) {
        // Your email address
        $to = "matthewmukasa0@gmail.com";
        $subject = "New Contact Form Message from $name";
        
        // Create email body
        $email_body = "You have received a new message.\n\n" .
                     "Name: $name\n" .
                     "Email: $email\n" .
                     "Message:\n$message\n";
        
        // Additional headers
        $headers = "From: $email\r\n" .
                  "Reply-To: $email\r\n" .
                  "X-Mailer: PHP/" . phpversion();
        
        // Try to send email
        try {
            if (mail($to, $subject, $email_body, $headers)) {
                // Redirect to thank you page
                header('Location: thank_you.html');
                exit();
            } else {
                throw new Exception("Failed to send email");
            }
        } catch (Exception $e) {
            $errors[] = "Sorry, there was an error sending your message. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="contact-form">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn">Send Message</button>
    </form>
</body>
</html>
