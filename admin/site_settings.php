<?php
// Include the database connection and necessary functions
include_once dirname(__DIR__). '/bootstrap.php';

// Fetch site settings
$siteSettings = $db->getSingleRow("SELECT * FROM siteSettings WHERE id = 1");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $aboutUsContent = $_POST['aboutUsContent'];
    $aboutUsImage = isset($_FILES['aboutUsImage']['tmp_name']) ? $_FILES['aboutUsImage']['tmp_name'] : null;
    $contactAddress = $_POST['contactAddress'];
    $contactEmail = $_POST['contactEmail'];
    $contactPhone = $_POST['contactPhone'];
    $HomeAboutUsMission = $_POST['HomeAboutUsMission'];
    $HomeAboutUsTeam = $_POST['HomeAboutUsTeam'];
    $HomeHeroTitle = $_POST['HomeHeroTitle'];
    $HomeHeroSubTitle = $_POST['HomeHeroSubTitle'];
    $HomeHeroNote = $_POST['HomeHeroNote'];
    $SiteLogo = isset($_FILES['SiteLogo']['tmp_name']) ? $_FILES['SiteLogo']['tmp_name'] : null;
    $HeroImage = isset($_FILES['HeroImage']['tmp_name']) ? $_FILES['HeroImage']['tmp_name'] : null;
    // Handle other form fields similarly

    // Update site settings in the database
    $update_data = [
        'aboutUsContent' => $aboutUsContent,
        'contactAddress' => $contactAddress,
        'contactEmail' => $contactEmail,
        'contactPhone' => $contactPhone,
        'HomeAboutUsMission' => $HomeAboutUsMission,
        'HomeAboutUsTeam' => $HomeAboutUsTeam,
        'HomeHeroTitle' => $HomeHeroTitle,
        'HomeHeroSubTitle' => $HomeHeroSubTitle,
        'HomeHeroNote' => $HomeHeroNote,
    ];
    var_dump($aboutUsImage);
    if ($aboutUsImage !== null && !empty($aboutUsImage)) {
        $update_data['aboutUsImage'] = file_get_contents($aboutUsImage);
    }
    
    if ($SiteLogo !== null && !empty($SiteLogo)) {
        $update_data['SiteLogo'] = file_get_contents($SiteLogo);
    }
    
    if ($HeroImage !== null && !empty($HeroImage)) {
        $update_data['HeroImage'] = file_get_contents($HeroImage);
    }
    
    $update_result = $db->update("siteSettings", $update_data, "id = 1");

    if ($update_result) {
        // Redirect to the settings page or any other page
        adminMessageRedirect("Settings updated successfully.", "site_settings.php", true);
    } else {
        // Handle update failure
        adminMessageRedirect("Failed to update settings. Please try again.", "site_settings.php", false);
    }
}
?>
<?php include __DIR__ . "/include/header.php";  ?>
<?php include __DIR__ . "/include/navbar.php";  ?>
<?php include __DIR__ . "/include/sidebar.php";  ?>
<!-- Main content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Site Settings</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Site Settings Edit Form -->
                    <div class="card">
                        <div class="card-body">
                            <form class="row" action="" method="post" enctype="multipart/form-data">
                                <div class="form-group col-6">
                                    <label for="HomeHeroTitle">Home Hero Title:</label>
                                    <input type="text" class="form-control" id="HomeHeroTitle" name="HomeHeroTitle"
                                        value="<?= $siteSettings['HomeHeroTitle'] ?>" required>
                                </div>
                                <div class="form-group col-6">
                                    <label for="HomeHeroSubTitle">Home Hero Subtitle:</label>
                                    <input type="text" class="form-control" id="HomeHeroSubTitle"
                                        name="HomeHeroSubTitle" value="<?= $siteSettings['HomeHeroSubTitle'] ?>"
                                        required>
                                </div>
                                <div class="form-group col-6">
                                    <label for="HomeHeroNote">Home Hero Note:</label>
                                    <textarea class="form-control" id="HomeHeroNote" name="HomeHeroNote"
                                        required><?= $siteSettings['HomeHeroNote'] ?></textarea>
                                </div>
                                <div class="form-group col-6">
                                    <label for="HomeAboutUsMission">Home About Us Mission:</label>
                                    <textarea class="form-control" id="HomeAboutUsMission" name="HomeAboutUsMission"
                                        required><?= $siteSettings['HomeAboutUsMission'] ?></textarea>
                                </div>
                                <div class="form-group col-6">
                                    <label for="HomeAboutUsTeam">Home About Us Team:</label>
                                    <textarea class="form-control" id="HomeAboutUsTeam" name="HomeAboutUsTeam"
                                        required><?= $siteSettings['HomeAboutUsTeam'] ?></textarea>
                                </div>
                                <div class="form-group col-6">
                                    <label for="aboutUsContent">About Us Content:</label>
                                    <textarea class="form-control" id="aboutUsContent" name="aboutUsContent"
                                        required><?= $siteSettings['aboutUsContent'] ?></textarea>
                                </div>
                                <div class="form-group col-6">
                                    <label for="contactAddress">Contact Address:</label>
                                    <input type="text" class="form-control" id="contactAddress" name="contactAddress"
                                        value="<?= $siteSettings['contactAddress'] ?>" required>
                                </div>
                                <div class="form-group col-6">
                                    <label for="contactEmail">Contact Email:</label>
                                    <input type="email" class="form-control" id="contactEmail" name="contactEmail"
                                        value="<?= $siteSettings['contactEmail'] ?>" required>
                                </div>
                                <div class="form-group col-6">
                                    <label for="contactPhone">Contact Phone:</label>
                                    <input type="text" class="form-control" id="contactPhone" name="contactPhone"
                                        value="<?= $siteSettings['contactPhone'] ?>" required>
                                </div>
                                <div class="col-12"></div>
                                <?php if(!empty($siteSettings['SiteLogo'])): ?>
                                <div class="form-group col-3">
                                    <label for="SiteLogo">Site Logo:</label>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($siteSettings['SiteLogo']) ?>"
                                        alt="Site Logo" class="img-fluid">
                                    <input type="file" class="form-control-file" id="SiteLogo" name="SiteLogo">
                                </div>
                                <?php endif; ?>
                                <?php if(!empty($siteSettings['HeroImage'])): ?>
                                <div class="form-group col-3">
                                    <label for="HeroImage">Hero Image:</label>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($siteSettings['HeroImage']) ?>"
                                        alt="Hero Image" class="img-fluid">
                                    <input type="file" class="form-control-file" id="HeroImage" name="HeroImage">
                                </div>
                                <?php endif; ?>
                                <?php if(!empty($siteSettings['aboutUsImage'])): ?>
                                <div class="form-group col-3">
                                    <label for="aboutUsImage">About Us Image:</label>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($siteSettings['aboutUsImage']) ?>"
                                        alt="About Us Image" class="img-fluid">
                                    <input type="file" class="form-control-file" id="aboutUsImage" name="aboutUsImage">
                                </div>
                                <?php endif; ?>
                                <!-- Add more fields for other site settings -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
// Get all textarea elements with the class 'dynamic-textarea'
var textareas = document.querySelectorAll('textarea');

// Attach an input event listener to each textarea
textareas.forEach(function(textarea) {
    adjustTextarea(textarea);
    textarea.addEventListener('input', function() {
        console.log('Input event fired');
        adjustTextarea(this);
    });
});

function adjustTextarea(textarea) {
    var maxRows = parseInt(textarea.getAttribute('maxRows'), 10) || 10;
    var lineHeight = parseFloat(window.getComputedStyle(textarea).lineHeight);
    var paddingTop = parseFloat(window.getComputedStyle(textarea).paddingTop);
    var paddingBottom = parseFloat(window.getComputedStyle(textarea).paddingBottom);
    var borderTopWidth = parseFloat(window.getComputedStyle(textarea).borderTopWidth);
    var borderBottomWidth = parseFloat(window.getComputedStyle(textarea).borderBottomWidth);

    // Calculate the height of the content
    var contentHeight = textarea.scrollHeight - paddingTop - paddingBottom - borderTopWidth - borderBottomWidth;
    console.log('Content height:', contentHeight);

    // Calculate the number of rows based on content height and line height
    var rows = Math.ceil(contentHeight / lineHeight);
    console.log('Number of rows:', rows);

    // Adjust the rows to be within the min and max limits
    rows = Math.min(maxRows, rows);
    console.log('Adjusted rows:', rows);

    // Set the rows attribute of the textarea
    textarea.rows = rows;
}
</script>
<?php include __DIR__ . "/include/footer.php";  ?>