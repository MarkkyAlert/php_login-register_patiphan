<?php
session_start();
include 'server.php';
$errors = array(); // เก็บ error
if(isset($_POST['reg_user'])) { // เช็คว่ามีการกดปุ่มไหม
    $username = mysqli_real_escape_string($conn, $_POST['username']); // สร้างตัวแปรเก็บแต่ละข้อมูล
    $email = mysqli_real_escape_string($conn, $_POST['email']); // สร้างตัวแปรเก็บแต่ละข้อมูล
    $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']); // สร้างตัวแปรเก็บแต่ละข้อมูล
    $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']); // สร้างตัวแปรเก็บแต่ละข้อมูล

    if (empty($username)) { // เช็คว่าค่ามันว่างหรือเปล่า
        array_push($errors, "Username is required");
    }
    if (empty($email)) { // เช็คว่าค่ามันว่างหรือเปล่า
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) { // เช็คว่าค่ามันว่างหรือเปล่า
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) { // เช็คว่าค่ามันว่างหรือเปล่า
        array_push($errors, "The two password do not match");
    }

    $user_check_query = "SELECT * FROM user WHERE username = '$username' OR email = '$email'";
    $query = mysqli_query($conn, $user_check_query);
    $result = mysqli_fetch_assoc($query);

    if ($result) {
        if ($result['username'] === $username) {
            array_push($errors, "Username already exists"); 
        }
        if ($result['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }

    if (count($errors) == 0) { // ถ้าไม่มี error
        $password = md5($password_1); // hash password
        $sql = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";
        mysqli_query($conn, $sql);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in"; // ใช้ SESSION เพื่อข้ามหน้า
        header('location: index.php');
    } else {
        array_push($errors, "Username or Email already exists");
        $_SESSION['error'] = "Username or Email already exists";
        header("location: register.php");
    }
}
?>