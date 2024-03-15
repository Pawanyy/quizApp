<?php 
include_once 'bootstrap.php';

if (isset($_POST['contact'])) {
    // Contact form submitted
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Get user ID from session if set, otherwise set it to null
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Insert contact information into the database
    $insert_result = $db->insertContactInfo($user_id, $name, $email, $phone, $message);
    if ($insert_result === true) {
        // Contact information inserted successfully
        setMessageRedirect("Contact information submitted successfully!", "contact.php", true);
    } else {
        // Insertion failed, display error message
        setMessageRedirect("Error submitting contact information!", "contact.php", false);
    }
}

include_once 'includes/header.php';
?>


<!-- Address Section -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Address:</h5>
                        <p>123 Main Street<br>City, State ZIP<br>Country</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Contact Information:</h5>
                        <p><strong>Email:</strong> info@example.com<br><strong>Phone:</strong> +1 (123) 456-7890</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form Section -->
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Contact Us
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="contact" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php include_once 'includes/footer.php'; ?>