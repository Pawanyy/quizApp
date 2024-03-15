<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : '';

// Check if the quiz exists
$quizExists = false;
if ($quiz_id) {
$quiz = $db->getSingleRow("SELECT id FROM quizzes WHERE id = $quiz_id");
if ($quiz) {
$quizExists = true;
}
}

if($quiz_id == '' || !$quizExists){
// Redirect with error message
adminMessageRedirect("Invalid Quiz or does Not Exist.", "quizzes.php", false);
exit();
}

$quizInfo = $db->getSingleRow("SELECT q.id AS quiz_id, q.name AS quiz_name, c.name AS category_name,
(SELECT COUNT(*) FROM questions WHERE quiz_id = $quiz_id) AS total_questions
FROM quizzes q
JOIN categories c ON q.category_id = c.id
WHERE q.id = $quiz_id");

// Fetch existing questions and answers for the quiz
$questions = $db->getMultipleRows("SELECT
q.id AS question_id,
q.question_text,
q.explanation,
a.id AS answer_id,
a.answer_text,
a.is_correct
FROM
questions q
LEFT JOIN
answers a ON q.id = a.question_id
WHERE
q.quiz_id = $quiz_id
ORDER BY
q.id, a.id");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Update or insert the questions and answers
foreach ($_POST['questions'] as $questionData) {
$question_id = $questionData['question_id'];
$question_text = $questionData['question_text'];
$explanation = $questionData['explanation'];

// Update or insert the question
$db->updateOrInsert("questions", [
'id' => $question_id,
'quiz_id' => $quiz_id,
'question_text' => $question_text,
'explanation' => $explanation
]);

// Delete all answers for this question
$db->delete("answers", "question_id = $question_id");

// Insert new answers
foreach ($questionData['answers'] as $answerData) {
$db->insert("answers", [
'question_id' => $question_id,
'answer_text' => $answerData['answer_text'],
'is_correct' => isset($answerData['is_correct']) ? 1 : 0
]);
}
}

// Redirect with success message
adminMessageRedirect("Questions and answers updated successfully.", "quizzes.php", true);
exit();
}

// Include header, navbar, and sidebar
include __DIR__ . "/include/header.php";
include __DIR__ . "/include/navbar.php";
include __DIR__ . "/include/sidebar.php";
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row pt-3">
                <div class="col-12">
                    <!-- Quiz Information Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quiz Information</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Quiz Name</th>
                                        <th>Category</th>
                                        <th>Total Questions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $quizInfo ? $quizInfo['quiz_name'] : 'All Quizzes' ?></td>
                                        <td><?= $quizInfo ? $quizInfo['category_name'] : 'N/A' ?></td>
                                        <td><?= $quizInfo ? $quizInfo['total_questions'] : 'N/A' ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Quiz Information Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Questions and Answers</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="" method="POST">
                                <?php foreach ($questions as $question): ?>
                                <div class="form-group">
                                    <label>Question:</label>
                                    <input type="hidden" name="questions[<?= $question['question_id'] ?>][question_id]"
                                        value="<?= $question['question_id'] ?>">
                                    <input type="text" class="form-control"
                                        name="questions[<?= $question['question_id'] ?>][question_text]"
                                        value="<?= $question['question_text'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Explanation:</label>
                                    <textarea class="form-control"
                                        name="questions[<?= $question['question_id'] ?>][explanation]"><?= $question['explanation'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Answers:</label>
                                    <?php foreach ($question['answers'] as $answer): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="questions[<?= $question['question_id'] ?>][answers][<?= $answer['answer_id'] ?>][is_correct]"
                                            value="1" <?= $answer['is_correct'] ? 'checked' : '' ?>>
                                        <input type="text" class="form-control"
                                            name="questions[<?= $question['question_id'] ?>][answers][<?= $answer['answer_id'] ?>][answer_text]"
                                            value="<?= $answer['answer_text'] ?>">
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endforeach; ?>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
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

<!-- Include footer -->
<?php include __DIR__ . "/include/footer.php";  ?>