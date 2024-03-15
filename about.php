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
        setMessageRedirect("Contact information submitted successfully!", "about.php", true);
    } else {
        // Insertion failed, display error message
        setMessageRedirect("Error submitting contact information!", "about.php",false);
    }
}

include_once 'includes/header.php';
?>


<main role="main" class="container mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <img src="https://images.unsplash.com/photo-1539628399213-d6aa89c93074?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="card-img-top" alt="Quiz Website">
                <div class="card-body">
                    <h2 class="card-title">About Us</h2>
                    <p class="card-text">Welcome to Quiz Website, your number one source for quizzes. We're dedicated to
                        providing you the very best of quizzes, with an emphasis on accuracy, variety, and fun.</p>
                    <p class="card-text">Founded in [Year], Quiz Website has come a long way from its beginnings. We
                        hope you enjoy our quizzes as much as we enjoy offering them to you. If you have any questions
                        or comments, please don't hesitate to contact us.</p>
                    <p class="card-text">Sincerely,<br>The Quiz Website Team</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>