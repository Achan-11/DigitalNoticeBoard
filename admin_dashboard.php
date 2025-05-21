<?php
// Start session (for user authentication later)
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
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
$sql_notices = "SELECT * FROM notices ORDER BY created_at DESC";
$result_notices = $conn->query($sql_notices);

// Fetch all users
$sql_users = "SELECT * FROM users ORDER BY username ASC";
$result_users = $conn->query($sql_users);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #f8f9fa;
            display: flex; /* Enable flexbox for centering */
            justify-content: center; /* Center content horizontally */
            align-items: flex-start; /* Align items to the top */
            min-height: 100vh; /* Ensure full viewport height */
            margin: 0; /* Remove default body margin */
        }
        .container {
            margin-top: 30px;
            width: 90%; /* Adjust width as needed */
            max-width: 1200px; /* Set a maximum width */
        }
        .admin-nav {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-nav-buttons button {
            margin-right: 10px;
        }
        .notice-table, .user-table {
            margin-top: 20px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .notice-table th, .notice-table td, .user-table th, .user-table td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            text-align: left; /* Keep table content aligned to the left */
        }
        .notice-table th, .user-table th {
            background-color: #007bff;
            color: white;
        }
        .notice-table tr:last-child td, .user-table tr:last-child td {
            border-bottom: none;
        }
        .btn-sm {
            margin-right: 5px;
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 5px;
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4 text-center">Admin Dashboard</h2>

        <nav class="admin-nav">
            <div class="admin-nav-buttons">
                <button class="btn btn-primary" onclick="document.getElementById('addNoticeModal').style.display='block'">Add New Notice</button>
                <button class="btn btn-success" onclick="document.getElementById('addUserModal').style.display='block'">Add New User</button>
            </div>
            <div>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </nav>

        <h3>Manage Notices</h3>
        <?php if ($result_notices->num_rows > 0): ?>
            <table class="table table-striped notice-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_notices->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["notice_id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["title"]); ?></td>
                            <td><?php echo date("F j, Y", strtotime($row["created_at"])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="openEditNoticeModal(<?php echo $row['notice_id']; ?>, '<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['description'], ENT_QUOTES); ?>')">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDeleteNotice(<?php echo $row['notice_id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-info">No notices available.</p>
        <?php endif; ?>

        <h3>Manage Users</h3>
        <?php if ($result_users->num_rows > 0): ?>
            <table class="table table-striped user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_users->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row["user_id"]; ?></td>
                            <td><?php echo htmlspecialchars($row["username"]); ?></td>
                            <td><?php echo htmlspecialchars($row["role"]); ?></td>
                            <td><?php echo date("F j, Y", strtotime($row["created_at"])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="openEditUserModal(<?php echo $row['user_id']; ?>, '<?php echo htmlspecialchars($row['username'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($row['role'], ENT_QUOTES); ?>')">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDeleteUser(<?php echo $row['user_id']; ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-info">No users available.</p>
        <?php endif; ?>

        <div id="addNoticeModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="document.getElementById('addNoticeModal').style.display='none'">&times;</span>
                <h3>Add New Notice</h3>
                <form action="process_notice.php" method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Notice</button>
                </form>
            </div>
        </div>

        <div id="editNoticeModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="document.getElementById('editNoticeModal').style.display='none'">&times;</span>
                <h3>Edit Notice</h3>
                <form action="process_notice.php" method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="notice_id" id="edit_notice_id">
                    <div class="form-group">
                        <label for="edit_title">Title:</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">Description:</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Notice</button>
                </form>
            </div>
        </div>

        <div id="deleteNoticeModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="document.getElementById('deleteNoticeModal').style.display='none'">&times;</span>
                <h3>Confirm Delete</h3>
                <p>Are you sure you want to delete this notice?</p>
                <form action="process_notice.php" method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="notice_id" id="delete_notice_id">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('deleteNoticeModal').style.display='none'">Cancel</button>
                </form>
            </div>
        </div>

        <div id="addUserModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="document.getElementById('addUserModal').style.display='none'">&times;</span>
                <h3>Add New User</h3>
                <form action="process_user.php" method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select class="form-control" id="role" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Add User</button>
                </form>
            </div>
        </div>

        <div id="editUserModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="document.getElementById('editUserModal').style.display='none'">&times;</span>
                <h3>Edit User</h3>
                <form action="process_user.php" method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="form-group">
                        <label for="edit_username">Username:</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_role">Role:</label>
                        <select class="form-control" id="edit_role" name="role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">Update User</button>
                </form>
            </div>
        </div>

        <div id="deleteUserModal" class="modal">
            <div class="modal-content">
                <span class="close-button" onclick="document.getElementById('deleteUserModal').style.display='none'">&times;</span>
                <h3>Confirm Delete</h3>
                <p>Are you sure you want to delete this user?</p>
                <form action="process_user.php" method="POST">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="user_id" id="delete_user_id">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('deleteUserModal').style.display='none'">Cancel</button>
                </form>
            </div>
        </div>

    </div>

    <script>
        function openEditNoticeModal(id, title, description) {
            document.getElementById('editNoticeModal').style.display = 'block';
            document.getElementById('edit_notice_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
        }

        function confirmDeleteNotice(id) {
            document.getElementById('deleteNoticeModal').style.display = 'block';
            document.getElementById('delete_notice_id').value = id;
        }

        function openEditUserModal(id, username, role) {
            document.getElementById('editUserModal').style.display = 'block';
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_role').value = role;
        }

        function confirmDeleteUser(id) {
            document.getElementById('deleteUserModal').style.display = 'block';
            document.getElementById('delete_user_id').value = id;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('addNoticeModal')) {
                document.getElementById('addNoticeModal').style.display = "none";
            }
            if (event.target == document.getElementById('editNoticeModal')) {
                document.getElementById('editNoticeModal').style.display = "none";
            }
            if (event.target == document.getElementById('deleteNoticeModal')) {
                document.getElementById('deleteNoticeModal').style.display = "none";
            }
            if (event.target == document.getElementById('addUserModal')) {
                document.getElementById('addUserModal').style.display = "none";
            }
            if (event.target == document.getElementById('editUserModal')) {
                document.getElementById('editUserModal').style.display = "none";
            }
            if (event.target == document.getElementById('deleteUserModal')) {
                document.getElementById('deleteUserModal').style.display = "none";
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
