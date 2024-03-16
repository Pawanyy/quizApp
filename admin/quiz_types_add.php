<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$table = "quiz_types"; // Assuming this is your table name

// Initialize variables for form fields
$type_name = '';
$description = '';
$time_limit_minutes = '';
$is_randomized = '';
$show_correct_answers = '';
$pass_percentage = '';
$attempts_limit = '';
$shuffle_options = '';
$penalty_for_wrong_answer = '';
$points_per_question = '';

// Check if editing an existing quiz type
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Fetch existing quiz type data from the database and populate form fields
    $existing_quiz_type = $db->getSingleRow("SELECT * FROM $table WHERE id = $edit_id");
    if ($existing_quiz_type) {
        $type_name = $existing_quiz_type['type_name'];
        $description = $existing_quiz_type['description'];
        $time_limit_minutes = $existing_quiz_type['time_limit_minutes'];
        $is_randomized = $existing_quiz_type['is_randomized'];
        $show_correct_answers = $existing_quiz_type['show_correct_answers'];
        $pass_percentage = $existing_quiz_type['pass_percentage'];
        $attempts_limit = $existing_quiz_type['attempts_limit'];
        $shuffle_options = $existing_quiz_type['shuffle_options'];
        $penalty_for_wrong_answer = $existing_quiz_type['penalty_for_wrong_answer'];
        $points_per_question = $existing_quiz_type['points_per_question'];
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $type_name = $_POST["type_name"];
    $description = $_POST["description"];
    $time_limit_minutes = $_POST["time_limit_minutes"];
    $is_randomized = $_POST["is_randomized"];
    $show_correct_answers = $_POST["show_correct_answers"];
    $pass_percentage = $_POST["pass_percentage"];
    $attempts_limit = $_POST["attempts_limit"];
    $shuffle_options = $_POST["shuffle_options"];
    $penalty_for_wrong_answer = $_POST["penalty_for_wrong_answer"];
    $points_per_question = $_POST["points_per_question"];

    $existingCondition = (isset($_POST['id']) && !empty($_POST['id'])) ? " AND id != {$_POST['id']}" : "";

    // Check for existing name
    $existingName = $db->getSingleRow("SELECT * FROM $table WHERE type_name = '$type_name' $existingCondition");

    if (($existingName)) {
        // Level or name already exists
        adminMessageRedirect("Error: Type Name already exists.", "quiz_types.php", false);
    }
    
    // Proceed with create or update
    $data = [
        'type_name' => $type_name,
        'description' => $description,
        'time_limit_minutes' => $time_limit_minutes,
        'is_randomized' => $is_randomized,
        'show_correct_answers' => $show_correct_answers,
        'pass_percentage' => $pass_percentage,
        'attempts_limit' => $attempts_limit,
        'shuffle_options' => $shuffle_options,
        'penalty_for_wrong_answer' => $penalty_for_wrong_answer,
        'points_per_question' => $points_per_question
    ];

    if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
        // Insert data into database
        $condition = "id = {$_GET['edit_id']}";
        $result = $db->update($table, $data, $condition);
    } else {
        $result = $db->insert($table, $data);
    }
    // Check if insertion was successful
    if ($result) {
        // Redirect with success message
        adminMessageRedirect("Success: Quiz type added.", "quiz_types.php", true);
    } else {
        // Redirect with failure message
        adminMessageRedirect("Error: Failed to add quiz type.", "add_quiz_type.php", false);
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
</style>
<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row pt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <?php echo isset($_GET['edit_id']) ? 'Edit Quiz Type' : 'Add Quiz Type'; ?></h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form
                                action="quiz_types_add.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>"
                                method="POST">
                                <div class="form-group">
                                    <label for="type_name">Type Name:</label>
                                    <input type="text" class="form-control" id="type_name" name="type_name"
                                        value="<?php echo isset($existing_quiz_type) ? $existing_quiz_type['type_name'] : ''; ?>"
                                        minlength="2" maxlength="20" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        minlength="2" maxlength="100"
                                        required><?php echo isset($existing_quiz_type) ? $existing_quiz_type['description'] : ''; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="time_limit_minutes">Time Limit (minutes):</label>
                                    <input type="number" class="form-control" id="time_limit_minutes"
                                        name="time_limit_minutes"
                                        value="<?php echo isset($existing_quiz_type) ? $existing_quiz_type['time_limit_minutes'] : ''; ?>"
                                        min="1" max="100" step="1" required>
                                </div>
                                <div class="form-group">
                                    <label for="is_randomized">Is Randomized:</label>
                                    <select class="form-control" id="is_randomized" name="is_randomized">
                                        <option value="1"
                                            <?php echo (isset($existing_quiz_type) && $existing_quiz_type['is_randomized'] == 1) ? 'selected' : ''; ?>>
                                            Yes</option>
                                        <option value="0"
                                            <?php echo (isset($existing_quiz_type) && $existing_quiz_type['is_randomized'] == 0) ? 'selected' : ''; ?>>
                                            No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="show_correct_answers">Show Correct Answers:</label>
                                    <select class="form-control" id="show_correct_answers" name="show_correct_answers">
                                        <option value="1"
                                            <?php echo (isset($existing_quiz_type) && $existing_quiz_type['show_correct_answers'] == 1) ? 'selected' : ''; ?>>
                                            Yes</option>
                                        <option value="0"
                                            <?php echo (isset($existing_quiz_type) && $existing_quiz_type['show_correct_answers'] == 0) ? 'selected' : ''; ?>>
                                            No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pass_percentage">Pass Percentage:</label>
                                    <input type="number" class="form-control" id="pass_percentage"
                                        name="pass_percentage"
                                        value="<?php echo isset($existing_quiz_type) ? $existing_quiz_type['pass_percentage'] : ''; ?>"
                                        min="0" max="100" required>
                                </div>
                                <div class="form-group">
                                    <label for="attempts_limit">Attempts Limit:</label>
                                    <input type="number" class="form-control" id="attempts_limit" name="attempts_limit"
                                        value="<?php echo isset($existing_quiz_type) ? $existing_quiz_type['attempts_limit'] : ''; ?>"
                                        min="1" max="999" step="1" required>
                                </div>
                                <div class="form-group">
                                    <label for="shuffle_options">Shuffle Options:</label>
                                    <select class="form-control" id="shuffle_options" name="shuffle_options">
                                        <option value="1"
                                            <?php echo (isset($existing_quiz_type) && $existing_quiz_type['shuffle_options'] == 1) ? 'selected' : ''; ?>>
                                            Yes</option>
                                        <option value="0"
                                            <?php echo (isset($existing_quiz_type) && $existing_quiz_type['shuffle_options'] == 0) ? 'selected' : ''; ?>>
                                            No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="penalty_for_wrong_answer">Penalty for Wrong Answer:</label>
                                    <select class="form-control" id="penalty_for_wrong_answer"
                                        name="penalty_for_wrong_answer">
                                        <option value="1"
                                            <?php echo (isset($existing_quiz_type) && $existing_quiz_type['penalty_for_wrong_answer'] == 1) ? 'selected' : ''; ?>>
                                            Yes</option>
                                        <option value="0"
                                            <?php echo (isset($existing_quiz_type) && $existing_quiz_type['penalty_for_wrong_answer'] == 0) ? 'selected' : ''; ?>>
                                            No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="points_per_question">Points per Question:</label>
                                    <input type="number" class="form-control" id="points_per_question"
                                        name="points_per_question"
                                        value="<?php echo isset($existing_quiz_type) ? $existing_quiz_type['points_per_question'] : ''; ?>"
                                        min="1" max="100" step="1" required>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary"><?php echo isset($_GET['edit_id']) ? 'Update Quiz Type' : 'Add Quiz Type'; ?></button>
                            </form>
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


<?php include __DIR__ . "/include/footer.php";  ?>