<?php
session_start();
include 'db.php';

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM balik WHERE id_blk = $id");
$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location: balik.php");
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
    <h2>Edit Pengembalian</h2>
    <form method="post" action="balik.php">
        <input type="hidden" name="id" value="<?= $book['id_bku'] ?>">

        <label>Staf:</label>
        <input type="text" name="staf" value="<?= $book['blk_id_staf'] ?>" required>

        <label>Pinjam:</label>
        <input type="text" name="pinjam" value="<?= $book['blk_id_pinjam'] ?>" required>

        <label>Waktu:</label>
        <input type="time" name="waktu" value="<?= $book['blk_waktu_balik'] ?>" required>

        <input type="submit" name="edit_balik" value="Update Balik">

        <?php
            
        ?>
    </form>
</body>
</html>
