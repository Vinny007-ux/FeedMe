<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: user_login.php");
    exit();
}

$category = $_GET['category'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = $_POST['message'];
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $user_id = $user['id'];

    $insert = $conn->prepare("INSERT INTO feedback (user_id, category, message, created_at) VALUES (?, ?, ?, NOW())");
    $insert->bind_param("iss", $user_id, $category, $message);
    $insert->execute();

    echo "<div class='feedback-success'>Feedback submitted successfully. <a href='student_dashboard.php'>Back to Dashboard</a></div>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="feedback-form">
        <h2><center>Submit Feedback for <?php echo htmlspecialchars($category); ?></center></h2>
        <form method="post" action="">
            <label for="message">Your Feedback</label>
            <textarea id="message" name="message" rows="10" cols="50" placeholder="Type your feedback here..." required></textarea>
            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</body>
</html>
