<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$table = "quiz_types"; // Assuming this is your table name
$quizTypes = $db->getMultipleRows("SELECT * FROM $table ORDER BY id ASC");

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
            adminMessageRedirect("Success: Operation completed.", "quiz_types.php", true);
        } else {
            // Redirect with failure message
            adminMessageRedirect("Error: Operation failed.", "quiz_types.php", false);
        }
    } catch (mysqli_sql_exception $e) {
        // Handle MySQL exceptions
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            // Foreign key constraint violation
            adminMessageRedirect("Error: Cannot delete the record. It is associated with other data.", "quiz_types.php", false);
        } else {
            // Other types of exceptions
            adminMessageRedirect("Error: An error occurred while processing the operation.", "quiz_types.php", false);
        }
    } catch (Exception $e) {
        // Handle other types of exceptions
        adminMessageRedirect("Error: An error occurred while processing the operation.", "quiz_types.php", false);
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
                            <h3 class="card-title">Quiz Types</h3>
                            <a class="btn btn-primary float-right" href="/admin/quiz_types_add.php">Add Quiz Type</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-wrapper">
                                <table id="dataTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type Name</th>
                                            <th>Description</th>
                                            <th>Time Limit (minutes)</th>
                                            <th>Is Randomized</th>
                                            <th>Show Correct Answers</th>
                                            <th>Pass Percentage</th>
                                            <th>Attempts Limit</th>
                                            <th>Shuffle Options</th>
                                            <th>Penalty for Wrong Answer</th>
                                            <th>Points per Question</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($quizTypes as $quizType): ?>
                                        <tr>
                                            <td><?= $quizType['id'] ?></td>
                                            <td><?= $quizType['type_name'] ?></td>
                                            <td><?= $quizType['description'] ?></td>
                                            <td><?= $quizType['time_limit_minutes'] ?></td>
                                            <td><?= $quizType['is_randomized'] ? 'Yes' : 'No' ?></td>
                                            <td><?= $quizType['show_correct_answers'] ? 'Yes' : 'No' ?></td>
                                            <td><?= $quizType['pass_percentage'] ?></td>
                                            <td><?= $quizType['attempts_limit'] ?></td>
                                            <td><?= $quizType['shuffle_options'] ? 'Yes' : 'No' ?></td>
                                            <td><?= $quizType['penalty_for_wrong_answer'] ? 'Yes' : 'No' ?></td>
                                            <td><?= $quizType['points_per_question'] ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-sm ml-2"
                                                    href="/admin/quiz_types_add.php?edit_id=<?= $quizType['id'] ?>">Edit</a>
                                                <a href="quiz_types.php?id=<?= $quizType['id'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this quiz type?')">Delete</a>
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