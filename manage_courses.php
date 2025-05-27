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
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="text-primary">Manage Courses & Sections</h2>
            <p class="text-muted">View and manage all courses and their sections</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group-actions">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="fas fa-plus"></i> Add Course
                </button>
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                    <i class="fas fa-plus"></i> Add Section
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    <!-- Courses List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Courses & Sections</h4>
                </div>
                <div class="card-body">
                    <?php
                    $sql = "SELECT c.*, 
                           (SELECT COUNT(*) FROM sections WHERE course_id = c.id) as section_count,
                           (SELECT COUNT(*) FROM students s 
                            JOIN sections sec ON s.section_id = sec.id 
                            WHERE sec.course_id = c.id) as student_count
                           FROM courses c 
                           ORDER BY c.course_name";
                    $result = mysqli_query($conn, $sql);
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        while($course = mysqli_fetch_assoc($result)) {
                            echo '<div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">' . htmlspecialchars($course['course_name']) . '</h5>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                        onclick="editCourse(' . $course['id'] . ', \'' . 
                                                        htmlspecialchars($course['course_name'], ENT_QUOTES) . '\')" 
                                                        data-bs-toggle="modal" data-bs-target="#editCourseModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="backend/delete_course.php?id=' . $course['id'] . '" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm(\'Are you sure you want to delete this course? This will also delete all sections and students associated with it.\')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p class="mb-0"><strong>Sections:</strong> ' . $course['section_count'] . '</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-0"><strong>Students:</strong> ' . $course['student_count'] . '</p>
                                            </div>
                                        </div>';
                            
                            // Get sections for this course
                            $sections_sql = "SELECT s.*, 
                                           (SELECT COUNT(*) FROM students WHERE section_id = s.id) as student_count
                                           FROM sections s 
                                           WHERE s.course_id = " . $course['id'] . " 
                                           ORDER BY s.section_name";
                            $sections_result = mysqli_query($conn, $sections_sql);
                            
                            if ($sections_result && mysqli_num_rows($sections_result) > 0) {
                                echo '<div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Section Name</th>
                                                    <th>Students</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                
                                while($section = mysqli_fetch_assoc($sections_result)) {
                                    $sectionData = array(
                                        'id' => $section['id'],
                                        'section_name' => $section['section_name'],
                                        'course_id' => $course['id']
                                    );
                                    
                                    echo '<tr>
                                            <td>' . htmlspecialchars($section['section_name']) . '</td>
                                            <td>' . $section['student_count'] . '</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" 
                                                        onclick="editSection(' . htmlspecialchars(json_encode($sectionData)) . ')" 
                                                        data-bs-toggle="modal" data-bs-target="#editSectionModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <a href="backend/delete_section.php?id=' . $section['id'] . '" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm(\'Are you sure you want to delete this section? This will also delete all students in this section.\')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>';
                                }
                                
                                echo '</tbody></table></div>';
                            } else {
                                echo '<p class="text-muted mb-0">No sections available</p>';
                            }
                            
                            echo '</div></div>';
                        }
                    } else {
                        echo '<div class="alert alert-info">No courses available. Add a course to get started.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/edit_course.php" method="POST">
                    <input type="hidden" name="id" id="edit_course_id">
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" class="form-control" name="course_name" id="edit_course_name" required>
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

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_course.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" class="form-control" name="course_name" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="backend/add_section.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select class="form-select" name="course_id" required>
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
                        <input type="text" class="form-control" name="section_name" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editCourse(id, courseName) {
    document.getElementById('edit_course_id').value = id;
    document.getElementById('edit_course_name').value = courseName;
}

function editSection(sectionData) {
    // Parse the section data if it's a string
    if (typeof sectionData === 'string') {
        sectionData = JSON.parse(sectionData);
    }
    
    // Set form values
    document.getElementById('edit_section_id').value = sectionData.id;
    document.getElementById('edit_section_name').value = sectionData.section_name;
}
</script>

<?php include 'includes/footer.php'; ?> 