CREATE TABLE users (
   id INT AUTO_INCREMENT PRIMARY KEY,
   username VARCHAR(50) NOT NULL,
   password VARCHAR(255) NOT NULL,
   email VARCHAR(100),
   full_name VARCHAR(100) NOT NULL,
   registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_types (
   id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(50) NOT NULL,
   description TEXT
);

CREATE TABLE user_user_type (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   user_type_id INT NOT NULL,
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (user_type_id) REFERENCES user_types(id)
);

CREATE TABLE permissions (
   id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(50) NOT NULL,
   description TEXT
);

CREATE TABLE user_permission (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   permission_id INT NOT NULL,
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (permission_id) REFERENCES permissions(id)
);

CREATE TABLE contact_information (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT,
   name VARCHAR(100) NOT NULL,
   email VARCHAR(100) NOT NULL,
   phone_number VARCHAR(20),
   message TEXT,
   submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE colleges (
   id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(100) NOT NULL,
   location VARCHAR(100),
   website VARCHAR(255),
   description TEXT,
   admission_deadline DATE
);

CREATE TABLE user_colleges (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   college_id INT,
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (college_id) REFERENCES colleges(id)
);

CREATE TABLE categories (
   id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(100) NOT NULL
);

CREATE TABLE quiz_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL,
    description TEXT,
    time_limit_minutes INT,
    is_randomized BOOLEAN,
    allow_review BOOLEAN,
    allow_skipping BOOLEAN,
    show_correct_answers BOOLEAN,
    pass_percentage DECIMAL(5,2),
    attempts_limit INT,
    display_score BOOLEAN,
    allow_backtracking BOOLEAN,
    shuffle_options BOOLEAN,
    allow_partial_credit BOOLEAN,
    penalty_for_wrong_answer BOOLEAN,
    points_per_question INT
);

CREATE TABLE questions (
   id INT AUTO_INCREMENT PRIMARY KEY,
   category_id INT NOT NULL,
   difficulty_level INT NOT NULL,
   question_text TEXT NOT NULL,
   question_image BLOB,
   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   explanation TEXT,
   FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE answers (
   id INT AUTO_INCREMENT PRIMARY KEY,
   question_id INT NOT NULL,
   answer_text TEXT NOT NULL,
   is_correct BOOLEAN NOT NULL,
   FOREIGN KEY (question_id) REFERENCES questions(id)
);

CREATE TABLE scores (
   id INT AUTO_INCREMENT PRIMARY KEY,
   user_id INT NOT NULL,
   quiz_id INT NOT NULL,
   score INT NOT NULL,
   test_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   start_time DATETIME,
   end_time DATETIME,
   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (quiz_id) REFERENCES quiz_types(id)
);

CREATE TABLE difficulty_levels (
   id INT AUTO_INCREMENT PRIMARY KEY,
   name VARCHAR(50) NOT NULL,
   level INT NOT NULL
);

INSERT INTO difficulty_levels (name, level) VALUES
('Easy', 1),
('Medium', 2),
('Hard', 3);

INSERT INTO user_types (name, description) VALUES
('Admin', 'Administrator with full access'),
('Teacher', 'Educator with privileges to create quizzes and view scores'),
('Student', 'Regular student with limited access');

INSERT INTO permissions (name, description) VALUES
('Create Quiz', 'Permission to create and edit quizzes'),
('View Scores', 'Permission to view quiz scores'),
('Manage Users', 'Permission to manage user accounts');

INSERT INTO users (username, password, email, full_name, registration_date)
VALUES ('admin', 'admin_password', 'admin@example.com', 'Admin User', NOW()),
       ('teacher', 'teacher_password', 'teacher@example.com', 'Teacher User', NOW()),
       ('student', 'student_password', 'student@example.com', 'Student User', NOW());


-- Admin
INSERT INTO user_user_type (user_id, user_type_id)
SELECT id, ut.id
FROM users u
CROSS JOIN user_types ut
WHERE u.username = 'admin' AND ut.name = 'Admin';

-- Teacher
INSERT INTO user_user_type (user_id, user_type_id)
SELECT id, ut.id
FROM users u
CROSS JOIN user_types ut
WHERE u.username = 'teacher' AND ut.name = 'Teacher';

-- Student
INSERT INTO user_user_type (user_id, user_type_id)
SELECT id, ut.id
FROM users u
CROSS JOIN user_types ut
WHERE u.username = 'student' AND ut.name = 'Student';

-- Admin permissions
INSERT INTO user_permission (user_id, permission_id)
SELECT u.id, p.id
FROM users u
CROSS JOIN permissions p
WHERE u.username = 'admin';

-- Teacher permissions
INSERT INTO user_permission (user_id, permission_id)
SELECT u.id, p.id
FROM users u
CROSS JOIN permissions p
WHERE u.username = 'teacher' AND p.name IN ('Create Quiz', 'View Scores');

-- Student permissions
INSERT INTO user_permission (user_id, permission_id)
SELECT u.id, p.id
FROM users u
CROSS JOIN permissions p
WHERE u.username = 'student' AND p.name = 'View Scores';

