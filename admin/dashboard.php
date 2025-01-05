<?php
require_once(dirname(__DIR__) . '/bootstrap.php');

// Create a new instance of the Database class
$db = new Database();

// Function to get total count from a table
function getTotalCount($tableName, $where = "")
{
    global $db;
    $row = $db->getSingleRow("SELECT COUNT(*) AS total FROM $tableName $where");
    return $row['total'];
}

// Function to get total passed and failed quiz attempts
function getTotalQuizAttemptsStatus()
{
    global $db;
    $passed = $db->getSingleValue("SELECT COUNT(*) FROM quiz_attempts WHERE passed = 1");
    $failed = $db->getSingleValue("SELECT COUNT(*) FROM quiz_attempts WHERE passed = 0");
    return array('passed' => $passed, 'failed' => $failed);
}
// Function to get the distribution of quiz types
function getQuizTypeDistribution()
{
    global $db;
    $data = array();
    $quizTypes = $db->getMultipleRows("SELECT type_name, COUNT(*) AS count FROM quiz_types GROUP BY type_name");
    foreach ($quizTypes as $type) {
        $data['labels'][] = $type['type_name'];
        $data['data'][] = $type['count'];
    }
    return $data;
}
?>

<?php include __DIR__ . "/include/header.php"; ?>
<?php include __DIR__ . "/include/navbar.php"; ?>
<?php include __DIR__ . "/include/sidebar.php"; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <!-- Your page content here -->
        <div class="container-fluid">
            <div class="row pt-3">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Quizzes</span>
                            <span class="info-box-number"><?php echo getTotalCount('quizzes'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Students</span>
                            <span
                                class="info-box-number"><?php echo getTotalCount('users', "WHERE userType='STUDENT'"); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-chalkboard-teacher"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Teachers</span>
                            <span
                                class="info-box-number"><?php echo getTotalCount('users', "WHERE userType='TEACHER'"); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-poll"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Quiz Attempts</span>
                            <span class="info-box-number"><?php echo getTotalCount('quiz_attempts'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Additional Info -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-tags"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Categories</span>
                            <span class="info-box-number"><?php echo getTotalCount('categories'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-university"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Colleges</span>
                            <span class="info-box-number"><?php echo getTotalCount('colleges'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-dark"><i class="fas fa-puzzle-piece"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Quiz Types</span>
                            <span class="info-box-number"><?php echo getTotalCount('quiz_types'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-user-md"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Doctors</span>
                            <span class="info-box-number"><?php echo getTotalQuizAttemptsStatus()['passed']; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-book"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Books</span>
                            <span class="info-box-number"><?php echo getTotalQuizAttemptsStatus()['passed']; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-check-circle"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Passed Quiz Attempts</span>
                            <span class="info-box-number"><?php echo getTotalQuizAttemptsStatus()['passed']; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-times-circle"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Failed Quiz Attempts</span>
                            <span class="info-box-number"><?php echo getTotalQuizAttemptsStatus()['failed']; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12"></div>
                <!-- New Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quiz Attempts Status</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="quizAttemptsChart" style="height:250px"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quiz Type Distribution</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="quizTypeDistributionChart" style="height:250px"></canvas>
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

<?php include __DIR__ . "/include/footer.php"; ?>
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<script>
// Data for the quiz attempts chart
var quizAttemptsData = {
    labels: ['Passed', 'Failed'],
    datasets: [{
        label: 'Quiz Attempts',
        backgroundColor: ['#28a745', '#dc3545'],
        borderColor: ['#28a745', '#dc3545'],
        borderWidth: 1,
        data: [<?php echo getTotalQuizAttemptsStatus()['passed']; ?>,
            <?php echo getTotalQuizAttemptsStatus()['failed']; ?>
        ]
    }]
};

// Options for the quiz attempts chart
var quizAttemptsOptions = {
    scales: {
        y: {
            beginAtZero: true
        }
    }
};

// Data for the quiz type distribution chart
var quizTypeDistributionData = {
    labels: <?php echo json_encode(getQuizTypeDistribution()['labels']); ?>,
    datasets: [{
        label: 'Quiz Type Distribution',
        backgroundColor: ['#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14', '#28a745', '#ffc107',
            '#dc3545', '#20c997', '#17a2b8'
        ],
        borderWidth: 1,
        data: <?php echo json_encode(getQuizTypeDistribution()['data']); ?>
    }]
};

// Options for the quiz type distribution chart
var quizTypeDistributionOptions = {};

// Get the canvas elements
var quizAttemptsCanvas = document.getElementById('quizAttemptsChart').getContext('2d');
var quizTypeDistributionCanvas = document.getElementById('quizTypeDistributionChart').getContext('2d');

// Create the charts
var quizAttemptsChart = new Chart(quizAttemptsCanvas, {
    type: 'bar',
    data: quizAttemptsData,
    options: quizAttemptsOptions
});

var quizTypeDistributionChart = new Chart(quizTypeDistributionCanvas, {
    type: 'pie',
    data: quizTypeDistributionData,
    options: quizTypeDistributionOptions
});
</script>