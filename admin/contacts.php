<?php
include_once dirname(__DIR__). '/bootstrap.php';
// Check if contact_id is set and not empty
if(isset($_POST['contact_id']) && !empty($_POST['contact_id'])) {
    $contactId = $_POST['contact_id'];

    // Define the table and condition for deletion
    $table = 'contact_information';
    $condition = "id = $contactId";

    // Delete contact from the database
    $result = $db->delete($table, $condition);

    // Check if deletion was successful
    if($result) {
        
        // Reload the table with updated data
        $contacts = $db->getContacts();
        
        $json_data = [];
        foreach ($contacts as $contact) {
            $json_data[] = [
                $contact['name'],
                $contact['email'],
                $contact['phone_number'],
                $contact['message'],
                $contact['user_username'] ?: "No associated user",
                $contact['user_email'] ?: "",
                $contact['user_full_name'] ?: "",
                '<button class="btn btn-danger btn-sm delete-contact" data-contact-id="'.$contact['id'].'">Delete</button>' // Delete button
            ];
        }
        
        // Return JSON response with updated data
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $json_data]);
        exit;
    } else {
        // Return error message as JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }
}
?>
<?php include __DIR__ . "/include/header.php";  ?>
<?php include __DIR__ . "/include/navbar.php";  ?>
<?php include __DIR__ . "/include/sidebar.php";  ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- Your page content here -->
        <div class="container-fluid pt-3">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Contacts with User Information</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="contactsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Contact Name</th>
                                        <th>Contact Email</th>
                                        <th>Contact Phone Number</th>
                                        <th>Contact Message</th>
                                        <th>User Username</th>
                                        <th>User Email</th>
                                        <th>User Full Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch contacts with user information
                                    $contacts = $db->getContacts();

                                    // Convert PHP data to JSON for DataTable
                                    $json_data = [];
                                    foreach ($contacts as $contact) {
                                        $json_data[] = [
                                            $contact['name'],
                                            $contact['email'],
                                            $contact['phone_number'],
                                            $contact['message'],
                                            $contact['user_username'] ?: "No associated user",
                                            $contact['user_email'] ?: "",
                                            $contact['user_full_name'] ?: "",
                                            '<button class="btn btn-danger btn-sm delete-contact" data-contact-id="'.$contact['id'].'">Delete</button>' // Delete button
                                        ];
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Include necessary scripts -->
<?php include __DIR__ . "/include/footer.php";  ?>

<script>
$(document).ready(function() {
    var table = $('#contactsTable').DataTable({
        data: <?php echo json_encode($json_data); ?>,
        columns: [{
                title: "Contact Name"
            },
            {
                title: "Contact Email"
            },
            {
                title: "Contact Phone Number"
            },
            {
                title: "Contact Message"
            },
            {
                title: "User Username"
            },
            {
                title: "User Email"
            },
            {
                title: "User Full Name"
            },
            {
                title: "Action"
            } // Add Action column

        ]
    });

    // Handle delete button click event
    $('#contactsTable').on('click', '.delete-contact', function() {
        var contactId = $(this).data('contact-id');
        if (confirm('Are you sure you want to delete this contact?')) {
            // You can perform AJAX call here to delete the contact
            // Example:
            $.ajax({
                url: 'contacts.php',
                type: 'POST',
                dataType: 'json', // Expect JSON response
                data: {
                    contact_id: contactId
                },
                success: function(response) {
                    if (response.success) {
                        // Reload DataTable after successful deletion
                        table.clear().rows.add(response.data).draw();
                    } else {
                        alert('Failed to delete contact');
                    }
                },
                error: function() {
                    alert('Failed to delete contact');
                }
            });
        }
    });
});
</script>