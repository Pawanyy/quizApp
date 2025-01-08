<?php
require_once(__DIR__ . '/bootstrap.php');

if(!isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && $_SESSION['type'] != "STUDENT")){
    setMessageRedirect("Pls Login First!", "login.php", false);
}

// Check if attempt_id is set in the URL
if (!isset($_GET['attempt_id'])) {
    header("Location: quiz.php");
    exit();
}
$attempt_id = $_GET['attempt_id'];

// Fetch quiz attempt information from the database based on attempt_id
$quiz_attempt = $db->getSingleRow("SELECT qa.*, q.name AS quiz_name, qt.pass_percentage, qt.show_books_percentage, qt.show_doctors_percentage, qt.show_correct_answers
                                   FROM quiz_attempts qa
                                   JOIN quizzes q ON qa.quiz_id = q.id
                                   JOIN quiz_types qt ON q.quiz_type_id = qt.id
                                   WHERE qa.id = $attempt_id");

// Fetch user's answers and relevant question details from the database
$user_answers = $db->getMultipleRows("SELECT qaa.*, qn.question_text, qn.explanation, ans.answer_text AS correct_answer
                                      FROM quiz_attempt_answers qaa
                                      JOIN questions qn ON qaa.question_id = qn.id
                                      LEFT JOIN answers ans ON qn.id = ans.question_id AND ans.is_correct = 1
                                      WHERE qaa.quiz_attempt_id = $attempt_id");

$books = $db->getMultipleRows("SELECT * from books ORDER BY RAND() LIMIT 4");

$doctors = $db->getMultipleRows("SELECT * from doctors ORDER BY RAND() LIMIT 4");

// Calculate performance summary
$total_questions = count($user_answers);
$correct_answers = 0;
$incorrect_answers = 0;

foreach ($user_answers as $answer) {
    if ($answer['is_correct']) {
        $correct_answers++;
    } else {
        $incorrect_answers++;
    }
}
include_once 'includes/header.php';
?>

<section class="content mb-5">
    <h2>Quiz Result: <?php echo $quiz_attempt['quiz_name']; ?></h2>
    <hr>
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="card-title">Performance Summary</h1>
            <p class="card-text">Total Questions: <?php echo $total_questions; ?></p>
            <p class="card-text">Correct Answers: <?php echo $correct_answers; ?></p>
            <p class="card-text">Incorrect Answers: <?php echo $incorrect_answers; ?></p>
            <p class="card-text">User Score: <?php echo $quiz_attempt['score']; ?> out of
                <?php echo $quiz_attempt['total_score']; ?></p>
            <p class="card-text">User Score Percentage: <?php echo $quiz_attempt['percentage']; ?>%</p>
            <p>Pass Percentage: <?php echo $quiz_attempt['pass_percentage']; ?>%</p>
        </div>
    </div>
    <hr>
    <h3>Answers Summary</h3>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Question</th>
                            <th>Your Answer</th>
                            <th>Explanation</th>
                            <?php if ($quiz_attempt['show_correct_answers']): ?>
                            <th>Correct Answer</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_answers as $index => $answer): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo $answer['question_text']; ?></td>
                            <td><?php echo $answer['selected_answer']; ?></td>
                            <td><?php echo $answer['explanation']; ?></td>
                            <?php if ($quiz_attempt['show_correct_answers']): ?>
                            <td><?php echo $answer['correct_answer']; ?></td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php if($quiz_attempt['percentage'] < $quiz_attempt['show_books_percentage']){ ?> 
    <hr>
    <h3>Suggested Books</h3>
    <div class="row">
        <?php foreach($books as $book){ ?>
        <div class="col-3">
            <div class="card">
                <div class="card-header p-0 bg-white d-flex items-center justify-content-center">
                <?php if(isset($book) && !empty($book['image'])){ ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($book['image']) ?>"
                        alt="Book Image" class="w-75">
                <?php } else { ?>
                    <img src="<?php echo IMAGE_PATH ."book.jpg" ?>"
                        alt="Book Image" class="w-75">
                <?php } ?>
                </div>
                <div class="card-body">
                    <h4><?=$book["name"]?></h4>
                    <div>by <strong><?=$book["author"]?></strong></div>
                    <p class="mt-2"><?=substr($book["description"], 0, 100)?>...</p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
    
    <?php if($quiz_attempt['percentage'] < $quiz_attempt['show_doctors_percentage']){ ?> 
    <hr>
    <h3>Suggested Doctors</h3>
    <div class="row">
        <?php foreach($doctors as $doctor){ ?>
        <div class="col-3">
            <div class="card">
                <div class="card-header bg-white p-0 d-flex items-center justify-content-center">
                    <img class="w-75" src=<?=IMAGE_PATH . "doctor.png"?>>
                </div>
                <div class="card-body">
                    <h4><?=$doctor["name"]?></h4>
                    <div>Location: <strong><?=$doctor["location"]?></strong></div>
                    <div>Contact: <strong><?=$doctor["contact"]?></strong></div>
                    <div>Specialist: <strong><?=$doctor["specialist"]?></strong></div>
                    <p class="mt-2"><?=substr($doctor["description"], 0, 100)?>...</p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
    
</section>
<?php include_once 'includes/footer.php'; ?>