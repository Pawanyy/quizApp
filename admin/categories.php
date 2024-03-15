<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

// Define database table name
$table = 'categories';

// Handle form submission for creating or updating categories
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Check for valid action
        if ($action === 'create' || $action === 'update') {
            // Check if name is provided
            if (isset($_POST['name'])) {
                $name = $_POST['name'];

                // Check if category already exists
                $existingCategory = $db->getSingleRow("SELECT * FROM $table WHERE name = '$name'");

                if ($existingCategory) {
                    // Category already exists
                    adminMessageRedirect("Error: Category already exists.", "categories.php", false);
                } else {
                    // Proceed with create or update
                    $data = [
                        'name' => $name
                    ];

                    if ($action === 'create') {
                        $result = $db->insert($table, $data);
                    } else {
                        $condition = "id = {$_POST['id']}";
                        $result = $db->update($table, $data, $condition);
                    }

                    // Check if operation was successful
                    if ($result) {
                        // Redirect with success message
                        adminMessageRedirect("Success: Operation completed.", "categories.php", true);
                    } else {
                        // Redirect with failure message
                        adminMessageRedirect("Error: Operation failed.", "categories.php", false);
                    }
                }
            } else {
                // Redirect with failure message
                adminMessageRedirect("Error: Name must be provided.", "categories.php", false);
            }
        } elseif ($action === 'fetch') {
            // Fetch Data
            $categories = $db->getCategories();

            $json_data = [];
            foreach ($categories as $category) {
                $json_data[] = [
                    $category['name'],
                    "<button class='btn btn-primary btn-sm edit-category' data-id='{$category['id']}' data-name='{$category['name']}'>Edit</button>
                    <button class='btn btn-danger btn-sm delete-category' data-id='{$category['id']}'>Delete</button>" // Delete button
                ];
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $json_data]);
            exit;
        } elseif ($action === 'delete') {
            $condition = "id = {$_POST['id']}";
            $result = $db->delete($table, $condition);

            // Return JSON response for AJAX request
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false]);
                exit;
            }
        }
    }
}
?>

<?php include __DIR__ . "/include/header.php";  ?>
<?php include __DIR__ . "/include/navbar.php";  ?>
<?php include __DIR__ . "/include/sidebar.php";  ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content pt-3">
        <div class="container-fluid">
            <!-- DataTable -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Categories</h3>
                    <button class="btn btn-primary float-right" id="addCategoryButton">Add Category</button>
                </div>
                <div class="card-body">
                    <table id="categoriesTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch categories from the database
                            $categories = $db->getMultipleRows("SELECT * FROM $table");
                            foreach ($categories as $category) {
                                echo "<tr data-id='{$category['id']}'>";
                                echo "<td>{$category['name']}</td>";
                                echo "<td>
                                        <button class='btn btn-primary btn-sm edit-category' data-id='{$category['id']}' data-name='{$category['name']}'>Edit</button>
                                        <button class='btn btn-danger btn-sm delete-category' data-id='{$category['id']}'>Delete</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End DataTable -->

            <!-- Category Form -->
            <!-- Category Form Modal -->
            <div class="modal fade" id="categoryFormModal" tabindex="-1" role="dialog"
                aria-labelledby="categoryFormModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="categoryFormModalLabel">Add/Edit Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="categoryFormInner" method="post">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <input type="hidden" id="categoryId" name="id">
                                <input type="hidden" id="action" name="action">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" form="categoryFormInner" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- End Category Form -->
        </div>
    </section>
</div>
<!-- End Content Wrapper -->

<?php include __DIR__ . "/include/footer.php";  ?>

<script>
$(document).ready(function() {
    var table = $('#categoriesTable').DataTable();

    // Show category form modal for editing or creating category
    $('#categoriesTable').on('click', '.edit-category', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#name').val(name);
        $('#categoryId').val(id);
        $('#action').val('update');
        $('#categoryFormModalLabel').text('Edit Category');
        $('#categoryFormModal').modal('show');
    });

    // Show category form modal for creating new category
    $('#addCategoryButton').click(function() {
        $('#name').val('');
        $('#categoryId').val('');
        $('#action').val('create');
        $('#categoryFormModalLabel').text('Add Category');
        $('#categoryFormModal').modal('show');
    });

    // Function to fetch categories
    function fetchCategories() {
        $.ajax({
            url: 'categories.php',
            type: 'post',
            dataType: 'json',
            data: {
                action: 'fetch'
            },
            success: function(response) {
                if (response.success) {
                    // Clear existing table rows
                    table.clear().rows.add(response.data).draw();

                } else {
                    alert('Failed to fetch categories');
                }
            },
            error: function() {
                alert('Failed to fetch categories');
            }
        });
    }

    // Handle delete category
    $('#categoriesTable').on('click', '.delete-category', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: 'categories.php',
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'delete',
                    id: id
                },
                success: function(response) {
                    if (response.success) {
                        // Reload DataTable after successful deletion
                        fetchCategories();
                        // Show success alert
                        $('#successAlert').removeClass('d-none');
                    } else {
                        alert('Failed to delete category');
                    }
                },
                error: function() {
                    alert('Failed to delete category');
                }
            });
        }
    });

    // Cancel form submission
    $('#cancelButton').click(function() {
        $('#categoryForm').addClass('d-none');
    });
});
</script>