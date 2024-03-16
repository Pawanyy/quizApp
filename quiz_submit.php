<?php
require_once(__DIR__ . '/bootstrap.php');

if(!isset($_SESSION['user_id']) || (isset($_SESSION['user_id']) && $_SESSION['type'] != "STUDENT")){
    setMessageRedirect("Pls Login First!", "login.php", false);
}

// Check if the quiz submission form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the quiz attempt ID from the form data
    $quiz_attempt_id = $_POST['attempt_id'];

    // Retrieve quiz information including pass percentage, penalty for wrong answer, points per question,
    // total number of questions, and total points directly from the database using join
    $quiz_attempt = $db->getSingleRow("SELECT qa.quiz_id, qa.start_time, qt.time_limit_minutes, qt.attempts_limit, qt.pass_percentage, qt.penalty_for_wrong_answer, qt.points_per_question,
                                              COUNT(q.id) AS total_questions,
                                              SUM(qt.points_per_question) AS total_points
                                       FROM quiz_attempts qa
                                       JOIN quizzes q ON qa.quiz_id = q.id
                                       JOIN quiz_types qt ON q.quiz_type_id = qt.id
                                       JOIN questions qn ON qn.quiz_id = q.id
                                       WHERE qa.id = $quiz_attempt_id
                                       GROUP BY qa.quiz_id");

    // Calculate the end time of the quiz based on the start time and quiz duration
    $start_time = strtotime($quiz_attempt['start_time']);
    $quiz_duration_minutes = $quiz_attempt['time_limit_minutes'];
    $end_time = date("Y-m-d H:i:s", $start_time + ($quiz_duration_minutes * 60));

    // Check if the current time is past the calculated end time
    $current_time = time();
    $quiz_has_ended = ($current_time > strtotime($end_time)) ? true : false;

    // Define attempts limit
    $attempts_limit = $quiz_attempt['attempts_limit']; // Change this value as needed

    // Check if the user has already exceeded the attempts limit
    $previous_attempts_count = $db->getSingleValue("SELECT COUNT(*) FROM quiz_attempts WHERE user_id = {$_SESSION['user_id']} AND id != {$quiz_attempt_id} AND quiz_id = {$quiz_attempt['quiz_id']}");

    if ($previous_attempts_count >= $attempts_limit) {
        setMessageRedirect("You have exceeded the maximum attempts limit for this quiz.", "quiz.php", false);
    }

    if ($quiz_has_ended) {
        // Quiz has ended, perform actions accordingly (e.g., redirect to quiz result page)
        $data = [
            'score' => 0,
            'percentage' => 0,
            'pass_percentage' => $quiz_attempt['pass_percentage'],
            'passed' => 0,
            'end_time' => date("Y-m-d H:i:s"),
            'penalty_points' => $penalty_points,
            'submission_date' => date("Y-m-d H:i:s"),
        ];
    
        $db->update('quiz_attempts', $data, "id = $quiz_attempt_id");
        setMessageRedirect("Sorry Quiz has Ended!", "quiz.php", false);
    }

    // Retrieve the user's answers from the POST data
    $user_answers = $_POST['answer']; // Assuming 'answer' is the name of the input field containing the answers

    // Initialize variables to store quiz attempt details
    $total_questions = $quiz_attempt['total_questions'];
    $total_correct_answers = 0;
    $user_score = 0;

    // Loop through each user answer
    foreach ($user_answers as $question_id => $selected_answer_id) {
        // Retrieve the text of the selected answer from the database
        $selected_answer = $db->getSingleRow("SELECT answer_text, is_correct FROM answers WHERE question_id = $question_id AND id = $selected_answer_id");

        // Determine if the selected answer is correct
        $is_correct = ($selected_answer['is_correct'] == 1) ? 1 : 0;

        // Insert the user's answer into the database
        $data = [
            'quiz_attempt_id' => $quiz_attempt_id,
            'question_id' => $question_id,
            'selected_answer' => $selected_answer['answer_text'],
            'is_correct' => $is_correct
        ];
        $db->insert('quiz_attempt_answers', $data);

        if ($is_correct) {
            $total_correct_answers++;
        }
    }

    // Calculate the user's score
    if ($total_questions > 0) {
        $user_score = $total_correct_answers * $quiz_attempt['points_per_question'];
    }

    // Apply penalty for wrong answers if applicable
    if ($quiz_attempt['penalty_for_wrong_answer']) {
        $wrong_answers_count = $total_questions - $total_correct_answers;
        $penalty_points = $wrong_answers_count * 1;
        $user_score -= $penalty_points;
    }

    // Check if the user passed the quiz based on the pass percentage
    $total_score = $total_questions * $quiz_attempt['points_per_question'];
    $user_percentage = ($user_score / $total_score) * 100;
    $passed_quiz = ($user_percentage >= $quiz_attempt['pass_percentage']) ? 1 : 0;

    // Update the quiz attempt record in the database with the calculated values
    $data = [
        'score' => $user_score,
        'total_score' => $total_score,
        'percentage' => $user_percentage,
        'pass_percentage' => $quiz_attempt['pass_percentage'],
        'passed' => $passed_quiz,
        'end_time' => date("Y-m-d H:i:s"), // Set the end time when the quiz attempt is submitted
        'penalty_points' => $penalty_points,
        'total_questions' => $total_questions,
        'correct_answers' => $total_correct_answers,
        'incorrect_answers' => $wrong_answers_count,
        'submission_date' => date("Y-m-d H:i:s"),
    ];

    $db->update('quiz_attempts', $data, "id = $quiz_attempt_id");

    // Redirect to the appropriate page based on quiz submission status
    header("Location: quiz_result.php?attempt_id=$quiz_attempt_id");
    exit();
} else {
    // If the form is not submitted via POST method, redirect to the quiz selection page or any other appropriate page
    header("Location: quiz.php");
    exit();
}
?>