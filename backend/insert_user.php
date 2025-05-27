<?php
include 'conn.php';
if (isset($_POST['submit'])) {
    // assign form data to variables
    $username   = $_POST['username'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $cpassword  = $_POST['cpassword'];

    // check if passwords match
    if ($password != $cpassword) {
        echo "
        <script>
            alert('Password and Confirm Password must match.');
            window.location.href = '../signup.php';
        </script>
        ";
        exit();
    }
    // check if email already in databse
    $checkusername = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $checkusername);

    if (mysqli_num_rows($result) > 0) {
        echo "
        <script>
            alert('Email already exists. Please use a different email.');
            window.location.href = '../signup.php';
        </script>
        ";
        exit();
    }

    // hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // insert user into databasse
    $sql = "INSERT INTO users (username, email, password)
            VALUES ('$username', '$email', '$hashedPassword')";

    if (mysqli_query($conn, $sql)) {
        echo "
        <script>
            alert('User Registered Successfully!');
            window.location.href = '../signup.php';
        </script>
        ";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    echo "
    <script>
        alert('Signup failed. Please try again.');
        window.location.href = '../signup.php';
    </script>
    ";
}

?>





