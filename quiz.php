<?php
// Include the database connection and necessary functions
include_once __DIR__. '/bootstrap.php';

// Fetch quizzes from the database with additional information
$quizzes = $db->getMultipleRows("SELECT q.id AS quiz_id, q.name AS quiz_name, q.description AS quiz_description, c.name AS category_name, t.type_name AS quiz_type_name, t.time_limit_minutes, 
                                (SELECT COUNT(*) FROM questions WHERE quiz_id = q.id) AS total_questions
                                FROM quizzes q
                                JOIN categories c ON q.category_id = c.id
                                JOIN quiz_types t ON q.quiz_type_id = t.id");

include_once 'includes/header.php';
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row pt-3">
            <div class="col-12">
                <h2 class="section-header">Available Quizzes</h2>
            </div>
        </div>
        <div class="row pt-3">
            <?php foreach ($quizzes as $quiz): ?>
            <div class="col-12 col-md-4 mb-4">
                <!-- Quiz Card -->
                <div class="card h-100 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= $quiz['quiz_name'] ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?= $quiz['quiz_description'] ?></p>
                        <ul class="list-group list-group-flush rounded border">
                            <li class="list-group-item"><strong>Category:</strong> <?= $quiz['category_name'] ?></li>
                            <li class="list-group-item"><strong>Type:</strong> <?= $quiz['quiz_type_name'] ?></li>
                            <li class="list-group-item"><strong>Time Limit:</strong> <?= $quiz['time_limit_minutes'] ?>
                                minutes</li>
                            <li class="list-group-item"><strong>Total Questions:</strong>
                                <?= $quiz['total_questions'] ?></li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <?php if (isset($_SESSION['user_id'])):
                        // Check if the quiz attempt is ongoing and has not ended yet
                        $quiz_attempt_ongoing = $db->getSingleRow("SELECT * FROM quiz_attempts WHERE quiz_id = {$quiz['quiz_id']} AND user_id = {$_SESSION['user_id']} AND end_time IS NULL");
                        ?>
                        <?php if ($quiz_attempt_ongoing): ?>
                        <a href="<?=BASE_URL?>/quiz_page.php?quiz_id=<?= $quiz['quiz_id']?>&attempt_uuid=<?=$quiz_attempt_ongoing['attempt_uuid']?>"
                            class="btn btn-warning btn-block">Continue
                            Quiz</a>
                        <?php else: ?>
                        <a href="<?=BASE_URL?>/quiz_take.php?quiz_id=<?= $quiz['quiz_id'] ?>"
                            class="btn btn-primary btn-block">Start
                            Quiz</a>
                        <?php endif;?>
                        <?php else:?>
                        <a href="<?=BASE_URL?>/login.php" class="btn btn-outline-info btn-block text-center">Login
                            First</a>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- End Quiz Card -->
            </div>
            <?php endforeach; ?>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php include_once 'includes/footer.php'; ?>