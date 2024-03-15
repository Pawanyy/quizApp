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
                <img src="data:image/jpeg;base64,<?= base64_encode($siteSettings['aboutUsImage']) ?>"
                    class="card-img-top" alt="Quiz Website">
                <div class="card-body">
                    <h2 class="card-title">About Us</h2>
                    <p class="card-text">
                        <?=$siteSettings['aboutUsContent']?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>