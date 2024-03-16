<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

// Define database table name
$table = 'difficulty_levels';

// Handle form submission for creating or updating difficulty levels
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $diff_id = isset($_POST['id']) ? $_POST['id'] : 0;

        // Check for valid action
        if ($action === 'create' || $action === 'update') {
            // Check if name and level are provided
            if (isset($_POST['name']) && isset($_POST['level'])) {
                $name = $_POST['name'];
                $level = $_POST['level'];
                
                $existingCondition = (isset($_POST['id']) && !empty($_POST['id'])) ? " AND id != {$_POST['id']}" : "";

                // Check for existing level and name
                $existingLevel = $db->getSingleRow("SELECT * FROM $table WHERE level = '$level' $existingCondition");
                $existingName = $db->getSingleRow("SELECT * FROM $table WHERE name = '$name' $existingCondition");

                if (($existingLevel || $existingName)) {
                    // Level or name already exists
                    adminMessageRedirect("Error: Level or name already exists.", "difficulty_levels.php", false);
                } else {
                    // Proceed with create or update
                    $data = [
                        'name' => $name,
                        'level' => $level
                    ];

                    if ($action === 'create') {
                        $result = $db->insert($table, $data);
                    } else {
                        $condition = "id = {$_POST['id']}";
                        $result = $db->update($table, $data, $condition);
                    }

                    // Check if operation was successful
                    if ($result) {
                        // Redirect with success message
                        adminMessageRedirect("Success: Operation completed.", "difficulty_levels.php", true);
                    } else {
                        // Redirect with failure message
                        adminMessageRedirect("Error: Operation failed.", "difficulty_levels.php", false);
                    }
                }
            } else {
                // Redirect with failure message
                adminMessageRedirect("Error: Name and level must be provided.", "difficulty_levels.php", false);
            }
        } elseif ($action === 'fetch') {
            // Fetch Data
            $difficulty_levels = $db->getMultipleRows("SELECT * FROM $table ORDER BY level ASC");

            $json_data = [];
            foreach ($difficulty_levels as $difficulty_level) {
                $json_data[] = [
                    $difficulty_level['name'],
                    $difficulty_level['level'],
                    "<button class='btn btn-primary btn-sm edit-difficulty-level' data-id='{$difficulty_level['id']}' data-name='{$difficulty_level['name']}' data-level='{$difficulty_level['level']}'>Edit</button>
                    <button class='btn btn-danger btn-sm delete-difficulty-level' data-id='{$difficulty_level['id']}'>Delete</button>" // Delete button
                ];
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $json_data]);
            exit;
        } elseif ($action === 'delete') {
            $condition = "id = {$_POST['id']}";

            try {

                $result = $db->delete($table, $condition);

                // Return JSON response for AJAX request
                if ($result) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit;
                } else {
                    throw new Error('Failed');
                }
                
            } catch (mysqli_sql_exception $e) {
                // Handle MySQL exceptions
                if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
                    // Foreign key constraint violation
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Cannot delete the record. It is associated with other data.']);
                    exit;
                } else {
                    throw new Error('Failed');
                }
            } catch (Exception $e) {
                // Handle other types of exceptions
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'An error occurred while processing the operation.']);
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
                    <h3 class="card-title">Difficulty Levels</h3>
                    <button class="btn btn-primary float-right" id="addDifficultyLevelButton">Add Difficulty
                        Level</button>
                </div>
                <div class="card-body">
                    <table id="difficultyLevelsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch difficulty levels from the database
                            $difficulty_levels = $db->getMultipleRows("SELECT * FROM $table ORDER BY level ASC");
                            foreach ($difficulty_levels as $difficulty_level) {
                                echo "<tr data-id='{$difficulty_level['id']}'>";
                                echo "<td>{$difficulty_level['name']}</td>";
                                echo "<td>{$difficulty_level['level']}</td>";
                                echo "<td>
                                        <button class='btn btn-primary btn-sm edit-difficulty-level' data-id='{$difficulty_level['id']}' data-name='{$difficulty_level['name']}' data-level='{$difficulty_level['level']}'>Edit</button>
                                        <button class='btn btn-danger btn-sm delete-difficulty-level' data-id='{$difficulty_level['id']}'>Delete</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End DataTable -->

            <!-- Difficulty Level Form Modal -->
            <div class="modal fade" id="difficultyLevelFormModal" tabindex="-1" role="dialog"
                aria-labelledby="difficultyLevelFormModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="difficultyLevelFormModalLabel">Add/Edit Difficulty Level</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="difficultyLevelFormInner" method="post">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required minlength="2"
                                        maxlength="20">
                                </div>
                                <div class="form-group">
                                    <label for="level">Level</label>
                                    <input type="number" class="form-control" id="level" name="level" required min="1"
                                        max="10" step="1">
                                </div>
                                <input type="hidden" id="difficultyLevelId" name="id">
                                <input type="hidden" id="action" name="action">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" form="difficultyLevelFormInner" class="btn btn-primary">Save
                                changes</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Difficulty Level Form Modal -->
        </div>
    </section>
</div>
<!-- End Content Wrapper -->

<?php include __DIR__ . "/include/footer.php";  ?>

<script>
$(document).ready(function() {
    var table = $('#difficultyLevelsTable').DataTable();

    // Show difficulty level form modal for editing or creating difficulty level
    $('#difficultyLevelsTable').on('click', '.edit-difficulty-level', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var level = $(this).data('level');

        $('#name').val(name);
        $('#level').val(level);
        $('#difficultyLevelId').val(id);
        $('#action').val('update');
        $('#difficultyLevelFormModalLabel').text('Edit Difficulty Level');
        $('#difficultyLevelFormModal').modal('show');
    });

    // Show difficulty level form modal for creating new difficulty level
    $('#addDifficultyLevelButton').click(function() {
        $('#name').val('');
        $('#level').val('');
        $('#difficultyLevelId').val('');
        $('#action').val('create');
        $('#difficultyLevelFormModalLabel').text('Add Difficulty Level');
        $('#difficultyLevelFormModal').modal('show');
    });

    // Function to fetch difficulty levels
    function fetchDifficultyLevels() {
        $.ajax({
            url: 'difficulty_levels.php',
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
                    alert('Failed to fetch difficulty levels');
                }
            },
            error: function() {
                alert('Failed to fetch difficulty levels');
            }
        });
    }

    // Handle delete difficulty level
    $('#difficultyLevelsTable').on('click', '.delete-difficulty-level', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this difficulty level?')) {
            $.ajax({
                url: 'difficulty_levels.php',
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'delete',
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        // Reload DataTable after successful deletion
                        fetchDifficultyLevels();
                        // Show success alert
                        $('#successAlert').removeClass('d-none');
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Failed to delete difficulty level');
                }
            });
        }
    });

    // Cancel form submission
    $('#cancelButton').click(function() {
        $('#difficultyLevelForm').addClass('d-none');
    });
});
</script>