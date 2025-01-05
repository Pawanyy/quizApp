<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$table = "quizzes"; // Assuming this is your table name

// Initialize variables for form fields
$name = '';
$description = '';
$category_id = '';
$quiz_type_id = '';

// Check if editing an existing quiz type
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Fetch existing quiz type data from the database and populate form fields
    $existing_quiz = $db->getSingleRow("SELECT * FROM $table WHERE id = $edit_id");
    if ($existing_quiz) {
        $name = $existing_quiz['name'];
        $description = $existing_quiz['description'];
        $category_id = $existing_quiz['category_id'];
        $quiz_type_id = $existing_quiz['quiz_type_id'];
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $description = $_POST["description"];
    $category_id = $_POST["category_id"];
    $quiz_type_id = $_POST["quiz_type_id"];
    
    // Proceed with create or update
    $data = [
        'name' => $name,
        'description' => $description,
        'category_id' => $category_id,
        'quiz_type_id' => $quiz_type_id
    ];

    
    $existingCondition = (isset($_POST['id']) && !empty($_POST['id'])) ? " AND id != {$_POST['id']}" : "";

    // Check for existing name
    $existingName = $db->getSingleRow("SELECT * FROM $table WHERE name = '$name' $existingCondition");

    if (($existingName)) {
        // Quiz Name already exists
        adminMessageRedirect("Error: Quiz Name already exists.", "quizzes.php", false);
    }

    if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
        // Insert data into database
        $condition = "id = {$_GET['edit_id']}";
        $result = $db->update($table, $data, $condition);
    } else {
        
        // Insert data into database
        $result = $db->insert($table, $data);

    }
    // Check if insertion was successful
    if ($result) {
        // Redirect with success message
        adminMessageRedirect("Success: Quiz added.", "quizzes.php", true);
    } else {
        // Redirect with failure message
        adminMessageRedirect("Error: Failed to add quiz.", "quizzes_add.php", false);
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
                                <?php echo isset($_GET['edit_id']) ? 'Edit Quiz' : 'Add Quiz'; ?></h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form
                                action="quizzes_add.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>"
                                method="POST">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo isset($existing_quiz) ? $existing_quiz['name'] : ''; ?>"
                                        required minlength="2" maxlength="20">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        minlength="2" maxlength="100"
                                        required><?php echo isset($existing_quiz) ? $existing_quiz['description'] : ''; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="category_id">Category:</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <?php
                                        // Fetch categories from the database
                                        $categories = $db->getMultipleRows("SELECT id, name FROM categories");

                                        // Display categories as options in select dropdown
                                        foreach ($categories as $category) {
                                            echo "<option value='" . $category['id'] . "'";
                                            if (isset($existing_quiz) && $existing_quiz['category_id'] == $category['id']) {
                                                echo " selected";
                                            }
                                            echo ">" . $category['name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="quiz_type_id">Quiz Type:</label>
                                    <select class="form-control" id="quiz_type_id" name="quiz_type_id">
                                        <?php
                                        // Fetch quiz types from the database
                                        $quiz_types = $db->getMultipleRows("SELECT id, type_name FROM quiz_types");

                                        // Display quiz types as options in select dropdown
                                        foreach ($quiz_types as $quiz_type) {
                                            echo "<option value='" . $quiz_type['id'] . "'";
                                            if (isset($existing_quiz) && $existing_quiz['quiz_type_id'] == $quiz_type['id']) {
                                                echo " selected";
                                            }
                                            echo ">" . $quiz_type['type_name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary"><?php echo isset($_GET['edit_id']) ? 'Update Quiz' : 'Add Quiz'; ?></button>
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