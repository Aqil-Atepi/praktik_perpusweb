<?php
session_start();
include 'db.php';

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM pinjam WHERE id_pjm = $id");
$book = mysqli_fetch_assoc($result);

if (!$book) {
    header("Location: pinjam.php");
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
    <h2>Edit Pinjam</h2>
    <form method="post" action="pinjam.php">
        <input type="hidden" name="id" value="<?= $book['id_pjm'] ?>">

        <label>Staf:</label>
        <input type="text" name="staf" value="<?= $book['pjm_id_staf'] ?>" required>

        <label>Anggota:</label>
        <input type="text" name="anggota" value="<?= $book['pjm_id_anggota'] ?>" required>

        <label>Buku:</label>
        <input type="text" name="buku" value="<?= $book['pjm_id_buku'] ?>" required>

        <label>Waktu:</label>
        <input type="time" name="waktu" value="<?= $book['pjm_waktu_pinjam'] ?>" required>

        <input type="submit" name="edit_pinjam" value="Update Pinjam">

        <?php
            
        ?>
    </form>
</body>
</html>
