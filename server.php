<?php
session_start();
$name = '';
$email = '';
$phone = '';
$password = '';
$password1 = '';
$password2 = '';
$errors = array();

// Database connection
$servername = "127.0.0.1:3307";
$username = "root";
$password = "";
$database = "user";

// Connect to the Database
$db = mysqli_connect($servername, $username, $password, $database);

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}

// If the create account button is clicked
if (isset($_POST['createAccount'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email1']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $password1 = mysqli_real_escape_string($db, $_POST['pwd1']);
    $password2 = mysqli_real_escape_string($db, $_POST['pwd2']);

    // Validation
    if (empty($name)) array_push($errors, "Name is required.");
    if (empty($email)) array_push($errors, "Email is required.");
    if (empty($phone)) array_push($errors, "Mobile No. is required.");
    if (empty($password1)) array_push($errors, "Password is required.");
    if ($password1 != $password2) array_push($errors, "The two passwords do not match.");

    // Check if email already exists
    $emailQuery = mysqli_query($db, "SELECT * FROM people WHERE email='$email'");
    if (mysqli_num_rows($emailQuery) > 0) {
        array_push($errors, "Email already exists.");
    }

    // If no errors, insert into database
    if (count($errors) == 0) {
        // $password = md5($password1); // optional encryption
        $sql = "INSERT INTO people (namme, email, phone, pwd1) VALUES ('$name', '$email', '$phone', '$password1')";
        if (mysqli_query($db, $sql)) {
            echo "You are registered successfully!";
            $_SESSION['full_name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "You are now logged in";
            header('Location: chat.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($db);
        }
    }
}

// If login button is clicked
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($db, $_POST['email1']);
    $password = mysqli_real_escape_string($db, $_POST['pwd1']);

    if (empty($email)) array_push($errors, "Email is required.");
    if (empty($password)) array_push($errors, "Password is required.");

    if (count($errors) == 0) {
        $query = "SELECT * FROM people WHERE email='$email' AND pwd1='$password'";
        $result = mysqli_query($db, $query);
        $resultObj = mysqli_fetch_object($result);

        if (mysqli_num_rows($result) == 1) {
            $_SESSION['full_name'] = $resultObj->namme;
            $_SESSION['email'] = $resultObj->email;
            $_SESSION['phone'] = $resultObj->phone;
            $_SESSION['success'] = "You are now logged in";
            header('Location: chat.php');
            exit();
        } else {
            array_push($errors, "Username or Password incorrect");
        }
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['full_name']);
    header('Location: index.php');
    exit();
}

// Update profile
if (isset($_POST['update'])) {
    $data = array();
    $data[0] = mysqli_real_escape_string($db, $_POST['name']);
    $data[1] = mysqli_real_escape_string($db, $_POST['email1']);
    $data[2] = mysqli_real_escape_string($db, $_POST['phone']);
    $data[3] = mysqli_real_escape_string($db, $_POST['pwd1']);
    $data[4] = mysqli_real_escape_string($db, $_POST['pwd2']);

    if (empty($data[0])) array_push($errors, "Name is required.");
    if (empty($data[1])) array_push($errors, "Email is required.");
    if (empty($data[2])) array_push($errors, "Mobile No. is required.");
    if (empty($data[3])) array_push($errors, "Password is required.");
    if ($data[3] != $data[4]) array_push($errors, "The two passwords do not match.");

    if (count($errors) == 0) {
        $update_Query = "UPDATE people 
                         SET namme='$data[0]', email='$data[1]', phone='$data[2]', pwd1='$data[3]' 
                         WHERE email='$data[1]'";

        if (mysqli_query($db, $update_Query)) {
            echo "Profile updated successfully!";
        } else {
            echo "Error updating profile: " . mysqli_error($db);
        }
    }
}
?>
