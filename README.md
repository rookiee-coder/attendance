# Attendance Management System

A web-based attendance management system built with PHP and MySQL that allows educational institutions to manage courses, sections, students, and track attendance.

## Features

- Course Management
  - Add, edit, and delete courses
  - View course details and statistics
- Section Management
  - Add, edit, and delete sections within courses
  - View section details and student count
- Student Management
  - Add, edit, and delete students
  - Assign students to courses and sections
- Attendance Tracking
  - Record daily attendance
  - Mark students as Present, Absent, or Late
  - View attendance history
- User Interface
  - Clean and responsive design using Bootstrap
  - Interactive modals for data entry
  - Real-time updates

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP/WAMP/MAMP (for local development)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/attendance-management.git
```

2. Set up the database:
   - Create a new MySQL database named `attendance_db`
   - Import the database schema from `database/attendance_db.sql`

3. Configure the database connection:
   - Open `backend/conn.php`
   - Update the database credentials if needed:
     ```php
     $servername = "localhost";
     $username = "your_username";
     $password = "your_password";
     $dbname = "attendance_db";
     ```

4. Place the project in your web server's root directory:
   - For XAMPP: `htdocs/attendance`
   - For WAMP: `www/attendance`
   - For MAMP: `htdocs/attendance`

5. Access the application:
   - Open your web browser
   - Navigate to `http://localhost/attendance`

## Usage

1. Add Courses
   - Click "Add Course" button
   - Enter course name
   - Save the course

2. Add Sections
   - Click "Add Section" button
   - Select a course
   - Enter section name
   - Save the section

3. Add Students
   - Click "Add Student" button
   - Fill in student details
   - Select course and section
   - Save the student

4. Record Attendance
   - Select course and section
   - Mark attendance status for each student
   - Save attendance records

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

Your Name - your.email@example.com

Project Link: [https://github.com/yourusername/attendance-management](https://github.com/yourusername/attendance-management) 