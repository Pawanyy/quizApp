<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

$table = "books"; // Assuming this is your table name
$books = $db->getMultipleRows("SELECT * FROM $table ORDER BY id ASC");

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
            adminMessageRedirect("Success: Operation completed.", "books.php", true);
        } else {
            // Redirect with failure message
            adminMessageRedirect("Error: Operation failed.", "books.php", false);
        }
    } catch (mysqli_sql_exception $e) {
        // Handle MySQL exceptions
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            // Foreign key constraint violation
            adminMessageRedirect("Error: Cannot delete the record. It is associated with other data.", "books.php", false);
        } else {
            // Other types of exceptions
            adminMessageRedirect("Error: An error occurred while processing the operation.", "books.php", false);
        }
    } catch (Exception $e) {
        // Handle other types of exceptions
        adminMessageRedirect("Error: An error occurred while processing the operation.", "books.php", false);
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
                            <h3 class="card-title">Books</h3>
                            <a class="btn btn-primary float-right" href="/admin/books_add.php">Add Book</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-wrapper">
                                <table id="dataTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Book Name</th>
                                            <th>Image</th>
                                            <th>Author</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($books as $book): ?>
                                        <tr>
                                            <td><?= $book['name'] ?></td>
                                            <td>
                                                <?php if(isset($book) && !empty($book['image'])){ ?>
                                                <img src="data:image/jpeg;base64,<?= base64_encode($book['image']) ?>"
                                                    alt="Book Image" class="img-fluid">
                                                <?php } else { ?>
                                                    <img src="<?php echo IMAGE_PATH ."book.jpg" ?>"
                                                    alt="Book Image" class="img-fluid">
                                                <?php } ?>
                                            </td>
                                            <td><?= $book['author'] ?></td>
                                            <td><?= $book['description'] ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-sm ml-2"
                                                    href="/admin/books_add.php?edit_id=<?= $book['id'] ?>">Edit</a>
                                                <a href="books.php?id=<?= $book['id'] ?>"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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

<?php include __DIR__ . "/include/footer.php";  ?>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>