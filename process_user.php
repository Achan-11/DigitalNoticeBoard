<?php
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
    $action = $_POST["action"];

    if ($action == "add") {
        $new_username = $conn->real_escape_string($_POST["username"]);
        $new_password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Securely hash the password
        $new_role = $conn->real_escape_string($_POST["role"]);

        $sql = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$new_password', '$new_role')";

        if ($conn->query($sql) === TRUE) {
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "Error adding user: " . $conn->error;
        }
    } elseif ($action == "edit") {
        $user_id = intval($_POST["user_id"]);
        $edit_username = $conn->real_escape_string($_POST["username"]);
        $edit_role = $conn->real_escape_string($_POST["role"]);

        $sql = "UPDATE users SET username='$edit_username', role='$edit_role' WHERE user_id=$user_id";

        if ($conn->query($sql) === TRUE) {
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "Error updating user: " . $conn->error;
        }
    } elseif ($action == "delete") {
        $user_id = intval($_POST["user_id"]);

        $sql = "DELETE FROM users WHERE user_id=$user_id";

        if ($conn->query($sql) === TRUE) {
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    }
}

$conn->close();
?>
