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

// Fetch all notices
$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Notice Board</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .notice-card {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .notice-header {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-bottom: 1px solid #0056b3;
            border-radius: 5px 5px 0 0;
        }
        .notice-body {
            padding: 15px;
        }
        .notice-footer {
            padding: 10px 15px;
            background-color: #e9ecef;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 5px 5px;
            font-size: 0.8em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Latest Notices</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="notice-card">';
                echo '<div class="notice-header">' . htmlspecialchars($row["title"]) . '</div>';
                echo '<div class="notice-body">' . nl2br(htmlspecialchars($row["description"])) . '</div>';
                echo '<div class="notice-footer">Posted on: ' . date("F j, Y, g:i a", strtotime($row["created_at"])) . '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="alert alert-info">No notices available yet.</p>';
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
