<?php 
include_once 'bootstrap.php';

if(!isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && $_SESSION['type'] != "STUDENT")){
    setMessageRedirect("Pls Login First!", "login.php", false);
}

$table = "users";
// Fetch user's current profile information
$user_id = $_SESSION['user_id'];
$user = $db->getSingleRow("SELECT * FROM $table WHERE id = $user_id");

// Fetch quiz attempts with additional information
$quiz_attempts = $db->getMultipleRows("SELECT qa.*, q.name AS quiz_name, qt.time_limit_minutes, c.name AS category_name
                                        FROM quiz_attempts qa
                                        LEFT JOIN quizzes q ON qa.quiz_id = q.id
                                        LEFT JOIN quiz_types qt ON q.quiz_type_id = qt.id
                                        LEFT JOIN categories c ON q.category_id = c.id
                                        WHERE qa.user_id = $user_id");

include_once 'includes/header.php'; 
?>
<div class="container mb-5">
    <div class="row">

        <?php include_once 'includes/sidebar.php';  ?>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Quiz Attempts</h2>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>SR</th>
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
                                            <a href="<?php echo BASE_URL . "/quiz_result.php?attempt_id=" . $attempt["id"] ?>">
                                                <?php echo $attempt['quiz_name']; ?>
                                            </a>
                                        </td>
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
                </div>
            </div>
        </div>
    </div>
</div>
<style>
tbody td {
    max-width: 200px;
    /* Set your desired maximum width */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<?php include_once 'includes/footer.php'; ?>