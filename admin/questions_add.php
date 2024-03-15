<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$difficulty_level_id = '';
$question_text = '';
$explanation = '';
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




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $difficulty_level_id = $_POST["difficulty_level_id"];
    $question_text = $_POST["question_text"];
    $explanation = $_POST["explanation"];
    
    $data = [
        'quiz_id' => $quiz_id,
        'difficulty_level_id' => $difficulty_level_id,
        'question_text' => $question_text,
        'explanation' => $explanation
    ];

    $question_id = $db->insert("questions", $data, true);

    if ($question_id) {
        $answers = $_POST["answers"];
        foreach ($answers as $answer) {
            $answer_text = $answer['answer_text'];
            $is_correct = $answer['is_correct'];
            $data = [
                'question_id' => $question_id,
                'answer_text' => $answer_text,
                'is_correct' => $is_correct ? $is_correct : 0
            ];
            $db->insert("answers", $data);
        }

        // Redirect with success message
        adminMessageRedirect("Question and answers added successfully.", "quizzes.php", true);
        exit();
    } else {
        // Redirect with error message
        adminMessageRedirect("Failed to add question and answers.", "quizzes.php", false);
        exit();
    }
}
?>

<?php include __DIR__ . "/include/header.php";  ?>
<?php include __DIR__ . "/include/navbar.php";  ?>
<?php include __DIR__ . "/include/sidebar.php";  ?>

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
                            <h3 class="card-title">Add Question and Answers</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="form-group">
                                    <!-- Populate difficulty_levels dynamically from database -->
                                    <label for="difficulty_level_id">Difficulty Level:</label>
                                    <select class="form-control" id="difficulty_level_id" name="difficulty_level_id"
                                        required>
                                        <?php
                                        // Fetch categories from the database
                                        $difficulty_levels = $db->getMultipleRows("SELECT id, name FROM difficulty_levels");

                                        // Display difficulty_levels as options in select dropdown
                                        foreach ($difficulty_levels as $difficulty_level) {
                                            echo "<option value='" . $difficulty_level['id'] . "'>" . $difficulty_level['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="question_text">Question Text:</label>
                                    <textarea class="form-control" id="question_text" name="question_text" rows="3"
                                        required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="explanation">Explanation:</label>
                                    <textarea class="form-control" id="explanation" name="explanation"
                                        rows="3"></textarea>
                                </div>
                                <div class="form-group" id="answers-group">
                                    <label>Answers:</label>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Answer No</th>
                                                <th>Correct</th>
                                                <th>Answer Text</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <input class="form-check-input" type="checkbox"
                                                        name="answers[0][is_correct]" value="1">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text"
                                                        name="answers[0][answer_text]" required>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-primary" id="add-answer">Add Answer</button>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Add Question and Answers</button>
                                </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    var answersGroup = document.getElementById('answers-group');
    var addButton = document.getElementById('add-answer');

    addButton.addEventListener('click', function() {
        var answerIndex = answersGroup.querySelectorAll('tbody tr').length;

        var newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${answerIndex + 1}</td>
            <td>
                <input class="form-check-input" type="checkbox" name="answers[${answerIndex}][is_correct]" value="1">
            </td>
            <td>
                <input class="form-control" type="text" name="answers[${answerIndex}][answer_text]" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger remove-answer">Remove</button>
            </td>
        `;

        answersGroup.querySelector('tbody').appendChild(newRow);
    });

    // Event delegation for remove answer button
    answersGroup.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-answer')) {
            event.target.closest('tr').remove();
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');

    form.addEventListener('submit', function(event) {
        var answers = document.querySelectorAll('input[name^="answers["]');
        var correctCount = 0;
        var answerCount = 0;

        // Count the number of answers and correct answers
        answers.forEach(function(answer) {
            if (answer.value.trim() !== '') {
                answerCount++;
                if (answer.checked) {
                    correctCount++;
                }
            }
        });

        // Check if conditions are met
        if (answerCount < 2 || correctCount === 0) {
            event.preventDefault(); // Prevent form submission
            alert('At least one answer must be correct and two answers must be added.');
        }
    });
});
</script>

<?php include __DIR__ . "/include/footer.php";  ?>