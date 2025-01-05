<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$table = "doctors"; // Assuming this is your table name
$doctors = $db->getMultipleRows("SELECT * FROM $table ORDER BY id ASC");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Build condition for deletion
    $condition = "id = $id";

    try {

        // Perform deletion operation
        $result = $db->delete($table, $condition);

        // Check if deletion was successful
        if ($result) {
            // Redirect with success message
            adminMessageRedirect("Success: Operation completed.", "doctors.php", true);
        } else {
            // Redirect with failure message
            adminMessageRedirect("Error: Operation failed.", "doctors.php", false);
        }
    } catch (mysqli_sql_exception $e) {
        // Handle MySQL exceptions
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            // Foreign key constraint violation
            adminMessageRedirect("Error: Cannot delete the record. It is associated with other data.", "doctors.php", false);
        } else {
            // Other types of exceptions
            adminMessageRedirect("Error: An error occurred while processing the operation.", "doctors.php", false);
        }
    } catch (Exception $e) {
        // Handle other types of exceptions
        adminMessageRedirect("Error: An error occurred while processing the operation.", "doctors.php", false);
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
                            <h3 class="card-title">Doctors</h3>
                            <a class="btn btn-primary float-right" href="/admin/doctors_add.php">Add Doctor</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-wrapper">
                                <table id="dataTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Specialist</th>
                                            <th>Contact</th>
                                            <th>Location</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($doctors as $doctor): ?>
                                        <tr>
                                            <td><?= $doctor['id'] ?></td>
                                            <td><?= $doctor['name'] ?></td>
                                            <td><?= $doctor['specialist'] ?></td>
                                            <td><?= $doctor['contact'] ?></td>
                                            <td><?= $doctor['location'] ?></td>
                                            <td><?= $doctor['description'] ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-sm ml-2"
                                                    href="/admin/doctors_add.php?edit_id=<?= $doctor['id'] ?>">Edit</a>
                                                <a href="doctors.php?id=<?= $doctor['id'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
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
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>