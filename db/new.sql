CREATE DATABASE enrollment_system;


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



CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL,
    title VARCHAR(255) NOT NULL,
    course_id INT,
    units INT,
    CONSTRAINT fk_subject_course FOREIGN KEY (course_id) REFERENCES courses(id)
);


CREATE TABLE sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    course_id INT,
    CONSTRAINT fk_section_course FOREIGN KEY (course_id) REFERENCES courses(id)
);
