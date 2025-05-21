<?php
session_start();

// Database connection (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "noticeboard";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $_POST["password"];
    $selected_role = $conn->real_escape_string($_POST["role"]); // Get the selected role

    $sql = "SELECT user_id, username, password, role FROM users WHERE username='$username' AND role='$selected_role'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];

            if ($row["role"] == "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            header("Location: login.php?error=Incorrect username or password for the selected role");
            exit();
        }
    } else {
        header("Location: login.php?error=Incorrect username or password for the selected role");
        exit();
    }
} else {
    header("Location: login.php"); // Redirect if accessed directly
    exit();
}

$conn->close();
?>
