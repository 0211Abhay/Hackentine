<?php
session_start();
$error = [];

@include '../../../Includes/db_connect.php';

if (isset($_POST['submit'])) {
    try {
        // Sanitize and validate inputs
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $mobile_no = htmlspecialchars($_POST['mobile_no']);
        $linkedin = htmlspecialchars($_POST['linkedin']);
        $github = htmlspecialchars($_POST['github']);
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $university_id = intval($_POST['university_id']);

        if ($password !== $cpassword) {
            $error[] = 'Passwords do not match!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $check_email = "SELECT email FROM users WHERE email = :email";
            $stmt = $conn->prepare($check_email);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $error[] = 'Email already exists!';
            } else {
                $insert = "INSERT INTO users (first_name, last_name, mobile_no, email, linkedin, github, password, university_id) 
                           VALUES (:first_name, :last_name, :mobile_no, :email, :linkedin, :github, :password, :university_id)";
                $stmt = $conn->prepare($insert);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':mobile_no', $mobile_no);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':linkedin', $linkedin);
                $stmt->bindParam(':github', $github);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':university_id', $university_id);

                if ($stmt->execute()) {
                    header('Location: ../Login/login.php');
                    exit();
                } else {
                    $error[] = 'Registration failed! Please try again.';
                }
            }
        }
    } catch (PDOException $e) {
        $error[] = 'Database error: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Registration Form</title>
</head>
<body>
    <div class="container">
        <header>Registration</header>
        <?php
        if (!empty($error)) {
            foreach ($error as $err) {
                echo "<p style='color: red;'>$err</p>";
            }
        }
        ?>
        <form action="" method="post">
            <div class="form first">
                <div class="fields">
                    <div class="input-field">
                        <label>First Name</label>
                        <input type="text" name="first_name" placeholder="Enter Your First Name" required>
                    </div>
                    <div class="input-field">
                        <label>Last Name</label>
                        <input type="text" name="last_name" placeholder="Enter Your Last Name" required>
                    </div>
                    <div class="input-field">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter Your Email" required>
                    </div>
                    <div class="input-field">
                        <label>Mobile Number</label>
                        <input type="tel" name="mobile_no" placeholder="Enter Mobile Number" required>
                    </div>
                    <div class="input-field">
                        <label>LinkedIn Profile Link</label>
                        <input type="url" name="linkedin" placeholder="Enter LinkedIn Profile Link" required>
                    </div>
                    <div class="input-field">
                        <label>Github Profile Link</label>
                        <input type="url" name="github" placeholder="Enter Github Profile Link" required>
                    </div>
                    <div class="input-field">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter Your Password" required>
                    </div>
                    <div class="input-field">
                        <label>Confirm Password</label>
                        <input type="password" name="cpassword" placeholder="Confirm Your Password" required>
                    </div>
                    <div class="input-field">
                        <label>University</label>
                        <select name="university_id" required>
                            <option disabled selected>Select University</option>
                            <option value="1">Marwadi University</option>
                            <option value="2">IIT Bombay</option>
                            <option value="3">NIT Surat</option>
                            <option value="4">BITS Pilani</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>Specalization</label>
                        <select name="specialization" required>
                        <option disabled selected>Select Specialization</option>

                            <!-- Computer Engineering Specializations -->
                            <option value="ce_ai_ml">Artificial Intelligence & Machine Learning</option>
                            <option value="ce_data_science">Data Science & Big Data Analytics</option>
                            <option value="ce_cybersecurity">Cybersecurity & Ethical Hacking</option>
                            <option value="ce_cloud">Cloud Computing & DevOps</option>
                            <option value="ce_iot">Internet of Things (IoT)</option>
                            <option value="ce_embedded">Embedded Systems & Robotics</option>
                            <option value="ce_software">Software Engineering & Application Development</option>
                            <option value="ce_networks">Computer Networks & Network Security</option>
                            <option value="ce_blockchain">Blockchain Technology</option>
                            <option value="ce_hci">Human-Computer Interaction (HCI)</option>
                            <option value="ce_quantum">Quantum Computing</option>
                            <option value="ce_ar_vr">Augmented Reality (AR) & Virtual Reality (VR)</option>
                            <option value="ce_game_dev">Game Development & Graphics Programming</option>
                            <option value="ce_hpc">High-Performance Computing</option>
                            <option value="ce_edge">Edge Computing</option>

                            <!-- Electronics Specializations -->
                            <option value="ee_vlsi">VLSI Design & Microelectronics</option>
                            <option value="ee_embedded">Embedded Systems & IoT</option>
                            <option value="ee_power">Power Electronics & Drives</option>
                            <option value="ee_nano">Nanoelectronics</option>
                            <option value="ee_communication">Communication Systems</option>
                            <option value="ee_wireless">Wireless & Mobile Communication</option>
                            <option value="ee_signal">Signal Processing</option>
                            <option value="ee_biomedical">Biomedical Electronics</option>
                            <option value="ee_renewable">Renewable Energy & Smart Grid Technologies</option>
                            <option value="ee_control">Control Systems & Automation</option>
                            <option value="ee_rf_microwave">RF & Microwave Engineering</option>
                            <option value="ee_sensors">Sensor Technology & Instrumentation</option>
                            <option value="ee_optoelectronics">Optoelectronics & Photonics</option>
                            <option value="ee_automotive">Automotive Electronics</option>
                            <option value="ee_circuit_design">Analog & Digital Circuit Design</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>Graduation Year</label>
                        <select name="year" required>
                            <option disabled selected>Select Your Graduation Year</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>Location</label>
                        <input type="text" name="location" placeholder="Enter Your Location" required>
                    </div>
                    <div class="input-field">
                        <label>Portfolio Link</label>
                        <input type="url" name="github" placeholder="Enter Your Portfolio Link" required>
                    </div>
                    <div class="input-field">
                        <label>Having Work Experience</label>
                        <select name="year" required>
                            <option disabled selected>Having Work Experience</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="input-field">
                        <label>Rate Yourself</label>
                        <select name="rating" required>
                            <option disabled selected>Rate Yourself</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermidiate">Intermidiate</option>
                            <option value="experienced">Experienced</option>
                        </select>
                    </div>
                </div>
                

                <div class="buttons-container">
                    <button type="submit" name="submit" class="submit">
                        <span class="btnText">Submit</span>
                        <i class="uil uil-navigator"></i>
                    </button>

                    <div class="login-signup">
                        <span class="text">
                            Already a member?
                            <a href="../Login/login.php" class="text signup-link">Login Now</a>
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>

