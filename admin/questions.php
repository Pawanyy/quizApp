<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

// Initialize variables to store quiz information
$quizInfo = null;
$totalQuestions['total'] = 0;
$table = 'questions';

$quiz_questions_condition = '';

// Check if quiz_id is provided in the URL
if (isset($_GET['quiz_id']) && is_numeric($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];

    // Fetch quiz information
    $quizInfo = $db->getSingleRow("SELECT q.id AS quiz_id, q.name AS quiz_name, c.name AS category_name
                             FROM quizzes q
                             JOIN categories c ON q.category_id = c.id
                             WHERE q.id = $quiz_id");

    // Count total questions for the quiz
    $totalQuestions = $db->getSingleRow("SELECT COUNT(*) AS total
                                           FROM questions 
                                           WHERE quiz_id = $quiz_id");
    $quiz_questions_condition = " WHERE q.quiz_id = $quiz_id";
} else {
    $totalQuestions = $db->getSingleRow("SELECT COUNT(*) AS total
                                            FROM questions");
}

// Fetch questions and associated details
$questions = $db->getMultipleRows("SELECT 
                                    q.id AS question_id,
                                    q.quiz_id,
                                    q.question_text,
                                    d.name AS difficulty_level,
                                    q.created_at AS question_created_at,
                                    q.explanation,
                                    a.id AS answer_id,
                                    a.answer_text,
                                    a.is_correct
                                FROM 
                                    questions q
                                LEFT JOIN 
                                    answers a ON q.id = a.question_id
                                LEFT JOIN 
                                    difficulty_levels d ON q.difficulty_level_id = d.id
                                $quiz_questions_condition
                                ORDER BY 
                                    q.id, a.id;");

// Perform deletion operation if applicable
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Build condition for deletion
    $condition = "id = $id";

    try {
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
    } catch (mysqli_sql_exception $e) {
        // Handle MySQL exceptions
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            // Foreign key constraint violation
            adminMessageRedirect("Error: Cannot delete the record. It is associated with other data.", "quizzes.php", false);
        } else {
            // Other types of exceptions
            adminMessageRedirect("Error: An error occurred while processing the operation.", "quizzes.php", false);
        }
    } catch (Exception $e) {
        // Handle other types of exceptions
        adminMessageRedirect("Error: An error occurred while processing the operation.", "quizzes.php", false);
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

.btn-column {
    width: 100px;
}
</style>
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
                                        <td><?= $totalQuestions['total'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Quiz Information Table -->

                    <!-- Questions Table -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Questions</h3>
                            <?php if($quizInfo): ?>
                            <a class="btn btn-primary float-right"
                                href="/admin/questions_add.php?quiz_id=<?=$quizInfo['quiz_id']?>">Add Question</a>
                            <?php endif; ?>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-wrapper">
                                <table id="dataTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Question Text</th>
                                            <th>Difficulty Level</th>
                                            <th>Answers</th>
                                            <th>Correct Answer</th>
                                            <th class="btn-column">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter = 1; ?>
                                        <?php 
                                        $last_question_id = 0;
                                        foreach ($questions as $question): ?>
                                        <?php if ($question['answer_id'] !== null && $question['question_id'] != $last_question_id):
                                            $last_question_id = $question['question_id']; ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>
                                            <td><?= $question['question_text'] ?></td>
                                            <td><?= $question['difficulty_level'] ?></td>
                                            <td>
                                                <ul style="list-style: inside;">
                                                    <?php foreach ($questions as $ans): ?>
                                                    <?php if ($ans['question_id'] == $question['question_id']): ?>
                                                    <li><?= $ans['answer_text'] ?></li>
                                                    <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <?php foreach ($questions as $ans): ?>
                                                <?php if ($ans['question_id'] == $question['question_id'] && $ans['is_correct'] == 1): ?>
                                                <?= $ans['answer_text'] ?>
                                                <?php endif; ?>
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger btn-sm"
                                                    href="/admin/questions.php?id=<?= $question['question_id'] ?>">Delete</a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- End Questions Table -->
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
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>