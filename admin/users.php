<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

// Define database table name
$table = 'users';

// Handle form submission for creating or updating users
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Create or update user
        if ($action === 'create' || $action === 'update') {
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'full_name' => $_POST['full_name'],
                'userType' => $_POST['userType'],
            ];

            // Check if password is provided and not empty for creation
            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            // Check if username exists
            $existing_username = $db->getSingleRow("SELECT id FROM $table WHERE username = '{$_POST['username']}'");
            if ($existing_username && ($action === 'create' || ($action === 'update' && $existing_username['id'] != $_POST['id']))) {
                // Handle error - Username already exists
                adminMessageRedirect("Username already exists.", "users.php", false);
                exit;
            }

            // Check if email exists
            $existing_email = $db->getSingleRow("SELECT id FROM $table WHERE email = '{$_POST['email']}'");
            if ($existing_email && ($action === 'create' || ($action === 'update' && $existing_email['id'] != $_POST['id']))) {
                // Handle error - Email already exists
                adminMessageRedirect("Email already exists.", "users.php", false);
                exit;
            }

            // Insert or update user based on action
            if ($action === 'create') {
                // Check if password is provided for creation
                if (!isset($data['password'])) {
                    // Handle error - Password is required for creation
                    adminMessageRedirect("Password is required for creation.", "users.php", false);
                    exit;
                }
                $result = $db->insert($table, $data);
            } else {
                $condition = "id = {$_POST['id']}";
                $result = $db->update($table, $data, $condition);
            }

            // Check if operation was successful
            if ($result) {
                // Redirect to refresh the page
                adminMessageRedirect("User ".($action === 'create' ? "Created" : "Updated")." Successfully.", "users.php");
                exit;
            } else {
                // Handle error
                adminMessageRedirect("Failed to $action user", "users.php", false);
            }
        }

        // Fetch users
        else if ($action === 'fetch') {
            // Fetch Data
            $users = $db->getMultipleRows("SELECT users.* FROM $table users WHERE users.userType = 'STUDENT'");

            $json_data = [];
            foreach ($users as $user) {
                $json_data[] = [
                    $user['username'],
                    $user['email'],
                    $user['full_name'],
                    $user['userType'],
                    $user['registration_date'],
                    "<button class='btn btn-primary btn-sm edit-user' data-id='{$user['id']}' data-username='{$user['username']}' data-email='{$user['email']}' data-full_name='{$user['full_name']}' data-userType='{$user['userType']}'>Edit</button>
                    <button class='btn btn-danger btn-sm delete-user' data-id='{$user['id']}'>Delete</button>" // Delete button
                ];
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $json_data]);
            exit;
        }

        // Delete user
        elseif ($action === 'delete') {
            $condition = "id = {$_POST['id']}";
            $result = $db->delete($table, $condition);

            // Return JSON response for AJAX request
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false]);
                exit;
            }
        }
    }
}
?>

<?php include __DIR__ . "/include/header.php";  ?>
<?php include __DIR__ . "/include/navbar.php";  ?>
<?php include __DIR__ . "/include/sidebar.php";  ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="container-fluid">
            <!-- DataTable -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Students</h3>
                    <button class="btn btn-primary float-right" id="addUserButton">Add Student</button>
                </div>
                <div class="card-body">
                    <table id="usersTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Full Name</th>
                                <th>User Type</th>
                                <th>Registration Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch users from the database
                            $users = $db->getMultipleRows("SELECT users.* FROM $table users WHERE users.userType = 'STUDENT'");

                            foreach ($users as $user) {
                                echo "<tr data-id='{$user['id']}'>";
                                echo "<td>{$user['username']}</td>";
                                echo "<td>{$user['email']}</td>";
                                echo "<td>{$user['full_name']}</td>";
                                echo "<td>{$user['userType']}</td>";
                                echo "<td>{$user['registration_date']}</td>";
                                echo "<td>
                                        <button class='btn btn-primary btn-sm edit-user' data-id='{$user['id']}' data-username='{$user['username']}' data-email='{$user['email']}' data-full_name='{$user['full_name']}' data-userType='{$user['userType']}'>Edit</button>
                                        <button class='btn btn-danger btn-sm delete-user' data-id='{$user['id']}'>Delete</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End DataTable -->

            <!-- User Form Modal -->
            <div class="modal fade" id="userFormModal" tabindex="-1" role="dialog" aria-labelledby="userFormModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="userFormModalLabel">Add/Edit Student</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="userFormInner" method="post">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="form-group">
                                    <label for="full_name">Full Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="userType">User Type</label>
                                    <select class="form-control" id="userType" name="userType">
                                        <option value="STUDENT" selected>Student</option>
                                    </select>
                                </div>
                                <input type="hidden" id="userId" name="id">
                                <input type="hidden" id="action" name="action">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" form="userFormInner" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End User Form Modal -->
        </div>
    </section>
</div>
<!-- End Content Wrapper -->

<?php include __DIR__ . "/include/footer.php";  ?>

<script>
$(document).ready(function() {
    var table = $('#usersTable').DataTable();


    // Show user form modal for editing or creating user
    $('#usersTable').on('click', '.edit-user', function() {

        var id = $(this).data('id');
        var username = $(this).data('username');
        var email = $(this).data('email');
        var full_name = $(this).data('full_name');
        var userType = $(this).data('usertype');

        $('#password').prop('required', false);

        $('#username').val(username);
        $('#email').val(email);
        $('#full_name').val(full_name);
        $('#userType').val('STUDENT');
        $('#userId').val(id);
        $('#action').val('update');
        $('#userFormModalLabel').text('Edit Student');
        $('#userFormModal').modal('show');
    });

    // Show user form modal for creating new user
    $('#addUserButton').click(function() {
        fetchColleges();
        $('#username').val('');
        $('#email').val('');
        $('#password').val('');
        $('#full_name').val('');
        $('#userType').val('STUDENT');
        $('#password').prop('required', true);
        $('#userId').val('');
        $('#action').val('create');
        $('#userFormModalLabel').text('Add Student');
        $('#userFormModal').modal('show');
    });

    // Function to fetch users
    function fetchUsers() {
        $.ajax({
            url: 'users.php',
            type: 'post',
            dataType: 'json',
            data: {
                action: 'fetch'
            },
            success: function(response) {
                if (response.success) {
                    // Clear existing table rows
                    table.clear().rows.add(response.data).draw();
                } else {
                    alert('Failed to fetch users');
                }
            },
            error: function() {
                alert('Failed to fetch users');
            }
        });
    }

    // Handle delete user
    $('#usersTable').on('click', '.delete-user', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: 'users.php',
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'delete',
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        // Reload DataTable after successful deletion
                        fetchUsers();
                        // Show success alert
                        $('#successAlert').removeClass('d-none');
                    } else {
                        alert('Failed to delete user');
                    }
                },
                error: function() {
                    alert('Failed to delete user');
                }
            });
        }
    });

    // Cancel form submission
    $('#cancelButton').click(function() {
        $('#userForm').addClass('d-none');
    });
});
</script>