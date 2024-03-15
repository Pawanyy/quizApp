<?php
include_once dirname(__DIR__). '/bootstrap.php';

// Fetch quiz attempts with additional information
$quiz_attempts = $db->getMultipleRows("SELECT qa.*, q.name AS quiz_name, qt.time_limit_minutes, c.name AS category_name,
                                        u.username, u.email, u.full_name
                                        FROM quiz_attempts qa
                                        LEFT JOIN quizzes q ON qa.quiz_id = q.id
                                        LEFT JOIN quiz_types qt ON q.quiz_type_id = qt.id
                                        LEFT JOIN categories c ON q.category_id = c.id
                                        LEFT JOIN users u ON qa.user_id = u.id");

?>
<?php include __DIR__ . "/include/header.php";  ?>
<?php include __DIR__ . "/include/navbar.php";  ?>
<?php include __DIR__ . "/include/sidebar.php";  ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- Your page content here -->
        <div class="container-fluid pt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Quiz Attempts</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="dataTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>SR</th>
                                            <th>User Info</th>
                                            <th>Quiz Name</th>
                                            <th>Time Limit (minutes)</th>
                                            <th>Category</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Score</th>
                                            <th>Total Score</th>
                                            <th>Penalty Points</th>
                                            <th>Total Questions</th>
                                            <th>Correct Answers</th>
                                            <th>Incorrect Answers</th>
                                            <th>Passed</th>
                                            <th>Pass Percentage</th>
                                            <th>Percentage</th>
                                            <th>Submission Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $serial = 1; ?>
                                        <?php foreach ($quiz_attempts as $attempt): ?>
                                        <tr>
                                            <td><?php echo $serial++; ?></td>
                                            <td>
                                                <strong>UserName:</strong> <?php echo $attempt['username']; ?>
                                                <br>
                                                <strong>Email:</strong> <?php echo $attempt['email']; ?>
                                                <br>
                                                <strong>FullName:</strong> <?php echo $attempt['full_name']; ?>
                                            </td>
                                            <td><?php echo $attempt['quiz_name']; ?></td>
                                            <td><?php echo $attempt['time_limit_minutes']; ?></td>
                                            <td><?php echo $attempt['category_name']; ?></td>
                                            <td><?php echo $attempt['start_time']; ?></td>
                                            <td><?php echo $attempt['end_time']; ?></td>
                                            <td><?php echo $attempt['score']; ?></td>
                                            <td><?php echo $attempt['total_score']; ?></td>
                                            <td><?php echo $attempt['penalty_points']; ?></td>
                                            <td><?php echo $attempt['total_questions']; ?></td>
                                            <td><?php echo $attempt['correct_answers']; ?></td>
                                            <td><?php echo $attempt['incorrect_answers']; ?></td>
                                            <td><?php echo $attempt['passed'] == null ? "NA" :($attempt['passed'] ? "Yes" : "No"); ?>
                                            </td>
                                            <td><?php echo $attempt['pass_percentage']; ?></td>
                                            <td><?php echo $attempt['percentage']; ?></td>
                                            <td><?php echo $attempt['submission_date']; ?></td>
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
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Include necessary scripts -->
<?php include __DIR__ . "/include/footer.php";  ?>
<style>
tbody td {
    max-width: 200px;
    /* Set your desired maximum width */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>