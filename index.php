<?php 
include 'backend/conn.php';
include 'includes/header.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Display message if exists
if (isset($_SESSION['message'])) {
    $message_type = $_SESSION['message_type'] ?? 'info';
    $message = $_SESSION['message'];
    
    // Clear the message
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    
    echo "<div class='alert alert-$message_type alert-dismissible fade show' role='alert'>
            $message
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}
?>

<div class="container py-4">
    <!-- Instruction Banner -->
    <div class="alert alert-info mb-4">
        <h5 class="alert-heading"><i class="fas fa-info-circle"></i> How to Use This System</h5>
        <ol class="mb-0">
            <li>First, add a course using the <strong class="text-success">green "Add Course"</strong> button above</li>
            <li>Then, add sections to your course using the <strong class="text-info">blue "Add Section"</strong> button</li>
            <li>Select your course and section from the dropdowns below to manage attendance</li>
            <li>Use the <strong class="text-primary">blue "Manage Courses & Sections"</strong> button to view all courses and sections</li>
        </ol>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="text-primary">Student Attendance System</h2>
            <p class="text-muted">Select a course and section to manage attendance</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group-actions">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="fas fa-plus"></i> Add Course
                </button>
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                    <i class="fas fa-plus"></i> Add Section
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="fas fa-user-plus"></i> Add Student
                </button>
                <a href="manage_courses.php" class="btn btn-secondary">
                    <i class="fas fa-cog"></i> Manage
                </a>
            </div>
        </div>
    </div>

    <!-- Course and Section Selection -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <label for="course_select" class="form-label fw-bold">Select Course</label>
                    <select id="course_select" class="form-select form-select-lg" onchange="loadSections()">
                        <option value="">Choose a course...</option>
                        <?php
                        $sql = "SELECT * FROM courses ORDER BY course_name";
                        $result = mysqli_query($conn, $sql);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='".$row['id']."'>".htmlspecialchars($row['course_name'])."</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No courses available - Add a course first</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <label for="section_select" class="form-label fw-bold">Select Section</label>
                    <select id="section_select" class="form-select form-select-lg" onchange="loadStudents()">
                        <option value="">Choose a section...</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Student List</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="backend/attendance.php" id="attendance_form">
                        <input type="hidden" name="section_id" id="attendance_section_id">
                        <div id="students_list">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>Select a course and section to view students</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Results -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Attendance History</h4>
                </div>
                <div class="card-body">
                    <div id="attendance_results">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-history fa-3x mb-3"></i>
                            <p>Select a section to view attendance history</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_course.php" method="POST">
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" 
                               placeholder="e.g., BSIT, BSCS" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSectionModalLabel">Add New Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_section.php" method="POST">
                    <div class="mb-3">
                        <label for="modal_course_id" class="form-label">Select Course</label>
                        <select class="form-select" id="modal_course_id" name="course_id" required>
                            <option value="">Choose a course...</option>
                            <?php
                            $sql = "SELECT * FROM courses ORDER BY course_name";
                            $result = mysqli_query($conn, $sql);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='".$row['id']."'>".htmlspecialchars($row['course_name'])."</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No courses available</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section_name" class="form-label">Section Name</label>
                        <input type="text" class="form-control" id="section_name" name="section_name" 
                               placeholder="e.g., Section A, Morning Class" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_student.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fullname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Student ID</label>
                        <input type="text" class="form-control" name="studentid" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select class="form-select" name="course_id" id="studentCourseSelect" required>
                            <option value="">Select Course</option>
                            <?php
                            $sql = "SELECT * FROM courses ORDER BY course_name";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['course_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <select class="form-select" name="section_id" id="studentSectionSelect">
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/edit_student.php" method="POST">
                    <input type="hidden" name="id" id="edit_student_id">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="fullname" id="edit_fullname" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Student ID</label>
                        <input type="text" class="form-control" name="studentid" id="edit_studentid" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select class="form-select" name="course_id" id="edit_course_id" required>
                            <option value="">Select Course</option>
                            <?php
                            $sql = "SELECT * FROM courses ORDER BY course_name";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['course_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <select class="form-select" name="section_id" id="edit_section_id">
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select class="form-select" name="gender" id="edit_gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Attendance Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/update_attendance.php" method="POST">
                    <input type="hidden" name="attendance_id" id="edit_attendance_id">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="edit_attendance_status" required>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/edit_section.php" method="POST">
                    <input type="hidden" name="id" id="edit_section_id">
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select class="form-select" name="course_id" id="edit_section_course_id" required>
                            <option value="">Select Course</option>
                            <?php
                            $sql = "SELECT * FROM courses ORDER BY course_name";
                            $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['course_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Section Name</label>
                        <input type="text" class="form-control" name="section_name" id="edit_section_name" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function loadSections() {
    const courseId = document.getElementById('course_select').value;
    const sectionSelect = document.getElementById('section_select');
    
    if(!courseId) {
        sectionSelect.innerHTML = '<option value="">Choose a section...</option>';
        loadStudents();
        loadAttendanceResults();
        return;
    }

    sectionSelect.innerHTML = '<option value="">Loading sections...</option>';

    fetch('backend/get_sections.php?course_id=' + courseId)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Choose a section...</option>';
            
            if (data.error) {
                console.error('Error:', data.error);
                sectionSelect.innerHTML = '<option value="" disabled>Error: ' + escapeHtml(data.error) + '</option>';
                return;
            }
            
            if (data && data.length > 0) {
                data.forEach(section => {
                    options += `<option value="${section.id}">${escapeHtml(section.section_name)}</option>`;
                });
            } else {
                options += '<option value="" disabled>No sections available for this course</option>';
            }
            sectionSelect.innerHTML = options;
        })
        .catch(error => {
            console.error('Error loading sections:', error);
            sectionSelect.innerHTML = '<option value="" disabled>Error loading sections. Please try again.</option>';
        });
}

function loadStudents() {
    const sectionId = document.getElementById('section_select').value;
    const studentsContainer = document.getElementById('students_list');
    const attendanceSectionId = document.getElementById('attendance_section_id');
    
    if(!sectionId) {
        studentsContainer.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-users fa-3x mb-3"></i>
                <p>Select a course and section to view students</p>
            </div>`;
        attendanceSectionId.value = '';
        return;
    }

    // Update hidden section_id field
    attendanceSectionId.value = sectionId;

    // Show loading state
    studentsContainer.innerHTML = `
        <div class="text-center text-muted py-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading students...</p>
        </div>`;

    fetch('backend/get_students.php?section_id=' + sectionId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            studentsContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading students:', error);
            studentsContainer.innerHTML = `
                <div class="text-center text-danger py-5">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <p>Error loading students. Please try again.</p>
                </div>`;
        });
}

function loadAttendanceResults() {
    const sectionId = document.getElementById('section_select').value;
    const attendanceContainer = document.getElementById('attendance_results');
    
    if(!sectionId) {
        attendanceContainer.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-history fa-3x mb-3"></i>
                <p>Select a section to view attendance history</p>
            </div>`;
        return;
    }


    attendanceContainer.innerHTML = `
        <div class="text-center text-muted py-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading attendance history...</p>
        </div>`;

    fetch('backend/get_attendance.php?section_id=' + sectionId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            attendanceContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading attendance:', error);
            attendanceContainer.innerHTML = `
                <div class="text-center text-danger py-5">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <p>Error loading attendance history. Please try again.</p>
                </div>`;
        });
}

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Load attendance results when section is selected
document.getElementById('section_select').addEventListener('change', function() {
    loadStudents();
    loadAttendanceResults();
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Student Attendance System loaded successfully');
});

document.getElementById('studentCourseSelect').addEventListener('change', function() {
    const courseId = this.value;
    const sectionSelect = document.getElementById('studentSectionSelect');
    
    // Clear current options
    sectionSelect.innerHTML = '<option value="">Select Section</option>';
    
    if (courseId) {
        // Fetch sections for selected course
        fetch(`backend/get_sections.php?course_id=${courseId}`)
            .then(response => response.json())
            .then(sections => {
                sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.section_name;
                    sectionSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }
});

function editStudent(studentData) {
    // Parse the student data if it's a string
    if (typeof studentData === 'string') {
        studentData = JSON.parse(studentData);
    }
    
    // Set form values
    document.getElementById('edit_student_id').value = studentData.id;
    document.getElementById('edit_fullname').value = studentData.fullname;
    document.getElementById('edit_studentid').value = studentData.studentid;
    document.getElementById('edit_course_id').value = studentData.course_id;
    document.getElementById('edit_gender').value = studentData.gender;
    
    // Load sections for the selected course
    const sectionSelect = document.getElementById('edit_section_id');
    sectionSelect.innerHTML = '<option value="">Select Section</option>';
    
    if (studentData.course_id) {
        fetch(`backend/get_sections.php?course_id=${studentData.course_id}`)
            .then(response => response.json())
            .then(sections => {
                sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.section_name;
                    if (section.id == studentData.section_id) {
                        option.selected = true;
                    }
                    sectionSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading sections:', error);
                sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
            });
    }
}

// Add event listener for course change in edit modal
document.getElementById('edit_course_id').addEventListener('change', function() {
    const courseId = this.value;
    const sectionSelect = document.getElementById('edit_section_id');
    
    sectionSelect.innerHTML = '<option value="">Select Section</option>';
    
    if (courseId) {
        fetch(`backend/get_sections.php?course_id=${courseId}`)
            .then(response => response.json())
            .then(sections => {
                sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.section_name;
                    sectionSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading sections:', error);
                sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
            });
    }
});

function editAttendance(id, currentStatus) {
    document.getElementById('edit_attendance_id').value = id;
    document.getElementById('edit_attendance_status').value = currentStatus;
}

function editSection(sectionData) {
    // Parse the section data if it's a string
    if (typeof sectionData === 'string') {
        sectionData = JSON.parse(sectionData);
    }
    
    // Set form values
    document.getElementById('edit_section_id').value = sectionData.id;
    document.getElementById('edit_section_course_id').value = sectionData.course_id;
    document.getElementById('edit_section_name').value = sectionData.section_name;
}
</script>