<?php
    session_start();
    include 'db.php';

    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }

    if(isset($_SESSION['admin_logged_in'])) {
        $webtitle = "Admin Panel";
    }
    else if(isset($_SESSION['staf_logged_in'])) {
        $webtitle = "Staf Panel";
    }
    else
    {
        $webtitle = "BILIBRARY";
    }

    if (isset($_GET['delete'])) {
        $id = intval($_GET['delete']);
        mysqli_query($conn, "DELETE FROM balik WHERE id_blk = $id");
        header("Location: balik.php");
        exit();
    }

    if (isset($_POST['add_balik'])) {
        $staf = mysqli_real_escape_string($conn, $_POST['staf']);
        $pinjam = mysqli_real_escape_string($conn, $_POST['pinjam']);
        $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);

        $query = "INSERT INTO balik (blk_id_staf, blk_id_pinjam, blk_waktu_balik) VALUES 
                  ('$staf', '$pinjam', '$waktu')";
        mysqli_query($conn, $query);
        header("Location: balik.php");
        exit();
    }

    if (isset($_POST['edit_balik'])) {
        $id = intval($_POST['id']);
        $staf = mysqli_real_escape_string($conn, $_POST['staf']);
        $pinjam = mysqli_real_escape_string($conn, $_POST['pinjam']);
        $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);

        $query = "UPDATE balik SET blk_id_staf='$staf', blk_id_pinjam='$pinjam', blk_waktu_balik='$waktu'
                WHERE id_blk=$id";
        mysqli_query($conn, $query);
        header("Location: balik.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $webtitle?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div id="head_con">
            <img src="assets/logo_bi.png" style="width: auto; height: 200px;">
        </div>
    </header>

    <nav>
        <div class="center-links">
            <a href="home.php">Home</a>
            <a href="pinjam.php">Pinjam</a>
            <a href="balik.php">Balik</a>
            <a href="riwayat.php">Riwayat</a>
            <a href="laporan.php">Laporan</a>
            <?php
                if (isset($_SESSION['admin_logged_in'])) {
                    echo '<a href="akun.php">Akun</a>';
                }
            ?>
        </div>
        <div class="right-link">
            <a>Hi, <?php echo $_SESSION['username']?></a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <section class="con">
        <h1>Data Peminjaman Buku</h1>
        <form method='post' action='balik.php'>
            <input type='text' name="search" placeholder="Cari dari ID, Staf, atau Pinjaman" />
            <input type='submit' name='search_btn' value='Search'>
            
            <select name='sort'>
                <option value='id_asc'>ID (Ascending)</option>
                <option value='id_desc'>ID (Descending)</option>
                <option value='staf_asc'>Staf (A - Z)</option>
                <option value='staf_desc'>Staf (Z - A)</option>
                <option value='pinjam_asc'>Pinjam (A - Z)</option>
                <option value='pinjam_desc'>Pinjam (Z - A)</option>
            </select>
            <input type='submit' name='sort_btn' value='Sort'>
        </form>

        <?php
            include 'db.php';
            $query = 'SELECT * FROM balik';
            
            if (isset($_POST['search_btn']) && !empty($_POST['search'])) {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                $query = "SELECT * FROM books WHERE 
                            id_blk LIKE '%$search%' OR 
                            blk_id_staf LIKE '%$search%' OR 
                            blk_id_pinjam LIKE '%$search%' OR
                            blk_waktu_balik LIKE '%$search%'";
            }

            if (isset($_POST['sort_btn'])) {
                $order = $_POST['sort'];
                if ($order == 'id_asc') $query .= ' ORDER BY id_bku ASC';
                else if ($order == 'id_desc') $query .= ' ORDER BY id_bku DESC';
                else if ($order == 'staf_asc') $query .= ' ORDER BY blk_id_staf ASC';
                else if ($order == 'staf_desc') $query .= ' ORDER BY blk_id_staf DESC';
                else if ($order == 'pinjam_asc') $query .= ' ORDER BY blk_id_pinjam ASC';
                else if ($order == 'pinjam_desc') $query .= ' ORDER BY blk_id_pinjam DESC';
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<table border="1">';
                echo '<tr>
                        <th>ID Balik</th>
                        <th>Staf</th>
                        <th>Pinjam</th>
                        <th>Waktu Balik</th>';
                if (isset($_SESSION['admin_logged_in']) || isset($_SESSION['staf_logged_in'])) {
                    echo '<th>Aksi</th>';
                }
                echo '</tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id_blk'] . '</td>';
                    echo '<td>' . $row['blk_id_staf'] . '</td>';
                    echo '<td>' . $row['blk_id_pinjam'] . '</td>';
                    echo '<td>' . $row['blk_waktu_balik'] . '</td>';
                    if (isset($_SESSION['admin_logged_in']) || isset($_SESSION['staf_logged_in'])) {
                        echo '<td>
                                <a href="balik_edit.php?id=' . $row['id_blk'] . '">Edit</a> /
                                <a href="balik.php?delete=' . $row['id_blk'] . '">Hapus</a>
                            </td>';
                        echo '</tr>';
                    }
                }                
                echo '</table>';
            } else {
                echo 'Data Pengembalian tidak ditemukan.';
            }
        ?>
    </section>

    <section style="text-align: center;">
        <h2>Tambah Pengembalian</h2>
        <form method="post">
            <label>Staf:</label>
            <input type="text" name="staf" required>
            
            <label>Pinjaman:</label>
            <input type="text" name="pinjam" required>
            
            <label>Waktu Balik:</label>
            <input type="time" name="waktu" required>

            <input type="submit" name="add_balik" value="Tambah Pengembalian">
        </form>
    </section>

</body>
</html>
