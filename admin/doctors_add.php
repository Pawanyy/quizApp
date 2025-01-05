<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$table = "doctors"; // Assuming this is your table name

// Initialize variables for form fields
$name = '';
$specialist = '';
$contact = '';
$location = '';
$description = '';

// Check if editing an existing quiz type
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Fetch existing quiz type data from the database and populate form fields
    $existing_doctor = $db->getSingleRow("SELECT * FROM $table WHERE id = $edit_id");
    if ($existing_doctor) {
        $name = $existing_doctor['name'];
        $specialist = $existing_doctor['specialist'];
        $contact = $existing_doctor['contact'];
        $location = $existing_doctor['location'];
        $description = $existing_doctor['description'];
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $specialist = $_POST["specialist"];
    $contact = $_POST["contact"];
    $location = $_POST["location"];
    $description = $_POST["description"];


    $existingCondition = (isset($_POST['id']) && !empty($_POST['id'])) ? " AND id != {$_POST['id']}" : "";

    // Check for existing name
    $existingName = $db->getSingleRow("SELECT * FROM $table WHERE name = '$name' $existingCondition");

    if (($existingName)) {
        // Level or name already exists
        adminMessageRedirect("Error: Doctor Name already exists.", "doctors.php", false);
    }
    
    // Proceed with create or update
    $data = [
        'name' => $name,
        'specialist' => $specialist,
        'contact' => $contact,
        'location' => $location,
        'description' => $description,
    ];

    if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
        // Insert data into database
        $condition = "id = {$_GET['edit_id']}";
        $result = $db->update($table, $data, $condition);
    } else {
        $result = $db->insert($table, $data);
    }
    // Check if insertion was successful
    if ($result) {
        // Redirect with success message
        adminMessageRedirect("Success: Doctor added.", "doctors.php", true);
    } else {
        // Redirect with failure message
        adminMessageRedirect("Error: Failed to add doctor.", "doctors_add.php", false);
    }
}
?>

<?php include __DIR__ . "/include/header.php";  ?>
<?php include __DIR__ . "/include/navbar.php";  ?>
<?php include __DIR__ . "/include/sidebar.php";  ?>
<style>
.table-wrapper {
    overflow-x: auto;
}

.table-wrapper table {
    width: 100%;
    border-collapse: collapse;
}

.table-wrapper th,
.table-wrapper td {
    padding: 8px;
    text-align: left;
    border: 1px solid #dddddd;
}

.table-wrapper th {
    background-color: #f2f2f2;
}
</style>
<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row pt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <?php echo isset($_GET['edit_id']) ? 'Edit Doctor' : 'Add Doctor'; ?></h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form
                                action="doctors_add.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>"
                                method="POST">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo isset($existing_doctor) ? $existing_doctor['name'] : ''; ?>"
                                        minlength="2" maxlength="20" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        minlength="2" maxlength="100"
                                        required><?php echo isset($existing_doctor) ? $existing_doctor['description'] : ''; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact:</label>
                                    <input type="number" class="form-control" id="contact" name="contact" min="1000000000" max="9999999999" step="1"
                                        value="<?php echo isset($existing_doctor) ? $existing_doctor['contact'] : ''; ?>"
                                        required>
                                        <span class="text-muted">Contact must be 10 digits long.</span>
                                </div>
                                <div class="form-group">
                                    <label for="location">Location:</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                        value="<?php echo isset($existing_doctor) ? $existing_doctor['location'] : ''; ?>"
                                        minlength="2" maxlength="20" required>
                                </div>
                                <div class="form-group">
                                    <label for="specialist">Specialist:</label>
                                    <input type="text" class="form-control" id="specialist" name="specialist"
                                        value="<?php echo isset($existing_doctor) ? $existing_doctor['specialist'] : ''; ?>"
                                        minlength="2" maxlength="20" required>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary"><?php echo isset($_GET['edit_id']) ? 'Update Doctor' : 'Add Doctor'; ?></button>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php include __DIR__ . "/include/footer.php";  ?>