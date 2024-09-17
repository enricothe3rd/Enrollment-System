CREATE DATABASE enrollment_system;

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
);

CREATE TABLE enrollment (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    student_id INT(11) NOT NULL,
    student_number VARCHAR(20) UNIQUE,
    firstname VARCHAR(50) NOT NULL,
    middlename VARCHAR(50),
    lastname VARCHAR(50) NOT NULL,
    suffix VARCHAR(10),
    status ENUM('pending','confirmed','rejected','New Student','Old Student','Regular','Irregular','Transferee') DEFAULT 'New Student',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    school_year VARCHAR(10),
    semester VARCHAR(20),
    sex VARCHAR(10),
    dob DATE,
    address TEXT,
    email VARCHAR(100),
    contact_no VARCHAR(20),
    statusofenrollment ENUM('pending', 'verifying', 'enrolled', 'rejected', 'incomplete') DEFAULT 'pending',
    CONSTRAINT fk_student_id FOREIGN KEY (student_id) REFERENCES users(id)
);

CREATE TABLE enrollment_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(255) NOT NULL
);

CREATE TABLE subject_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    student_number VARCHAR(50) NOT NULL,
    subject_id INT NOT NULL,
    code VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    units INT NOT NULL,
    room VARCHAR(50),
    day VARCHAR(10),
    start_time TIME,
    end_time TIME,
    payment_status VARCHAR(50) NOT NULL,
    FOREIGN KEY (student_id) REFERENCES enrollment(student_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

CREATE TABLE school_years (
    id INT AUTO_INCREMENT PRIMARY KEY,
    year VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE semesters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    semester VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE status_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(255) NOT NULL UNIQUE
);


CREATE TABLE sex_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sex_name VARCHAR(255) NOT NULL UNIQUE
);









CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(255) NOT NULL,  -- Changed to course_name to match your code
    department_id INT,
    CONSTRAINT fk_department FOREIGN KEY (department_id) REFERENCES departments(id)
);

CREATE TABLE sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    course_id INT,
    CONSTRAINT fk_section_course FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    section_id INT,
    units INT,
    course_id INT;

);



CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    day_of_week VARCHAR(10) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    room VARCHAR(50) NOT NULL,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);


