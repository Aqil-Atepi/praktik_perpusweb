<?php
session_start();
include 'db.php';

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM buku WHERE id_bku = $id");
$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Buku</h2>
    <form method="post" action="home.php">
        <input type="hidden" name="id" value="<?= $book['id_bku'] ?>">

        <label>Judul:</label>
        <input type="text" name="judul" value="<?= $book['bku_judul'] ?>" required>

        <label>Penulis:</label>
        <input type="text" name="penulis" value="<?= $book['bku_nama_penulis'] ?>" required>

        <label>Penerbit:</label>
        <input type="text" name="penerbit" value="<?= $book['bku_nama_penerbit'] ?>" required>

        <label>Katalog:</label>
        <input type="text" name="katalog" value="<?= $book['bku_katalog'] ?>" required>

        <input type="submit" name="edit_book" value="Update Book">

        <?php
            
        ?>
    </form>
</body>
</html>
