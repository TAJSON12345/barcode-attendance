-- ==========================================
-- Create Users Table
-- ==========================================

CREATE TABLE IF NOT EXISTS users (

    id SERIAL PRIMARY KEY,

    fullname VARCHAR(100) NOT NULL,

    username VARCHAR(50) UNIQUE NOT NULL,

    password VARCHAR(255) NOT NULL,

    role VARCHAR(20) DEFAULT 'Lecturer'
);

-- ==========================================
-- Create Students Table
-- ==========================================

CREATE TABLE IF NOT EXISTS students (

    id SERIAL PRIMARY KEY,

    student_id VARCHAR(20) UNIQUE NOT NULL,

    fullname VARCHAR(100) NOT NULL,

    department VARCHAR(100) NOT NULL,

    level VARCHAR(20),

    barcode VARCHAR(100) UNIQUE NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- Create Courses Table
-- ==========================================

CREATE TABLE IF NOT EXISTS courses (

    id SERIAL PRIMARY KEY,

    course_code VARCHAR(20) UNIQUE NOT NULL,

    course_title VARCHAR(150) NOT NULL,

    semester VARCHAR(20),

    session VARCHAR(20)
);

-- ==========================================
-- Create Attendance Table
-- ==========================================

CREATE TABLE IF NOT EXISTS attendance (

    id SERIAL PRIMARY KEY,

    student_id INTEGER NOT NULL,

    course_id INTEGER NOT NULL,

    attendance_date DATE DEFAULT CURRENT_DATE,

    attendance_time TIME DEFAULT CURRENT_TIME,

    CONSTRAINT fk_student
        FOREIGN KEY(student_id)
        REFERENCES students(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_course
        FOREIGN KEY(course_id)
        REFERENCES courses(id)
        ON DELETE CASCADE
);