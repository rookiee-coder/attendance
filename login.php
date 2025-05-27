<?php

session_start();
if(isset($_SESSION['email'])){
   header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <script defer src="js/bootstrap.bundle.min.js"></script>
    
    <title>Document</title>
</head>
<body>
    <section class="h-100 gradient-form" style="background-color: #f4f6f8;">
        <div class="container py-5 h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-10">
              <div class="card rounded-3 text-black">
                <div class="row g-0">
                  <div class="col-lg-6">
                    <div class="card-body p-md-5 mx-md-4">
      
                      <div class="text-center">
                        <h4 class="mt-1 mb-5 pb-1">Login</h4>
                      </div>
      
                      <form method="POST" action="backend/login_user.php">
                        <p>Please login to your account</p>
      
                        <div data-mdb-input-init class="form-outline mb-4">
                          <input type="text" name="username" id="form2Example11" class="form-control"
                          <label class="form-label" for="form2Example11">Username</label>
                        </div>
      
                        <div data-mdb-input-init class="form-outline mb-4">
                          <input type="password" name="password" id="form2Example22" class="form-control" />
                          <label class="form-label" for="form2Example22">Password</label>
                        </div>
      
                        <div class="text-center pt-1 mb-5 pb-1">
                          <input type="submit" name="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" style="width:300px;">
                            <br>
                        </div>
      
                        <div class="d-flex align-items-center justify-content-center pb-4">
                          <p class="mb-0 me-2">Don't have an account?</p>
                          <a href="signup.php" type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-danger">Create New</a>
                        </div>
  
                      </form>
      
                    </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                    <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                      <h4 class="mb-4">Students Attendance Monitoring System</h4>
                      <p class="small mb-0">An attendance monitoring system for students can significantly improve the accuracy and efficiency of attendance tracking, especially when compared to manual methods.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <style>
        .gradient-custom-2 {

background: #fccb90;

background: -webkit-linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);

background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);
}

@media (min-width: 768px) {
.gradient-form {
height: 100vh !important;
}
}
@media (min-width: 769px) {
.gradient-custom-2 {
border-top-right-radius: .3rem;
border-bottom-right-radius: .3rem;
}
}
      </style>
</body>
</html>