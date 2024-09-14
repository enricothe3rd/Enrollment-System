<!-- 


CREATE DATABASE token_db1; 


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50),
    status VARCHAR(50) DEFAULT 'inactive',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    email_confirmed TINYINT(1) NOT NULL DEFAULT 0,
    failed_attempts INT DEFAULT 0,
    account_locked TINYINT(1) DEFAULT 0,
    lock_time DATETIME NULL;

);

--     ALTER TABLE users 
-- ADD COLUMN failed_attempts INT DEFAULT 0,
-- ADD COLUMN account_locked TINYINT(1) DEFAULT 0,
-- ADD COLUMN lock_time DATETIME NULL;

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    expires_at DATETIME NOT NULL
);


CREATE TABLE user_registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
); -->

<!-- -- Create the courses table
CREATE TABLE courses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create the enrollment table with a foreign key to courses
CREATE TABLE enrollment (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    middlename VARCHAR(50),
    lastname VARCHAR(50) NOT NULL,
    suffix VARCHAR(10),
    status ENUM('pending','confirmed','rejected','New Student','Old Student','Regular','Irregular','Transferee') DEFAULT 'New Student',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    year VARCHAR(10),
    -- course_id INT(11),
    sex VARCHAR(10),
    dob DATE,
    address TEXT,
    email VARCHAR(100),
    contact_no VARCHAR(20),
    statusofenrollment ENUM('pending', 'verifying', 'enrolled', 'rejected', 'incomplete') DEFAULT 'pending';
    CONSTRAINT fk_student_id FOREIGN KEY (student_id) REFERENCES users(id),
    -- Drop na CONSTRAINT fk_course_id FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE status_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(255) NOT NULL
);
CREATE TABLE sex_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sex_name VARCHAR(50) NOT NULL
);

CREATE TABLE enrollment_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(255) NOT NULL
);

-->
CREATE TABLE courses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE classes (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE sections (
    id INT(11) NOT NULL AUTO_INCREMENT,
    class_id INT(11) NOT NULL,
    section_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY (class_id)
);

CREATE TABLE subjects (
    id INT(11) NOT NULL AUTO_INCREMENT,
    class_id INT(11) NOT NULL,
    code VARCHAR(20) NOT NULL,
    subject_title VARCHAR(100) NOT NULL,
    units DECIMAL(5,2) NOT NULL,
    room VARCHAR(50) DEFAULT NULL,
    day VARCHAR(50) DEFAULT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY (class_id)
);

CREATE TABLE subject_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);
