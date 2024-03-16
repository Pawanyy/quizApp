<?php
// Include the database connection and necessary functions
include_once __DIR__ . '/bootstrap.php';

// Fetch colleges from the database
$colleges = $db->getMultipleRows("SELECT * FROM colleges ORDER BY college_level ASC");

// Check if the user is logged in and fetch quiz attempt information
if(isset($_SESSION['username'])) {
    $quiz_attempt_info = $db->getSingleRow("SELECT percentage, passed 
                                            FROM quiz_attempts 
                                            WHERE user_id = {$_SESSION['user_id']} 
                                            AND passed = true
                                            ORDER BY id DESC 
                                            LIMIT 1");
}

include_once 'includes/header.php';
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid mb-5">
        <div class="row pt-3">
            <div class="col-12">
                <h2 class="section-header">Available Colleges</h2>
                <?php
                if(!isset($_SESSION['username'])) {
                ?>
                <h5 class="text-muted">Login and Check Quiz to find Qualifying Colleges</h5>
                <?php } ?>
            </div>
        </div>

        <!-- Filter for College Level -->
        <div class="row pt-3">
            <div class="col-3">
                <label for="collegeLevel">Filter by College Level:</label>
                <select class="form-control" id="collegeLevel">
                    <option value="all">All</option>
                    <option value="elementary">Elementary</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="top">Top</option>
                </select>
            </div>
        </div>

        <!-- Show Qualifying Colleges Checkbox -->
        <?php if(isset($_SESSION['username'])): ?>
        <div class="row pt-3">
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="showQualifyingColleges">
                    <label class="form-check-label" for="showQualifyingColleges">Show Qualifying Colleges</label>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- College Cards -->
        <div class="row pt-3" id="collegeCards">
            <?php foreach ($colleges as $college): ?>
            <div class="col-12 col-md-4 mb-4 college-card" data-college-level="<?= $college['college_level'] ?>"
                data-qualifying="<?= (isset($_SESSION['username']) && $quiz_attempt_info && $college['cut_off_percentage'] <= $quiz_attempt_info['percentage']) ? 'true' : 'false' ?>">
                <!-- College Card -->
                <div class="card h-100 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><?= $college['name'] ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?= $college['description'] ?></p>
                        <ul class="list-group list-group-flush rounded border">
                            <li class="list-group-item"><strong>Location:</strong> <?= $college['location'] ?></li>
                            <li class="list-group-item"><strong>Website:</strong> <a
                                    href="<?php echo ((!$college['website'] || $college['website'] == "NA") ? "#" : $college['website']);  ?>"
                                    target="_blank"><?= $college['website'] ?></a></li>
                            <li class="list-group-item"><strong>Cut Off Percentage:</strong>
                                <?= $college['cut_off_percentage'] ?></li>
                            <li class="list-group-item"><strong>College Level:</strong> <?= $college['college_level'] ?>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <!-- Add any additional actions or buttons here -->
                        <div class=""><strong>Admission Deadline:</strong>
                            <?= $college['admission_deadline'] ?></div>
                    </div>
                </div>
                <!-- End College Card -->
            </div>
            <?php endforeach; ?>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php include_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var showQualifyingCollegesEle = document.getElementById('showQualifyingColleges');
    var selectLevelEle = document.getElementById('collegeLevel');

    // JavaScript for filtering college cards based on selected level and qualifying checkbox
    function filterColleges() {

        var selectedLevel = selectLevelEle.value;
        var showQualifyingColleges = showQualifyingCollegesEle ? showQualifyingCollegesEle.checked : null;
        var collegeCards = document.querySelectorAll('.college-card');


        collegeCards.forEach(function(card) {

            var level = card.getAttribute('data-college-level');
            var isQualifying = card.getAttribute('data-qualifying') === 'true';

            console.log("Changed 1", showQualifyingColleges);


            if (showQualifyingColleges !== null && showQualifyingColleges) {
                if ((selectedLevel === 'all' || level === selectedLevel) && (isQualifying)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            } else {
                if (selectedLevel === 'all' || level === selectedLevel) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }


        });
    }

    // Call the filter function initially and add event listeners
    filterColleges();

    // Add event listeners to the dropdown and checkbox
    if (selectLevelEle) {
        selectLevelEle.addEventListener('change', filterColleges);
    }

    if (showQualifyingCollegesEle) {
        showQualifyingCollegesEle.addEventListener('change', filterColleges);
    }
});
</script>