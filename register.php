<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role = "student"; // All registrations default to student user role

    // Check if username already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Username already taken. Please choose another.";
    } else {
        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, role, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $username, $hashedPassword, $role);

        if ($stmt->execute()) {
            // Redirect to login after successful registration
            header("Location: user_login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2 class="form-title">Registration</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="user_login.php" class="login-link">Login</a></p>
    </div>
</body>
</html>

