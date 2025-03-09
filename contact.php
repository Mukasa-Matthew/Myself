<?php
// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed');
}

if(isset($_POST['email'])) {
    // ... existing code ...
}
?> 