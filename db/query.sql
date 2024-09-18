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
--   failed_attempts INT DEFAULT 0,
--   account_locked TINYINT(1) DEFAULT 0,
--   lock_time DATETIME NULL;

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
    student_number VARCHAR(255) NOT NULL,
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

latest
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

-->
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE semesters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    semester_name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
   section_id INT;

    CONSTRAINT fk_section_course FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    section_id INT,
    units INT,
    semester_id INT;


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




CREATE TABLE subject_enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

     code VARCHAR(20),
     title VARCHAR(255),
     units INT,
     room VARCHAR(50),
    day VARCHAR(20),
    start_time TIME,
    end_time TIME,
    payment_status ENUM('pending', 'completed') DEFAULT 'pending'

);

CREATE TABLE instructors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    department_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE classrooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    building VARCHAR(255) NOT NULL
);

CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_number VARCHAR(255) NOT NULL UNIQUE,
    firstname VARCHAR(255) NOT NULL,
    middlename VARCHAR(255),
    lastname VARCHAR(255) NOT NULL,
    suffix VARCHAR(50),
    student_type ENUM('regular', 'new student', 'irregular', 'summer') NOT NULL,
    sex ENUM('male', 'female') NOT NULL,
    dob DATE NOT NULL,
    email VARCHAR(255) NOT NULL,
    contact_no VARCHAR(255),
    course_id INT NOT NULL,
    section_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
);


CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_number VARCHAR(20) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    suffix VARCHAR(10),
    student_type ENUM('Regular', 'New', 'Irregular', 'Summer') NOT NULL,
    sex ENUM('Male', 'Female') NOT NULL,
    dob DATE NOT NULL,
    email VARCHAR(255) NOT NULL,
    contact_no VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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






-- 1 INSERTING ON COURSE OR DEPARTMENT

INSERT INTO courses (id, course_name) VALUES 
(9, 'College of Science'),
(10, 'College of Engineering'),
(11, 'College of Education');

-- 2 INSERTING ON CLASSES

-- 10  = COLLEGE OF ENGINEERING
-- 9 = COLLEGE OF SCIENCE
-- 11 = COLLEGE OF EDUCATION
INSERT INTO classes (course_id, name, description) VALUES
(10, 'Mathematics 101', 'Introduction to basic mathematics.'),
(9, 'Physics 101', 'Fundamentals of physics.'),
(11, 'Chemistry 101', 'Basics of chemistry.');




-- 3 Sample data for sections for computer science

INSERT INTO sections (class_id, section_name) VALUES
(92, '1-CS1'),
(92, '1-CS2'),
(92, '1-CS3'),
(92, '1-CS4'),
(92, '2-CS1'),
(92, '2-CS2'),
(92, '2-CS3'),
(92, '2-CS4'),
(92, '3-CS1'),
(92, '3-CS2'),
(92, '3-CS3'),
(92, '3-CS4'),
(92, '4-CS1'),
(92, '4-CS2'),
(92, '4-CS3'),
(92, '4-CS4');


-- 4 INSERTING OF SUBJECTS
INSERT INTO subjects (section_id, code, subject_title, units, room, day, start_time, end_time) VALUES

(57, '1ITP', 'Intro to Programming', 3.00, '201', 'Monday', '08:00:00', '10:00:00'),
(57, '1CS1', 'Computer Science I', 3.00, '202', 'Tuesday', '10:00:00', '12:00:00'),
(57, '1DB', 'Database Basics', 3.00, '203', 'Wednesday', '13:00:00', '15:00:00'),
(57, '1AL', 'Algorithms I', 3.00, '204', 'Thursday', '08:00:00', '10:00:00'),
(57, '1NET', 'Networking Basics', 3.00, '205', 'Friday', '10:00:00', '12:00:00'),

(58, '1ITP', 'Intro to Programming II', 3.00, '206', 'Monday', '08:00:00', '10:00:00'),
(58, '1CS2', 'Computer Science II', 3.00, '207', 'Tuesday', '10:00:00', '12:00:00'),
(58, '1DB', 'Database Design', 3.00, '208', 'Wednesday', '13:00:00', '15:00:00'),
(58, '1AL', 'Algorithms II', 3.00, '209', 'Thursday', '08:00:00', '10:00:00'),
(58, '1NET', 'Advanced Networking', 3.00, '210', 'Friday', '10:00:00', '12:00:00'),

(59, '1ITP', 'Advanced Programming', 3.00, '211', 'Monday', '08:00:00', '10:00:00'),
(59, '1CS3', 'Data Structures', 3.00, '212', 'Tuesday', '10:00:00', '12:00:00'),
(59, '1DB', 'Database Systems', 3.00, '213', 'Wednesday', '13:00:00', '15:00:00'),
(59, '1AL', 'Advanced Algorithms', 3.00, '214', 'Thursday', '08:00:00', '10:00:00'),
(59, '1NET', 'Network Security', 3.00, '215', 'Friday', '10:00:00', '12:00:00'),

(60, '1ITP', 'Programming II', 3.00, '216', 'Monday', '08:00:00', '10:00:00'),
(60, '1CS4', 'Computer Science III', 3.00, '217', 'Tuesday', '10:00:00', '12:00:00'),
(60, '1DB', 'Database Management', 3.00, '218', 'Wednesday', '13:00:00', '15:00:00'),
(60, '1AL', 'Data Algorithms', 3.00, '219', 'Thursday', '08:00:00', '10:00:00'),
(60, '1NET', 'Advanced Network Design', 3.00, '220', 'Friday', '10:00:00', '12:00:00'),


(57, '2ITP', 'Intro to Programming', 3.00, '201', 'Monday', '08:00:00', '10:00:00'),
(57, '2CS1', 'Computer Science I', 3.00, '202', 'Tuesday', '10:00:00', '12:00:00'),
(57, '2DB', 'Database Basics', 3.00, '203', 'Wednesday', '13:00:00', '15:00:00'),
(57, '2AL', 'Algorithms I', 3.00, '204', 'Thursday', '08:00:00', '10:00:00'),
(57, '2NET', 'Networking Basics', 3.00, '205', 'Friday', '10:00:00', '12:00:00'),

(58, '2ITP', 'Intro to Programming II', 3.00, '206', 'Monday', '08:00:00', '10:00:00'),
(58, '2CS2', 'Computer Science II', 3.00, '207', 'Tuesday', '10:00:00', '12:00:00'),
(58, '2DB', 'Database Design', 3.00, '208', 'Wednesday', '13:00:00', '15:00:00'),
(58, '2AL', 'Algorithms II', 3.00, '209', 'Thursday', '08:00:00', '10:00:00'),
(58, '2NET', 'Advanced Networking', 3.00, '210', 'Friday', '10:00:00', '12:00:00'),

(59, '2ITP', 'Advanced Programming', 3.00, '211', 'Monday', '08:00:00', '10:00:00'),
(59, '2CS3', 'Data Structures', 3.00, '212', 'Tuesday', '10:00:00', '12:00:00'),
(59, '2DB', 'Database Systems', 3.00, '213', 'Wednesday', '13:00:00', '15:00:00'),
(59, '2AL', 'Advanced Algorithms', 3.00, '214', 'Thursday', '08:00:00', '10:00:00'),
(59, '2NET', 'Network Security', 3.00, '215', 'Friday', '10:00:00', '12:00:00'),

(60, '2ITP', 'Programming II', 3.00, '216', 'Monday', '08:00:00', '10:00:00'),
(60, '2CS4', 'Computer Science III', 3.00, '217', 'Tuesday', '10:00:00', '12:00:00'),
(60, '2DB', 'Database Management', 3.00, '218', 'Wednesday', '13:00:00', '15:00:00'),
(60, '2AL', 'Data Algorithms', 3.00, '219', 'Thursday', '08:00:00', '10:00:00'),
(60, '2NET', 'Advanced Network Design', 3.00, '220', 'Friday', '10:00:00', '12:00:00'),


(57, 'SITP', 'Intro to Programming', 3.00, '201', 'Monday', '08:00:00', '10:00:00'),
(57, 'SCS1', 'Computer Science I', 3.00, '202', 'Tuesday', '10:00:00', '12:00:00'),
(57, 'SDB', 'Database Basics', 3.00, '203', 'Wednesday', '13:00:00', '15:00:00'),
(57, 'SAL', 'Algorithms I', 3.00, '204', 'Thursday', '08:00:00', '10:00:00'),
(57, 'SNET', 'Networking Basics', 3.00, '205', 'Friday', '10:00:00', '12:00:00'),

(58, 'SITP', 'Intro to Programming II', 3.00, '206', 'Monday', '08:00:00', '10:00:00'),
(58, 'SCS2', 'Computer Science II', 3.00, '207', 'Tuesday', '10:00:00', '12:00:00'),
(58, 'SDB', 'Database Design', 3.00, '208', 'Wednesday', '13:00:00', '15:00:00'),
(58, 'SAL', 'Algorithms II', 3.00, '209', 'Thursday', '08:00:00', '10:00:00'),
(58, 'SNET', 'Advanced Networking', 3.00, '210', 'Friday', '10:00:00', '12:00:00'),

(59, 'SITP', 'Advanced Programming', 3.00, '211', 'Monday', '08:00:00', '10:00:00'),
(59, 'SCS3', 'Data Structures', 3.00, '212', 'Tuesday', '10:00:00', '12:00:00'),
(59, 'SDB', 'Database Systems', 3.00, '213', 'Wednesday', '13:00:00', '15:00:00'),
(59, 'SAL', 'Advanced Algorithms', 3.00, '214', 'Thursday', '08:00:00', '10:00:00'),
(59, 'SNET', 'Network Security', 3.00, '215', 'Friday', '10:00:00', '12:00:00'),

(60, 'SITP', 'Programming II', 3.00, '216', 'Monday', '08:00:00', '10:00:00'),
(60, 'SCS4', 'Computer Science III', 3.00, '217', 'Tuesday', '10:00:00', '12:00:00'),
(60, 'SDB', 'Database Management', 3.00, '218', 'Wednesday', '13:00:00', '15:00:00'),
(60, 'SAL', 'Data Algorithms', 3.00, '219', 'Thursday', '08:00:00', '10:00:00'),
(60, 'SNET', 'Advanced Network Design', 3.00, '220', 'Friday', '10:00:00', '12:00:00');

---------------------

INSERT INTO subjects (section_id, code, subject_title, units, room, day, start_time, end_time) VALUES
(61, '1ITP', 'Intermediate Programming', 3.00, '221', 'Monday', '08:00:00', '10:00:00'),
(61, '1CS1', 'Software Engineering', 3.00, '222', 'Tuesday', '10:00:00', '12:00:00'),
(61, '1DB', 'Advanced Databases', 3.00, '223', 'Wednesday', '13:00:00', '15:00:00'),
(61, '1AL', 'Complex Algorithms', 3.00, '224', 'Thursday', '08:00:00', '10:00:00'),
(61, '1NET', 'Network Administration', 3.00, '225', 'Friday', '10:00:00', '12:00:00'),

(62, '1ITP', 'Software Development', 3.00, '226', 'Monday', '08:00:00', '10:00:00'),
(62, '1CS2', 'Systems Programming', 3.00, '227', 'Tuesday', '10:00:00', '12:00:00'),
(62, '1DB', 'Big Data Systems', 3.00, '228', 'Wednesday', '13:00:00', '15:00:00'),
(62, '1AL', 'Machine Learning', 3.00, '229', 'Thursday', '08:00:00', '10:00:00'),
(62, '1NET', 'Cybersecurity', 3.00, '230', 'Friday', '10:00:00', '12:00:00'),

(63, '1ITP', 'Advanced Programming', 3.00, '231', 'Monday', '08:00:00', '10:00:00'),
(63, '1CS3', 'Capstone Project I', 3.00, '232', 'Tuesday', '10:00:00', '12:00:00'),
(63, '1DB', 'Data Warehousing', 3.00, '233', 'Wednesday', '13:00:00', '15:00:00'),
(63, '1AL', 'Artificial Intelligence', 3.00, '234', 'Thursday', '08:00:00', '10:00:00'),
(63, '1NET', 'Advanced Network Security', 3.00, '235', 'Friday', '10:00:00', '12:00:00'),

(64, '1ITP', 'Full-Stack Development', 3.00, '236', 'Monday', '08:00:00', '10:00:00'),
(64, '1CS4', 'Capstone Project II', 3.00, '237', 'Tuesday', '10:00:00', '12:00:00'),
(64, '1DB', 'Cloud Databases', 3.00, '238', 'Wednesday', '13:00:00', '15:00:00'),
(64, '1AL', 'Data Mining', 3.00, '239', 'Thursday', '08:00:00', '10:00:00'),
(64, '1NET', 'Networking for Security', 3.00, '240', 'Friday', '10:00:00', '12:00:00'),



(61, '2ITP', 'Intermediate Programming', 3.00, '221', 'Monday', '08:00:00', '10:00:00'),
(61, '2CS1', 'Software Engineering', 3.00, '222', 'Tuesday', '10:00:00', '12:00:00'),
(61, '2DB', 'Advanced Databases', 3.00, '223', 'Wednesday', '13:00:00', '15:00:00'),
(61, '2AL', 'Complex Algorithms', 3.00, '224', 'Thursday', '08:00:00', '10:00:00'),
(61, '2NET', 'Network Administration', 3.00, '225', 'Friday', '10:00:00', '12:00:00'),

(62, '2ITP', 'Software Development', 3.00, '226', 'Monday', '08:00:00', '10:00:00'),
(62, '2CS2', 'Systems Programming', 3.00, '227', 'Tuesday', '10:00:00', '12:00:00'),
(62, '2DB', 'Big Data Systems', 3.00, '228', 'Wednesday', '13:00:00', '15:00:00'),
(62, '2AL', 'Machine Learning', 3.00, '229', 'Thursday', '08:00:00', '10:00:00'),
(62, '2NET', 'Cybersecurity', 3.00, '230', 'Friday', '10:00:00', '12:00:00'),

(63, '2ITP', 'Advanced Programming', 3.00, '231', 'Monday', '08:00:00', '10:00:00'),
(63, '2CS3', 'Capstone Project I', 3.00, '232', 'Tuesday', '10:00:00', '12:00:00'),
(63, '2DB', 'Data Warehousing', 3.00, '233', 'Wednesday', '13:00:00', '15:00:00'),
(63, '2AL', 'Artificial Intelligence', 3.00, '234', 'Thursday', '08:00:00', '10:00:00'),
(63, '2NET', 'Advanced Network Security', 3.00, '235', 'Friday', '10:00:00', '12:00:00'),

(64, '2ITP', 'Full-Stack Development', 3.00, '236', 'Monday', '08:00:00', '10:00:00'),
(64, '2CS4', 'Capstone Project II', 3.00, '237', 'Tuesday', '10:00:00', '12:00:00'),
(64, '2DB', 'Cloud Databases', 3.00, '238', 'Wednesday', '13:00:00', '15:00:00'),
(64, '2AL', 'Data Mining', 3.00, '239', 'Thursday', '08:00:00', '10:00:00'),
(64, '2NET', 'Networking for Security', 3.00, '240', 'Friday', '10:00:00', '12:00:00'),


(61, 'SITP', 'Intermediate Programming', 3.00, '221', 'Monday', '08:00:00', '10:00:00'),
(61, 'SCS1', 'Software Engineering', 3.00, '222', 'Tuesday', '10:00:00', '12:00:00'),
(61, 'SDB', 'Advanced Databases', 3.00, '223', 'Wednesday', '13:00:00', '15:00:00'),
(61, 'SAL', 'Complex Algorithms', 3.00, '224', 'Thursday', '08:00:00', '10:00:00'),
(61, 'SNET', 'Network Administration', 3.00, '225', 'Friday', '10:00:00', '12:00:00'),

(62, 'SITP', 'Software Development', 3.00, '226', 'Monday', '08:00:00', '10:00:00'),
(62, 'SCS2', 'Systems Programming', 3.00, '227', 'Tuesday', '10:00:00', '12:00:00'),
(62, 'SDB', 'Big Data Systems', 3.00, '228', 'Wednesday', '13:00:00', '15:00:00'),
(62, 'SAL', 'Machine Learning', 3.00, '229', 'Thursday', '08:00:00', '10:00:00'),
(62, 'SNET', 'Cybersecurity', 3.00, '230', 'Friday', '10:00:00', '12:00:00'),

(63, 'SITP', 'Advanced Programming', 3.00, '231', 'Monday', '08:00:00', '10:00:00'),
(63, 'SCS3', 'Capstone Project I', 3.00, '232', 'Tuesday', '10:00:00', '12:00:00'),
(63, 'SDB', 'Data Warehousing', 3.00, '233', 'Wednesday', '13:00:00', '15:00:00'),
(63, 'SAL', 'Artificial Intelligence', 3.00, '234', 'Thursday', '08:00:00', '10:00:00'),
(63, 'SNET', 'Advanced Network Security', 3.00, '235', 'Friday', '10:00:00', '12:00:00'),

(64, 'SITP', 'Full-Stack Development', 3.00, '236', 'Monday', '08:00:00', '10:00:00'),
(64, 'SCS4', 'Capstone Project II', 3.00, '237', 'Tuesday', '10:00:00', '12:00:00'),
(64, 'SDB', 'Cloud Databases', 3.00, '238', 'Wednesday', '13:00:00', '15:00:00'),
(64, 'SAL', 'Data Mining', 3.00, '239', 'Thursday', '08:00:00', '10:00:00'),
(64, 'SNET', 'Networking for Security', 3.00, '240', 'Friday', '10:00:00', '12:00:00');

---------------

INSERT INTO subjects (section_id, code, subject_title, units, room, day, start_time, end_time) VALUES
(65, '1ITP', 'Advanced Programming Techniques', 3.00, '241', 'Monday', '08:00:00', '10:00:00'),
(65, '1CS1', 'Enterprise Software Engineering', 3.00, '242', 'Tuesday', '10:00:00', '12:00:00'),
(65, '1DB', 'Data Science', 3.00, '243', 'Wednesday', '13:00:00', '15:00:00'),
(65, '1AL', 'Neural Networks', 3.00, '244', 'Thursday', '08:00:00', '10:00:00'),
(65, '1NET', 'Network Protocols', 3.00, '245', 'Friday', '10:00:00', '12:00:00'),

(66, '1ITP', 'Mobile Application Development', 3.00, '246', 'Monday', '08:00:00', '10:00:00'),
(66, '1CS2', 'Capstone Project III', 3.00, '247', 'Tuesday', '10:00:00', '12:00:00'),
(66, '1DB', 'Distributed Databases', 3.00, '248', 'Wednesday', '13:00:00', '15:00:00'),
(66, '1AL', 'Evolutionary Computing', 3.00, '249', 'Thursday', '08:00:00', '10:00:00'),
(66, '1NET', 'Cloud Networking', 3.00, '250', 'Friday', '10:00:00', '12:00:00'),

(67, '1ITP', 'Web Development', 3.00, '251', 'Monday', '08:00:00', '10:00:00'),
(67, '1CS3', 'Software Testing', 3.00, '252', 'Tuesday', '10:00:00', '12:00:00'),
(67, '1DB', 'Data Analytics', 3.00, '253', 'Wednesday', '13:00:00', '15:00:00'),
(67, '1AL', 'Quantum Computing', 3.00, '254', 'Thursday', '08:00:00', '10:00:00'),
(67, '1NET', 'Advanced Wireless Networks', 3.00, '255', 'Friday', '10:00:00', '12:00:00'),

(68, '1ITP', 'Systems Integration', 3.00, '256', 'Monday', '08:00:00', '10:00:00'),
(68, '1CS4', 'Capstone Project IV', 3.00, '257', 'Tuesday', '10:00:00', '12:00:00'),
(68, '1DB', 'Big Data Analytics', 3.00, '258', 'Wednesday', '13:00:00', '15:00:00'),
(68, '1AL', 'Bioinformatics', 3.00, '259', 'Thursday', '08:00:00', '10:00:00'),
(68, '1NET', 'Smart Networks', 3.00, '260', 'Friday', '10:00:00', '12:00:00'),



(65, '2ITP', 'Advanced Programming Techniques', 3.00, '241', 'Monday', '08:00:00', '10:00:00'),
(65, '2CS1', 'Enterprise Software Engineering', 3.00, '242', 'Tuesday', '10:00:00', '12:00:00'),
(65, '2DB', 'Data Science', 3.00, '243', 'Wednesday', '13:00:00', '15:00:00'),
(65, '2AL', 'Neural Networks', 3.00, '244', 'Thursday', '08:00:00', '10:00:00'),
(65, '2NET', 'Network Protocols', 3.00, '245', 'Friday', '10:00:00', '12:00:00'),


(66, '2ITP', 'Mobile Application Development', 3.00, '246', 'Monday', '08:00:00', '10:00:00'),
(66, '2CS2', 'Capstone Project III', 3.00, '247', 'Tuesday', '10:00:00', '12:00:00'),
(66, '2DB', 'Distributed Databases', 3.00, '248', 'Wednesday', '13:00:00', '15:00:00'),
(66, '2AL', 'Evolutionary Computing', 3.00, '249', 'Thursday', '08:00:00', '10:00:00'),
(66, '2NET', 'Cloud Networking', 3.00, '250', 'Friday', '10:00:00', '12:00:00'),


(67, '2ITP', 'Web Development', 3.00, '251', 'Monday', '08:00:00', '10:00:00'),
(67, '2CS3', 'Software Testing', 3.00, '252', 'Tuesday', '10:00:00', '12:00:00'),
(67, '2DB', 'Data Analytics', 3.00, '253', 'Wednesday', '13:00:00', '15:00:00'),
(67, '2AL', 'Quantum Computing', 3.00, '254', 'Thursday', '08:00:00', '10:00:00'),
(67, '2NET', 'Advanced Wireless Networks', 3.00, '255', 'Friday', '10:00:00', '12:00:00'),


(68, '2ITP', 'Systems Integration', 3.00, '256', 'Monday', '08:00:00', '10:00:00'),
(68, '2CS4', 'Capstone Project IV', 3.00, '257', 'Tuesday', '10:00:00', '12:00:00'),
(68, '2DB', 'Big Data Analytics', 3.00, '258', 'Wednesday', '13:00:00', '15:00:00'),
(68, '2AL', 'Bioinformatics', 3.00, '259', 'Thursday', '08:00:00', '10:00:00'),
(68, '2NET', 'Smart Networks', 3.00, '260', 'Friday', '10:00:00', '12:00:00'),


(65, 'SITP', 'Advanced Programming Techniques', 3.00, '241', 'Monday', '08:00:00', '10:00:00'),
(65, 'SCS1', 'Enterprise Software Engineering', 3.00, '242', 'Tuesday', '10:00:00', '12:00:00'),
(65, 'SDB', 'Data Science', 3.00, '243', 'Wednesday', '13:00:00', '15:00:00'),
(65, 'SAL', 'Neural Networks', 3.00, '244', 'Thursday', '08:00:00', '10:00:00'),
(65, 'SNET', 'Network Protocols', 3.00, '245', 'Friday', '10:00:00', '12:00:00'),

(66, 'SITP', 'Mobile Application Development', 3.00, '246', 'Monday', '08:00:00', '10:00:00'),
(66, 'SCS2', 'Capstone Project III', 3.00, '247', 'Tuesday', '10:00:00', '12:00:00'),
(66, 'SDB', 'Distributed Databases', 3.00, '248', 'Wednesday', '13:00:00', '15:00:00'),
(66, 'SAL', 'Evolutionary Computing', 3.00, '249', 'Thursday', '08:00:00', '10:00:00'),
(66, 'SNET', 'Cloud Networking', 3.00, '250', 'Friday', '10:00:00', '12:00:00'),

(67, 'SITP', 'Web Development', 3.00, '251', 'Monday', '08:00:00', '10:00:00'),
(67, 'SCS3', 'Software Testing', 3.00, '252', 'Tuesday', '10:00:00', '12:00:00'),
(67, 'SDB', 'Data Analytics', 3.00, '253', 'Wednesday', '13:00:00', '15:00:00'),
(67, 'SAL', 'Quantum Computing', 3.00, '254', 'Thursday', '08:00:00', '10:00:00'),
(67, 'SNET', 'Advanced Wireless Networks', 3.00, '255', 'Friday', '10:00:00', '12:00:00'),

(68, 'SITP', 'Systems Integration', 3.00, '256', 'Monday', '08:00:00', '10:00:00'),
(68, 'SCS4', 'Capstone Project IV', 3.00, '257', 'Tuesday', '10:00:00', '12:00:00'),
(68, 'SDB', 'Big Data Analytics', 3.00, '258', 'Wednesday', '13:00:00', '15:00:00'),
(68, 'SAL', 'Bioinformatics', 3.00, '259', 'Thursday', '08:00:00', '10:00:00'),
(68, 'SNET', 'Smart Networks', 3.00, '260', 'Friday', '10:00:00', '12:00:00');

-------------------

INSERT INTO subjects (section_id, code, subject_title, units, room, day, start_time, end_time) VALUES
(69, '1ITP', 'Advanced Software Engineering', 3.00, '261', 'Monday', '08:00:00', '10:00:00'),
(69, '1CS1', 'Cloud Computing', 3.00, '262', 'Tuesday', '10:00:00', '12:00:00'),
(69, '1DB', 'AI Databases', 3.00, '263', 'Wednesday', '13:00:00', '15:00:00'),
(69, '1AL', 'Computational Biology', 3.00, '264', 'Thursday', '08:00:00', '10:00:00'),
(69, '1NET', 'Network Design and Optimization', 3.00, '265', 'Friday', '10:00:00', '12:00:00'),

(70, '1ITP', 'Human-Computer Interaction', 3.00, '266', 'Monday', '08:00:00', '10:00:00'),
(70, '1CS2', 'Advanced Machine Learning', 3.00, '267', 'Tuesday', '10:00:00', '12:00:00'),
(70, '1DB', 'Real-Time Databases', 3.00, '268', 'Wednesday', '13:00:00', '15:00:00'),
(70, '1AL', 'Advanced Algorithms II', 3.00, '269', 'Thursday', '08:00:00', '10:00:00'),
(70, '1NET', 'Telecommunications', 3.00, '270', 'Friday', '10:00:00', '12:00:00'),

(71, '1ITP', 'Embedded Systems', 3.00, '271', 'Monday', '08:00:00', '10:00:00'),
(71, '1CS3', 'Blockchain Technology', 3.00, '272', 'Tuesday', '10:00:00', '12:00:00'),
(71, '1DB', 'Data Privacy', 3.00, '273', 'Wednesday', '13:00:00', '15:00:00'),
(71, '1AL', 'Computational Neuroscience', 3.00, '274', 'Thursday', '08:00:00', '10:00:00'),
(71, '1NET', 'Advanced IoT Networks', 3.00, '275', 'Friday', '10:00:00', '12:00:00'),

(72, '1ITP', 'Internship', 3.00, '276', 'Monday', '08:00:00', '10:00:00'),
(72, '1CS4', 'Thesis I', 3.00, '277', 'Tuesday', '10:00:00', '12:00:00'),
(72, '1DB', 'Data Integration', 3.00, '278', 'Wednesday', '13:00:00', '15:00:00'),
(72, '1AL', 'Robotics', 3.00, '279', 'Thursday', '08:00:00', '10:00:00'),
(72, '1NET', '5G Technologies', 3.00, '280', 'Friday', '10:00:00', '12:00:00'),


(69, '2ITP', 'Advanced Software Engineering', 3.00, '261', 'Monday', '08:00:00', '10:00:00'),
(69, '2CS1', 'Cloud Computing', 3.00, '262', 'Tuesday', '10:00:00', '12:00:00'),
(69, '2DB', 'AI Databases', 3.00, '263', 'Wednesday', '13:00:00', '15:00:00'),
(69, '2AL', 'Computational Biology', 3.00, '264', 'Thursday', '08:00:00', '10:00:00'),
(69, '2NET', 'Network Design and Optimization', 3.00, '265', 'Friday', '10:00:00', '12:00:00'),

(70, '2ITP', 'Human-Computer Interaction', 3.00, '266', 'Monday', '08:00:00', '10:00:00'),
(70, '2CS2', 'Advanced Machine Learning', 3.00, '267', 'Tuesday', '10:00:00', '12:00:00'),
(70, '2DB', 'Real-Time Databases', 3.00, '268', 'Wednesday', '13:00:00', '15:00:00'),
(70, '2AL', 'Advanced Algorithms II', 3.00, '269', 'Thursday', '08:00:00', '10:00:00'),
(70, '2NET', 'Telecommunications', 3.00, '270', 'Friday', '10:00:00', '12:00:00'),

(71, '2ITP', 'Embedded Systems', 3.00, '271', 'Monday', '08:00:00', '10:00:00'),
(71, '2CS3', 'Blockchain Technology', 3.00, '272', 'Tuesday', '10:00:00', '12:00:00'),
(71, '2DB', 'Data Privacy', 3.00, '273', 'Wednesday', '13:00:00', '15:00:00'),
(71, '2AL', 'Computational Neuroscience', 3.00, '274', 'Thursday', '08:00:00', '10:00:00'),
(71, '2NET', 'Advanced IoT Networks', 3.00, '275', 'Friday', '10:00:00', '12:00:00'),

(72, '2ITP', 'Internship', 3.00, '276', 'Monday', '08:00:00', '10:00:00'),
(72, '2CS4', 'Thesis I', 3.00, '277', 'Tuesday', '10:00:00', '12:00:00'),
(72, '2DB', 'Data Integration', 3.00, '278', 'Wednesday', '13:00:00', '15:00:00'),
(72, '2AL', 'Robotics', 3.00, '279', 'Thursday', '08:00:00', '10:00:00'),
(72, '2NET', '5G Technologies', 3.00, '280', 'Friday', '10:00:00', '12:00:00'),


(69, 'SITP', 'Advanced Software Engineering', 3.00, '261', 'Monday', '08:00:00', '10:00:00'),
(69, 'SCS1', 'Cloud Computing', 3.00, '262', 'Tuesday', '10:00:00', '12:00:00'),
(69, 'SDB', 'AI Databases', 3.00, '263', 'Wednesday', '13:00:00', '15:00:00'),
(69, 'SAL', 'Computational Biology', 3.00, '264', 'Thursday', '08:00:00', '10:00:00'),
(69, 'SNET', 'Network Design and Optimization', 3.00, '265', 'Friday', '10:00:00', '12:00:00'),


(70, 'SITP', 'Human-Computer Interaction', 3.00, '266', 'Monday', '08:00:00', '10:00:00'),
(70, 'SCS2', 'Advanced Machine Learning', 3.00, '267', 'Tuesday', '10:00:00', '12:00:00'),
(70, 'SDB', 'Real-Time Databases', 3.00, '268', 'Wednesday', '13:00:00', '15:00:00'),
(70, 'SAL', 'Advanced Algorithms II', 3.00, '269', 'Thursday', '08:00:00', '10:00:00'),
(70, 'SNET', 'Telecommunications', 3.00, '270', 'Friday', '10:00:00', '12:00:00'),


(71, 'SITP', 'Embedded Systems', 3.00, '271', 'Monday', '08:00:00', '10:00:00'),
(71, 'SCS3', 'Blockchain Technology', 3.00, '272', 'Tuesday', '10:00:00', '12:00:00'),
(71, 'SDB', 'Data Privacy', 3.00, '273', 'Wednesday', '13:00:00', '15:00:00'),
(71, 'SAL', 'Computational Neuroscience', 3.00, '274', 'Thursday', '08:00:00', '10:00:00'),
(71, 'SNET', 'Advanced IoT Networks', 3.00, '275', 'Friday', '10:00:00', '12:00:00'),


(72, 'SITP', 'Internship', 3.00, '276', 'Monday', '08:00:00', '10:00:00'),
(72, 'SCS4', 'Thesis I', 3.00, '277', 'Tuesday', '10:00:00', '12:00:00'),
(72, 'SDB', 'Data Integration', 3.00, '278', 'Wednesday', '13:00:00', '15:00:00'),
(72, 'SAL', 'Robotics', 3.00, '279', 'Thursday', '08:00:00', '10:00:00'),
(72, 'SNET', '5G Technologies', 3.00, '280', 'Friday', '10:00:00', '12:00:00');