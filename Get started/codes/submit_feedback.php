<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Feedback Submission</title>
</head>
<body>
<?php
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['feedback']) && !empty(trim($input['feedback']))) {
    $feedback = trim($input['feedback']);
    
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'neximpact');
    
    if ($conn->connect_error) {
        echo json_encode(['message' => 'Database connection failed.']);
        exit;
    }

    $stmt = $conn->prepare('INSERT INTO feedback (feedback_text, submitted_at) VALUES (?, NOW())');
    $stmt->bind_param('s', $feedback);
    
    if ($stmt->execute()) {
        echo json_encode(['message' => 'Thank you for your feedback!']);
    } else {
        echo json_encode(['message' => 'Failed to submit feedback.']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['message' => 'Invalid feedback input.']);
}
?>
</body>
</html>