<?php
session_start();
include 'db.php';

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM accounts WHERE id_acc = $id");
$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location: akun.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit User</h2>
    <form method="post" action="akun.php">
        <input type="hidden" name="id" value="<?= $book['id_acc'] ?>">

        <label>Username:</label>
        <input type="text" name="staf" value="<?= $book['acc_username'] ?>" required>

        <label>Email:</label>
        <input type="text" name="pinjam" value="<?= $book['acc_email'] ?>" required>

        <label>Password:</label>
        <input type="text" name="waktu" value="<?= $book['acc_password'] ?>" required>

        <label>Role:</label>
        <input list="roles" name="role" value="<?= $book['acc_role'] ?>" required>
        <datalist id="roles" required>
            <option value="admin">
            <option value="staf">
            <option value="anggota">
         </datalist>

        <input type="submit" name="edit_user" value="Update User">

        <?php
            
        ?>
    </form>
</body>
</html>
