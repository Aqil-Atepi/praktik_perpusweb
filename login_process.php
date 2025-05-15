<?php
session_start();
include 'db.php'; // Include database connection

if (isset($_POST['login_btn'])) {
    function v_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $user = v_data($_POST['useremail']);
    $pass = v_data($_POST['password']);

    $query = "SELECT * FROM accounts WHERE acc_username='$user' OR acc_email='$user'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        if (password_verify($pass, $data['acc_password'])) {
            $_SESSION['iduser'] = $data['id_acc'];
            $_SESSION['username'] = $data['acc_username'];

            if ($data['acc_role'] == 'admin') {
                $_SESSION['admin_logged_in'] = true;
            }
            else if ($data['acc_role'] == 'staf') {
                $_SESSION['staf_logged_in'] = true;
            }
            header('location: home.php');
        }
        else {
            $login_error = 'Password does not match!';
            header("Location: login.php?login_error=" . $login_error . ""); // Redirect if password is wrong
            exit();
        }
    }
    else {
        $login_error = 'Username / Email does not found!';
        header("Location: login.php?login_error=" . $login_error . ""); // Redirect if username/email is wrong
        exit();
    }
} else {
    header("Location: login.php"); // Redirect if accessed without form submission
    exit();
}
?>