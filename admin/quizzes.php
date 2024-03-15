<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$table = "quizzes"; // Assuming this is your table name

$quizzes = $db->getMultipleRows("SELECT 
                                    q.id AS quiz_id,
                                    q.name AS quiz_name,
                                    q.description AS quiz_description,
                                    q.created_at AS quiz_created_at,
                                    c.id AS category_id,
                                    c.name AS category_name,
                                    qt.id AS quiz_type_id,
                                    qt.type_name AS quiz_type_name,
                                    qt.description AS quiz_type_description,
                                    qt.time_limit_minutes,
                                    qt.is_randomized,
                                    qt.show_correct_answers,
                                    qt.pass_percentage,
                                    qt.attempts_limit,
                                    qt.shuffle_options,
                                    qt.penalty_for_wrong_answer,
                                    qt.points_per_question,
                                    COUNT(DISTINCT qst.id) AS total_questions
                                FROM 
                                    quizzes q
                                JOIN 
                                    categories c ON q.category_id = c.id
                                LEFT JOIN
                                    questions qst ON q.id = qst.quiz_id
                                JOIN 
                                    quiz_types qt ON q.quiz_type_id = qt.id
                                GROUP BY
                                    q.id;");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Build condition for deletion
    $condition = "id = $id";

    // Perform deletion operation
    $result = $db->delete($table, $condition);

    // Check if deletion was successful
    if ($result) {
        // Redirect with success message
        adminMessageRedirect("Success: Operation completed.", "quizzes.php", true);
    } else {
        // Redirect with failure message
        adminMessageRedirect("Error: Operation failed.", "quizzes.php", false);
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
                            <h3 class="card-title">quizzes</h3>
                            <a class="btn btn-primary float-right" href="/admin/quizzes_add.php">Add quizzes</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-wrapper">
                                <table id="quizzesTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Quiz Type</th>
                                            <th>Description</th>
                                            <th>Total Questions</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($quizzes as $quiz): ?>
                                        <tr>
                                            <td><?= $quiz['quiz_id'] ?></td>
                                            <td><?= $quiz['quiz_name'] ?></td>
                                            <td><?= $quiz['category_name'] ?></td>
                                            <td><?= $quiz['quiz_type_name'] ?></td>
                                            <td><?= $quiz['quiz_description']?></td>
                                            <td><?= $quiz['total_questions']?></td>
                                            <td><?= $quiz['quiz_created_at']?></td>
                                            <td>
                                                <a class="btn btn-primary btn-sm ml-2"
                                                    href="/admin/questions.php?quiz_id=<?= $quiz['quiz_id'] ?>">Questions</a>
                                                <a class="btn btn-primary btn-sm ml-2"
                                                    href="/admin/quizzes_add.php?edit_id=<?= $quiz['quiz_id'] ?>">Edit</a>
                                                <a href="quizzes.php?id=<?= $quiz['quiz_id'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</a>
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