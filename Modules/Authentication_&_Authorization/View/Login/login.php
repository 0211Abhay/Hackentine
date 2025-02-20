<?php
session_start();
$error = [];

// Include the database connection file
@include '../../../Includes/db_connect.php';

if (isset($_POST['submit'])) {
    try {
        // Sanitize and validate inputs
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        // Prepare the SQL query to fetch user data
        $select = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($select);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['last_name'] = $row['last_name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['university_id'] = $row['university_id'];     
                $_SESSION['email'] = $row['email'];    
                // Set session variables based on user type
                if ($row['role'] == 'mentor') {
                    $_SESSION['role'] = $row['role'];                 
                    header('Location: ../../../10X Mentor/View/mentor.html');
                    exit();
                } 
                
                else if ($row['role'] == 'coordinator') {
                    $_SESSION['role'] = $row['role'] ;                  
                    header('Location: ../../../Coordinator_Dasnboard/coordinator_dashboard.php');
                    exit();
                }

                else if ($row['role'] == 'member') {
                    $_SESSION['role'] = $row['role'];                 
                    header('Location: ../../../Student_Dashboard/student.php');
                    exit();
                }
            } else {
                $error[] = 'Incorrect email or password!';
            }
        } else {
            $error[] = 'Incorrect email or password!';
        }
    } catch (PDOException $e) {
        $error[] = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- ===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="style.css" />
    <title>Login Form</title>
</head>
<body>
    <div class="container">
        <div class="forms">
            <div class="form login">
                <span class="title">Login</span>
                <?php
                if (!empty($error)) {
                    foreach ($error as $err) {
                        echo "<p style='color: red;'>$err</p>";
                    }
                }
                ?>
                <form action="" method="post">
                    <div class="input-field">
                        <input type="text" name="email" placeholder="Enter your email" required />
                        <i class="uil uil-envelope icon"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" class="password" placeholder="Enter your password" required />
                        <i class="uil uil-lock icon"></i>
                        <i class="uil uil-eye-slash showHidePw"></i>
                    </div>
                    <div class="checkbox-text">
                        <div class="checkbox-content">
                            <input type="checkbox" id="logCheck" />
                            <label for="logCheck" class="text">Remember me</label>
                        </div>
                        <a href="#" class="text">Forgot password?</a>
                    </div>
                    <div class="input-field button">
                        <input type="submit" name="submit" value="Login" />
                    </div>
                </form>
                <div class="login-signup">
                    <span class="text">
                        Not a Registered?
                        <a href="../Register/registration.php" class="text signup-link">Register</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <script src="./script.js"></script>
</body>
</html>