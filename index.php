<?php include_once 'includes/header.php'; ?>

<style>
.jumbotron-shadow {
    background-image: url('data:image/jpeg;base64,<?= base64_encode($siteSettings['HeroImage']) ?>');
    background-size: cover;
    background-position: center;
    box-shadow: inset 0 0 20px 1000px rgba(0, 0, 0, 0.5);
    padding: 80px 0;
    color: white;
}

/* Custom CSS for horizontal lines between sections */
hr {
    margin-top: 1px;
    /* Adjust the margin as needed */
    margin-bottom: 1px;
    /* Adjust the margin as needed */
    border-top: 1px solid #ccc;
    /* Color and style of the horizontal line */
}

.feature-icon {
    font-size: 3rem;
    color: #007bff;
    /* Update color as desired */
}

.testimonial-card {
    background-color: #f8f9fa;
    /* Update background color as desired */
    border: 1px solid #dee2e6;
    /* Update border color as desired */
}

.square-img-50 {
    width: 50%;
    aspect-ratio: 1/1;
    object-fit: cover;
}

.square-img {
    width: 75%;
    aspect-ratio: 1/1;
    object-fit: cover;
}
</style>

<!-- Jumbotron -->
<div class="jumbotron jumbotron-fluid bg-dark text-white jumbotron-shadow">
    <div class="container text-center">
        <h1 class="display-3 mb-4"><?=$siteSettings['HomeHeroTitle']?></h1>
        <p class="lead mb-4"><?=$siteSettings['HomeHeroSubTitle']?></p>
        <a class="btn btn-primary btn-lg mb-4" href="<?=BASE_URL?>/quiz.php" role="button">Browse Quizzes</a>
        <p class="mb-0"><?=$siteSettings['HomeHeroNote']?></p>
    </div>
</div>

<hr />

<!-- Features Section -->
<!-- Features Section -->
<!-- Key Features Section -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Key Features</h2>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-question feature-icon mb-3"></i>
                        <h3 class="mb-3">Wide Range of Quizzes</h3>
                        <p>Explore quizzes on different topics including science, history, sports, and more.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-play-circle feature-icon mb-3"></i>
                        <h3 class="mb-3">Interactive Experience</h3>
                        <p>Engage in interactive quizzes that challenge your knowledge and provide instant feedback.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-star feature-icon mb-3"></i>
                        <h3 class="mb-3">Track Your Progress</h3>
                        <p>Create an account to track your quiz history and achievements.</p>
                    </div>
                </div>
            </div>
            <!-- Add more feature cards with icons -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-medal feature-icon mb-3"></i>
                        <h3 class="mb-3">Achievements</h3>
                        <p>Earn achievements as you progress through quizzes and challenges.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-friends feature-icon mb-3"></i>
                        <h3 class="mb-3">Social Sharing</h3>
                        <p>Share your quiz results and achievements with friends on social media.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-stopwatch feature-icon mb-3"></i>
                        <h3 class="mb-3">Timed Quizzes</h3>
                        <p>Challenge yourself with timed quizzes to test your knowledge under pressure.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-trophy feature-icon mb-3"></i>
                        <h3 class="mb-3">Leaderboards</h3>
                        <p>Compete with other users and climb the leaderboards by earning top scores.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-certificate feature-icon mb-3"></i>
                        <h3 class="mb-3">Certificates</h3>
                        <p>Receive certificates of achievement upon completing certain quizzes or challenges.</p>
                    </div>
                </div>
            </div>
            <!-- Add more feature cards here -->
        </div>
    </div>
</section>



<hr />

<!-- About Us Section -->
<section class="py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-4">About Us</h2>
        <div class="row">
            <div class="col-lg-6">
                <h3>Our Mission</h3>
                <p><?=$siteSettings['HomeAboutUsMission']?></p>
            </div>
            <div class="col-lg-6">
                <h3>Our Team</h3>
                <p><?=$siteSettings['HomeAboutUsTeam']?></p>
            </div>
        </div>
    </div>
</section>

<hr />

<!-- Journey Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Our Journey</h2>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-3">Step 1: Inception</h3>
                        <p class="mb-4">Our journey began with a simple idea: to create a platform where people could
                            test their knowledge in an engaging way.</p>
                        <img src="https://images.unsplash.com/photo-1541448232224-93f4a582b89d?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="square-img-50 rounded-circle mb-3" alt="Step 1">
                        <footer class="blockquote-footer">Date: January 2020</footer>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-3">Step 2: Development</h3>
                        <p class="mb-4">With dedication and hard work, we started developing a wide range of quizzes
                            covering various topics.</p>
                        <img src="https://images.unsplash.com/photo-1516373829531-29d21ac7f9d6?q=80&w=1904&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="square-img-50 rounded-circle mb-3" alt="Step 2">
                        <footer class="blockquote-footer">Date: March 2020</footer>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-3">Step 3: Expansion</h3>
                        <p class="mb-4">As our platform grew, we introduced interactive features to enhance the user
                            experience and attract more users.</p>
                        <img src="https://images.unsplash.com/photo-1556804599-f7d0c84b61b8?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="square-img-50 rounded-circle mb-3" alt="Step 3">
                        <footer class="blockquote-footer">Date: May 2020</footer>
                    </div>
                </div>
            </div>
            <!-- Add more journey steps here -->
        </div>
    </div>
</section>


<hr />

<!-- Testimonials Section -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Testimonials</h2>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <img src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?q=80&w=1780&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="rounded-circle square-img mb-3" alt="Testimonial 1">
                        <p class="mb-4">"The quiz website provided an excellent platform for expanding my knowledge in
                            various subjects. It's engaging and fun!"</p>
                        <footer class="blockquote-footer">John Doe</footer>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="rounded-circle square-img mb-3" alt="Testimonial 2">
                        <p class="mb-4">"I love the interactive quizzes! They really challenge my knowledge and provide
                            instant feedback, making learning enjoyable." </p>
                        <footer class="blockquote-footer">Jane Doe</footer>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <img src="https://plus.unsplash.com/premium_photo-1683121366070-5ceb7e007a97?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="rounded-circle square-img mb-3" alt="Testimonial 3">
                        <p class="mb-4">"The timed quizzes are a great way to test my knowledge under pressure. It's
                            both challenging and rewarding!"</p>
                        <footer class="blockquote-footer">James Smith</footer>
                    </div>
                </div>
            </div>
            <!-- Add more testimonial cards here -->
        </div>
    </div>
</section>



<?php include_once 'includes/footer.php'; ?>