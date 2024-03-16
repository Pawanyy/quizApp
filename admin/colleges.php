<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

// Define database table name
$table = 'colleges';

// Handle form submission for creating or updating colleges
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Check for valid action
    if ($action === 'create' || $action === 'update') {
        // Check if required fields are provided
        if (isset($_POST['name'], $_POST['location'], $_POST['website'], $_POST['description'], $_POST['admission_deadline'], $_POST['cut_off_percentage'], $_POST['college_level'])) {
            $name = $_POST['name'];
            $college_id = $_POST['id'];
            $location = $_POST['location'];
            $website = $_POST['website'];
            $description = $_POST['description'];
            $admission_deadline = $_POST['admission_deadline'];
            $cut_off_percentage = $_POST['cut_off_percentage'];
            $college_level = $_POST['college_level'];

            $existingCondition = (isset($_POST['id']) && !empty($_POST['id'])) ? " AND id != {$_POST['id']}" : "";

            // Check if college already exists
            $existingCollege = $db->getSingleRow("SELECT * FROM $table WHERE name = '$name' $existingCondition");

            if ($existingCollege) {
                // College already exists
                adminMessageRedirect("Error: College already exists.", "colleges.php", false);
            } else {
                // Proceed with create or update
                $data = [
                    'name' => $name,
                    'location' => $location,
                    'website' => $website,
                    'description' => $description,
                    'admission_deadline' => $admission_deadline,
                    'cut_off_percentage' => $cut_off_percentage,
                    'college_level' => $college_level
                ];

                var_dump($data);

                if ($action === 'create') {
                    $result = $db->insert($table, $data);
                } else {
                    $condition = "id = {$_POST['id']}";
                    $result = $db->update($table, $data, $condition);
                }

                // Check if operation was successful
                if ($result) {
                    // Redirect with success message
                    adminMessageRedirect("Success: Operation completed.", "colleges.php", true);
                } else {
                    // Redirect with failure message
                    adminMessageRedirect("Error: Operation failed.", "colleges.php", false);
                }
            }
        } else {
            // Redirect with failure message
            adminMessageRedirect("Error: All fields must be provided.", "colleges.php", false);
        }
    } elseif ($action === 'fetch' || $action === 'fetchColleges') {
        // Fetch Data
        $colleges = $db->getMultipleRows("SELECT * FROM $table");

        $json_data = [];
        foreach ($colleges as $college) {
            if ($action === 'fetch') {
                $json_data[] = [
                    $college['name'],
                    $college['location'],
                    $college['website'],
                    $college['description'],
                    $college['admission_deadline'],
                    $college['cut_off_percentage'],
                    $college['college_level'],
                    "<button class='btn btn-primary btn-sm edit-college' data-id='{$college['id']}' data-name='{$college['name']}' data-location='{$college['location']}' data-website='{$college['website']}' data-description='{$college['description']}' data-admission_deadline='{$college['admission_deadline']}' data-cut_off_percentage='{$college['cut_off_percentage']}' data-college_level='{$college['college_level']}'>Edit</button>
                    <button class='btn btn-danger btn-sm delete-college' data-id='{$college['id']}'>Delete</button>" // Delete button
                ];
            } elseif ($action === 'fetchColleges') {
                $json_data[] = [
                    'id' => $college['id'],
                    'name' => $college['name'],
                    'location' => $college['location'],
                    'website' => $college['website'],
                    'description' => $college['description'],
                    'admission_deadline' => $college['admission_deadline'],
                    'cut_off_percentage' => $college['cut_off_percentage'],
                    'college_level' => $college['college_level']
                ];
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $json_data]);
        exit;
    } elseif ($action === 'delete') {
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
                    <h3 class="card-title">Colleges</h3>
                    <button class="btn btn-primary float-right" id="addCollegeButton">Add College</button>
                </div>
                <div class="card-body">
                    <table id="collegesTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Website</th>
                                <th>Description</th>
                                <th>Admission Deadline</th>
                                <th>Cut Off Percentage</th>
                                <th>College Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch colleges from the database
                            $colleges = $db->getMultipleRows("SELECT * FROM $table");
                            foreach ($colleges as $college) {
                                echo "<tr data-id='{$college['id']}'>";
                                echo "<td>{$college['name']}</td>";
                                echo "<td>{$college['location']}</td>";
                                echo "<td>{$college['website']}</td>";
                                echo "<td>{$college['description']}</td>";
                                echo "<td>{$college['admission_deadline']}</td>";
                                echo "<td>{$college['cut_off_percentage']}</td>";
                                echo "<td>{$college['college_level']}</td>";
                                echo "<td>
                                        <button class='btn btn-primary btn-sm edit-college' data-id='{$college['id']}' data-name='{$college['name']}' data-location='{$college['location']}' data-website='{$college['website']}' data-description='{$college['description']}' data-admission_deadline='{$college['admission_deadline']}' data-cut_off_percentage='{$college['cut_off_percentage']}' data-college_level='{$college['college_level']}'>Edit</button>
                                        <button class='btn btn-danger btn-sm delete-college' data-id='{$college['id']}'>Delete</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End DataTable -->

            <!-- College Form Modal -->
            <div class="modal fade" id="collegeFormModal" tabindex="-1" role="dialog"
                aria-labelledby="collegeFormModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="collegeFormModalLabel">Add/Edit College</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="collegeFormInner" method="post">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" minlength="2"
                                        maxlength="20" required>
                                </div>
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" minlength="2"
                                        maxlength="25">
                                </div>
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="text" class="form-control" id="website" name="website" minlength="2"
                                        maxlength="25">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        minlength="2" maxlength="100" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="admissionDeadline">Admission Deadline</label>
                                    <input type="date" class="form-control" id="admissionDeadline"
                                        name="admission_deadline">
                                </div>
                                <div class="form-group">
                                    <label for="cutOffPercentage">Cut Off Percentage</label>
                                    <input type="number" class="form-control" id="cutOffPercentage"
                                        name="cut_off_percentage" min="0" max="100" step="0.01" required>
                                </div>
                                <div class="form-group">
                                    <label for="collegeLevel">College Level</label>
                                    <select class="form-control" id="collegeLevel" name="college_level" required>
                                        <option value="elementary">Elementary</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="top">Top</option>
                                    </select>
                                </div>
                                <input type="hidden" id="collegeId" name="id">
                                <input type="hidden" id="action" name="action">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" form="collegeFormInner" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- End College Form -->
        </div>
    </section>
</div>
<!-- End Content Wrapper -->

<?php include __DIR__ . "/include/footer.php";  ?>

<script>
$(document).ready(function() {
    var table = $('#collegesTable').DataTable();

    // Show college form modal for editing or creating college
    $('#collegesTable').on('click', '.edit-college', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var location = $(this).data('location');
        var website = $(this).data('website');
        var description = $(this).data('description');
        var admissionDeadline = $(this).data('admission_deadline');
        var cutOffPercentage = $(this).data('cut_off_percentage');
        var collegeLevel = $(this).data('college_level');

        $('#name').val(name);
        $('#location').val(location);
        $('#website').val(website);
        $('#description').val(description);
        $('#admissionDeadline').val(admissionDeadline);
        $('#cutOffPercentage').val(cutOffPercentage);
        $('#collegeLevel').val(collegeLevel);
        $('#collegeId').val(id);
        $('#action').val('update');
        $('#collegeFormModalLabel').text('Edit College');
        $('#collegeFormModal').modal('show');
    });

    // Show college form modal for creating new college
    $('#addCollegeButton').click(function() {
        $('#name').val('');
        $('#location').val('');
        $('#website').val('');
        $('#description').val('');
        $('#admissionDeadline').val('');
        $('#cutOffPercentage').val('');
        $('#collegeLevel').val('');
        $('#collegeId').val('');
        $('#action').val('create');
        $('#collegeFormModalLabel').text('Add College');
        $('#collegeFormModal').modal('show');
    });

    // Function to fetch colleges
    function fetchColleges() {
        $.ajax({
            url: 'colleges.php',
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
                    alert('Failed to fetch colleges');
                }
            },
            error: function() {
                alert('Failed to fetch colleges');
            }
        });
    }

    // Handle delete college
    $('#collegesTable').on('click', '.delete-college', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this college?')) {
            $.ajax({
                url: 'colleges.php',
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'delete',
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        // Reload DataTable after successful deletion
                        fetchColleges();
                        // Show success alert
                        $('#successAlert').removeClass('d-none');
                    } else {
                        alert('Failed to delete college');
                    }
                },
                error: function() {
                    alert('Failed to delete college');
                }
            });
        }
    });

    // Cancel form submission
    $('#cancelButton').click(function() {
        $('#collegeForm').addClass('d-none');
    });
});
</script>