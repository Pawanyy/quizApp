<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$table = "books"; // Assuming this is your table name

// Initialize variables for form fields
$name = '';
$author = '';
$description = '';
// Check if editing an existing quiz type
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];

    // Fetch existing quiz type data from the database and populate form fields
    $existing_book = $db->getSingleRow("SELECT * FROM $table WHERE id = $edit_id");
    if ($existing_book) {
        $name = $existing_book['name'];
        $author = $existing_book['author'];
        $description = $existing_book['description'];
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $author = $_POST["author"];
    $description = $_POST["description"];

    $existingCondition = (isset($_POST['id']) && !empty($_POST['id'])) ? " AND id != {$_POST['id']}" : "";

    // Check for existing name
    $existingName = $db->getSingleRow("SELECT * FROM $table WHERE name = '$name' $existingCondition");

    if (($existingName)) {
        // Level or name already exists
        adminMessageRedirect("Error: Book Name already exists.", "books.php", false);
    }
    
    // Proceed with create or update
    $data = [
        'name' => $name,
        'author' => $author,
        'description' => $description,
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
        $message = "Success: Book " . (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) ? "Updated" : "Added";
        // Redirect with success message
        adminMessageRedirect($message, "books.php", true);
    } else {
        // Redirect with failure message
        adminMessageRedirect("Error: Failed to add book. Please try again.", "books_add.php", false);
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
                                <?php echo isset($_GET['edit_id']) ? 'Edit Book' : 'Add Book'; ?></h3>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form
                                action="books_add.php<?php echo isset($_GET['edit_id']) ? '?edit_id=' . $_GET['edit_id'] : ''; ?>"
                                method="POST">
                                <div class="form-group">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo isset($existing_book) ? $existing_book['name'] : ''; ?>"
                                        minlength="2" maxlength="255" required>
                                </div>
                                <div class="form-group">
                                    <label for="author">Author:</label>
                                    <input type="text" class="form-control" id="author" name="author"
                                        value="<?php echo isset($existing_book) ? $existing_book['author'] : ''; ?>"
                                        minlength="2" maxlength="255" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                        minlength="2" maxlength="500"
                                        required><?php echo isset($existing_book) ? $existing_book['description'] : ''; ?></textarea>
                                </div>
                                <input type="hidden" name="id" value="<?php echo  isset($existing_book) ? $existing_book['id'] : ''; ?>" />
                                <button type="submit"
                                    class="btn btn-primary"><?php echo isset($_GET['edit_id']) ? 'Update Book' : 'Add Book'; ?></button>
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