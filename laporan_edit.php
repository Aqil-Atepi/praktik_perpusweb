<?php
session_start();
include 'db.php';

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM laporan WHERE id_laporan = $id");
$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location: laporan.php");
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
    <h2>Edit Laporan</h2>
    <form method="post" action="akun.php">
        <input type="hidden" name="id" value="<?= $book['id_laporan'] ?>">

        <label>Jumlah Pinjam:</label>
        <input type="text" name="staf" value="<?= $book['jumlah_pinjam'] ?>" required>

        <label>Jumlah Balik:</label>
        <input type="text" name="pinjam" value="<?= $book['jumlah_balik'] ?>" required>

        <label>Jumlah Akun:</label>
        <input type="text" name="waktu" value="<?= $book['jumlah_akun'] ?>" required>

        <input type="submit" name="edit_laporan" value="Update Laporan">

        <?php
            
        ?>
    </form>
</body>
</html>
