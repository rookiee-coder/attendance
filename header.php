<?php
include 'backend/conn.php';
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h2>Welcome, <?php echo htmlspecialchars($username) ?>!</h2>
        </div>
        <div class="header-buttons">
            <a class="action-btn edit-btn" data-bs-toggle="modal" data-bs-target="#addstudents">Add Student</a>
            <a class="logout-btn" href="backend/logout_user.php"
                onclick="return confirm('Are you sure you want to Logout?');"
            >Logout</a>
        </div>
    </div>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_courses.php">Manage Courses</a>
            </li>
        </ul>
    </div>

<div class="modal fade" id="addstudents" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Student</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form method="POST" action="backend/add_student.php">
        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input class="form-control" type="text" name="fullname" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Student ID</label>
            <input class="form-control" type="text" name="studentid" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Select Course</label>
            <select class="form-select" id="course_select" name="course_id" required onchange="loadSectionsForStudent()">
                <option value="">Choose a course...</option>
                <?php
                $sql = "SELECT * FROM courses ORDER BY course_name";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['id']."'>".htmlspecialchars($row['course_name'])."</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Select Section</label>
            <select class="form-select" id="section_select" name="section_id" required>
                <option value="">Choose a section...</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" value="Male" required>
                    <label class="form-check-label">Male</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" value="Female" required>
                    <label class="form-check-label">Female</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="submit" class="btn btn-primary">Add Student</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>

<script>
function loadSectionsForStudent() {
    const courseId = document.getElementById('course_select').value;
    const sectionSelect = document.getElementById('section_select');
    
    if(!courseId) {
        sectionSelect.innerHTML = '<option value="">Choose a section...</option>';
        return;
    }

    // Show loading state
    sectionSelect.innerHTML = '<option value="">Loading sections...</option>';

    fetch('backend/get_sections.php?course_id=' + courseId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
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

// Helper function to escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
</body>
</html>