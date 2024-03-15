<?php
require_once(__DIR__ . '/bootstrap.php');

// Create a new instance of the Database class
$db = new Database();

// Check if quiz_id is set in the URL
if(isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
    
    // Fetch quiz information from the database based on quiz_id
    $quiz_info = $db->getSingleRow("SELECT q.*, c.name AS category_name, qt.type_name AS quiz_type_name, qt.time_limit_minutes AS quiz_time_limit_minutes, qt.attempts_limit AS quiz_attempts_limit, qt.penalty_for_wrong_answer, qt.points_per_question, qt.is_randomized, qt.shuffle_options
                                    FROM quizzes q 
                                    JOIN categories c ON q.category_id = c.id 
                                    JOIN quiz_types qt ON q.quiz_type_id = qt.id 
                                    WHERE q.id = $quiz_id");

    if(!$quiz_info) {
        setMessageRedirect("Quiz not found!", "quiz.php", false);
    }
} else {
    setMessageRedirect("Invalid request!", "quiz.php", false);
}

// Check if attempt_uuid is set in the URL
$attempt_uuid = isset($_GET['attempt_uuid']) ? $_GET['attempt_uuid'] : null;

// Generate a UUID if attempt_uuid is empty
if (empty($attempt_uuid)) {
    $attempt_uuid = uniqid();
}

    // Get the current timestamp for start_time
    
$existing_attempt = $db->getSingleRow("SELECT id FROM quiz_attempts WHERE attempt_uuid = '$attempt_uuid'");
if ($existing_attempt) {    
    $quiz_attempt_id = $existing_attempt['id'];
} else {
    $start_time = date("Y-m-d H:i:s");
    // Prepare data for insertion into the quiz_attempts table
    $data = [
        'quiz_id' => $quiz_id,
        'attempt_uuid' => $attempt_uuid,
        'start_time' => $start_time,
        'user_id' => $_SESSION['user_id'] // Assuming user_id is stored in session
    ];

    // Insert the attempt data into the quiz_attempts table
    $db->insert('quiz_attempts', $data);

    // Fetch the inserted quiz_attempt_id
    $quiz_attempt_id = $db->getLastInsertedId();
}

// Fetch questions for the quiz from the database
$questions = $db->getMultipleRows("SELECT q.*, d.name AS difficulty_level_name 
                                   FROM questions q 
                                   JOIN difficulty_levels d ON q.difficulty_level_id = d.id 
                                   WHERE q.quiz_id = $quiz_id
                                   ORDER BY q.id ASC");

// If is_randomized is true, shuffle the questions
if ($quiz_info['is_randomized']) {
    shuffle($questions);
}

include_once 'includes/header.php';
?>
<section class="content mb-5">
    <style>
    .question-card {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .question-card .card-body {
        padding: 20px;
    }

    .question-card .card-title {
        margin-bottom: 15px;
    }

    .question-card p {
        margin-bottom: 0;
    }

    .question-card .form-check {
        margin-bottom: 10px;
    }

    .explanation {
        margin-top: 20px;
    }

    .quiz-info-table th {
        width: 200px;
    }

    .answers_div {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .answers_div .form-check:hover {
        background: #dee2e6;
        border: 1px solid black;
    }

    .answers_div .form-check {
        background: #eeeeee;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin: 0;
        padding: 5px 10px;
    }

    .answers_div .form-check .form-check-input {
        position: relative;
        margin: 0;
    }
    </style>
    <div class="container-fluid">
        <h2 class="mb-4">Quiz - <?php echo $quiz_info['name']; ?></h2>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th colspan="2" class="text-center">General Information</th>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td><?php echo $quiz_info['category_name']; ?></td>
                            </tr>
                            <tr>
                                <th>Quiz Type</th>
                                <td><?php echo $quiz_info['quiz_type_name']; ?></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td><?php echo $quiz_info['description']; ?></td>
                            </tr>
                            <?php if ($quiz_info['quiz_time_limit_minutes'] > 0): ?>
                            <tr>
                                <th>Time Limit</th>
                                <td><?php echo $quiz_info['quiz_time_limit_minutes']; ?> minutes</td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($quiz_info['quiz_attempts_limit'] > 0): ?>
                            <tr>
                                <th>Attempts Limit</th>
                                <td><?php echo $quiz_info['quiz_attempts_limit']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($quiz_info['penalty_for_wrong_answer']): ?>
                            <tr>
                                <th>Penalty for Wrong Answer</th>
                                <td><span class="badge badge-danger">Yes</span></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($quiz_info['points_per_question'] > 0): ?>
                            <tr>
                                <th>Points Per Question</th>
                                <td><?php echo $quiz_info['points_per_question']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php
                        // Calculate total marks
                        $total_questions = count($questions);
                        $total_marks = $total_questions * $quiz_info['points_per_question'];
                        ?>
                            <tr>
                                <th colspan="2" class="text-center">Assessment</th>
                            </tr>
                            <tr>
                                <th>Total Marks</th>
                                <td><?php echo $total_marks; ?></td>
                            </tr>
                            <tr>
                                <th>Total Questions</th>
                                <td><?php echo $total_questions; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <form action="quiz_submit.php?quiz_id=<?php echo $quiz_id; ?>" method="post">
                    <?php
                    // Initialize question counter
                    $question_number = 1;

                    // Loop through each question
                    foreach ($questions as $question) {
                        $question_id = $question['id'];
                        $question_text = $question['question_text'];
                        $difficulty_level = $question['difficulty_level_name'];

                        // Fetch answers for the question from the database
                        $answers = $db->getMultipleRows("SELECT * FROM answers WHERE question_id = $question_id");

                        // Shuffle the answers if shuffle_options is enabled
                        if ($quiz_info['shuffle_options']) {
                            shuffle($answers);
                        }
                    ?>
                    <div class="card question-card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title m-0">
                                Question #<?php echo $question_number; ?>:
                                <span class="card-text"><?php echo $question_text; ?></span>
                            </h5>
                            <span class="badge badge-info"
                                style="height: fit-content;"><?php echo $difficulty_level; ?></span>
                        </div>
                        <div class="card-body">
                            <div class="answers_div" style="display: grid; grid-template-columns: 1fr 1fr;">
                                <?php foreach ($answers as $answer): ?>
                                <div data-answer-id="form_check_answer[<?php echo $question_id; ?>]" class="form-check"
                                    onclick="enableRadio(this)">
                                    <input class="form-check-input" type="radio"
                                        name="answer[<?php echo $question_id; ?>]"
                                        id="answer_<?php echo $question_id; ?>_<?php echo $answer['id']; ?>"
                                        value="<?php echo $answer['id']; ?>" required>
                                    <label class="form-check-label"
                                        for="answer_<?php echo $question_id; ?>_<?php echo $answer['id']; ?>">
                                        <?php echo $answer['answer_text']; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php if (!empty($question['explanation'])): ?>
                        <div class="card-footer">
                            <p><strong>Explanation:</strong> <?php echo $question['explanation']; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php
                        // Increment question counter
                        $question_number++;
                    }
                    ?>
                    <input name="attempt_id" type="hidden" value="<?php echo $quiz_attempt_id ?>">
                    <button type="submit" class="btn btn-primary mt-3">Submit Quiz</button>
                </form>
                <script>
                function enableRadio(element) {
                    // Find the radio button inside the clicked form-check element
                    var radio = element.querySelector('.form-check-input[type="radio"]');
                    console.log(element, radio, radio.checked)
                    // Check if the radio button is disabled
                    if (radio && !radio.checked) {
                        // Check (select) the radio button
                        radio.checked = true;
                    }
                }
                </script>
            </div>
        </div>
    </div>
</section>
<?php include_once 'includes/footer.php'; ?>