<?php
// Include the database connection file
include_once "bootstrap.php";

$quiz_info = null;
// Check if quiz_id is set in the URL
if(isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
    
    // Fetch quiz information from the database based on quiz_id
    $quiz_info = $db->getSingleRow("SELECT q.*, c.name AS category_name, t.time_limit_minutes
                                    FROM quizzes q
                                    JOIN categories c ON q.category_id = c.id
                                    JOIN quiz_types t ON q.quiz_type_id = t.id
                                    WHERE q.id = $quiz_id");

}

if(!isset($_GET['quiz_id']) || $quiz_info == null){
    setMessageRedirect("Quiz not found!", "quiz.php", false);
}
include_once 'includes/header.php';
?>
<div class="container mb-5">
    <h2 class="mb-4">Quiz Instructions - <?php echo $quiz_info['name']; ?></h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Quiz Information:</h5>
            <p class="card-text"><strong>Name:</strong> <?php echo $quiz_info['name']; ?></p>
            <p class="card-text"><strong>Description:</strong> <?php echo $quiz_info['description']; ?></p>
            <p class="card-text"><strong>Category:</strong> <?php echo $quiz_info['category_name']; ?></p>
            <p class="card-text"><strong>Number of Questions:</strong> <?php echo getNumberOfQuestions($quiz_id); ?></p>
            <p class="card-text"><strong>Time Limit (minutes):</strong> <?php echo $quiz_info['time_limit_minutes']; ?>
            </p>
            <!-- Instructions for the quiz -->
            <h5 class="card-title">Instructions for the quiz:</h5>
            <ul style="list-style: decimal;margin-left: 25px">
                <li>This quiz contains multiple-choice questions.</li>
                <li>You can only select one answer for each question.</li>
                <li>Make sure to answer all questions before submitting.</li>
            </ul>
            <!-- End of instructions -->
            <?php
            // Generate a UUID for the attempt ID
            $attempt_uuid = uniqid();
            ?>
            <a href="<?=BASE_URL?>/quiz_page.php?quiz_id=<?php echo $quiz_id; ?>&attempt_uuid=<?php echo $attempt_uuid; ?>"
                class="btn btn-primary">Start Quiz</a>
        </div>
    </div>
</div>
<?php include_once 'includes/footer.php'; ?>
<?php
// Function to get the number of questions for a quiz
function getNumberOfQuestions($quiz_id) {
    global $db;
    $num_questions = $db->getSingleValue("SELECT COUNT(*) FROM questions WHERE quiz_id = $quiz_id");
    return $num_questions;
}
?>