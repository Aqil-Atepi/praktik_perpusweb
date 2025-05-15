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
        mysqli_query($conn, "DELETE FROM pinjam WHERE id_pjm = $id");
        header("Location: pinjam.php");
        exit();
    }

    if (isset($_POST['add_pinjam'])) {
        $staf = mysqli_real_escape_string($conn, $_POST['staf']);
        $anggota = mysqli_real_escape_string($conn, $_POST['anggota']);
        $buku = mysqli_real_escape_string($conn, $_POST['buku']);
        $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);

        $query1 = "INSERT INTO pinjam (pjm_id_staf, pjm_id_anggota, pjm_id_buku, pjm_waktu_pinjam) VALUES 
                  ('$staf', '$anggota', '$buku', '$waktu')";
        mysqli_query($conn, $query1);

        $query2 = "INSERT INTO history_pinjam (htp_id_staf, htp_id_anggota, htp_id_buku, htp_waktu_pinjam, htp_waktu_history) VALUES 
                  ('$staf', '$anggota', '$buku', '$waktu', NOW())";
        mysqli_query($conn, $query2);
        header("Location: pinjam.php");
        exit();
    }

    if (isset($_POST['edit_pinjam'])) {
        $id = intval($_POST['id']);
        $staf = mysqli_real_escape_string($conn, $_POST['staf']);
        $anggota = mysqli_real_escape_string($conn, $_POST['anggota']);
        $buku = mysqli_real_escape_string($conn, $_POST['buku']);
        $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);

        $query1 = "UPDATE pinjam SET pjm_id_staf='$staf', pjm_id_anggota='$anggota', pjm_id_buku='$buku', pjm_waktu_pinjam='$waktu' 
                WHERE id_pjm=$id";
        mysqli_query($conn, $query1);
        header("Location: pinjam.php");
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
        <form method='post' action='pinjam.php'>
            <input type='text' name="search" placeholder="Cari dari ID, Staf, Anggota, atau Buku" />
            <input type='submit' name='search_btn' value='Search'>
            
            <select name='sort'>
                <option value='id_asc'>ID (Ascending)</option>
                <option value='id_desc'>ID (Descending)</option>
                <option value='staf_asc'>Staf (A - Z)</option>
                <option value='staf_desc'>Staf (Z - A)</option>
                <option value='anggota_asc'>Anggota (A - Z)</option>
                <option value='anggota_desc'>Anggota (Z - A)</option>
                <option value='buku_asc'>Buku (A - Z)</option>
                <option value='buku_desc'>Buku (Z - A)</option>
            </select>
            <input type='submit' name='sort_btn' value='Sort'>
        </form>

        <?php
            include 'db.php';
            $query = 'SELECT * FROM pinjam';
            
            if (isset($_POST['search_btn']) && !empty($_POST['search'])) {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                $query = "SELECT * FROM books WHERE 
                            id_pjm LIKE '%$search%' OR 
                            pjm_id_staf LIKE '%$search%' OR 
                            pjm_id_anggota LIKE '%$search%' OR
                            pjm_id_buku LIKE '%$search%'";
            }

            if (isset($_POST['sort_btn'])) {
                $order = $_POST['sort'];
                if ($order == 'id_asc') $query .= ' ORDER BY id_bku ASC';
                else if ($order == 'id_desc') $query .= ' ORDER BY id_bku DESC';
                else if ($order == 'staf_asc') $query .= ' ORDER BY pjm_id_staf ASC';
                else if ($order == 'staf_desc') $query .= ' ORDER BY pjm_id_staf DESC';
                else if ($order == 'anggota_asc') $query .= ' ORDER BY pjm_id_anggota ASC';
                else if ($order == 'anggota_desc') $query .= ' ORDER BY pjm_id_anggota DESC';
                else if ($order == 'buku_asc') $query .= ' ORDER BY pjm_id_buku ASC';
                else if ($order == 'buku_desc') $query .= ' ORDER BY pjm_id_buku DESC';
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<table border="1">';
                echo '<tr>
                        <th>ID Pinjam</th>
                        <th>Staf</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Waktu Pinjam</th>';
                if (isset($_SESSION['admin_logged_in']) || isset($_SESSION['staf_logged_in'])) {
                    echo '<th>Aksi</th>';
                }
                echo '</tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id_pjm'] . '</td>';
                    echo '<td>' . $row['pjm_id_staf'] . '</td>';
                    echo '<td>' . $row['pjm_id_anggota'] . '</td>';
                    echo '<td>' . $row['pjm_id_buku'] . '</td>';
                    echo '<td>' . $row['pjm_waktu_pinjam'] . '</td>';
                    if (isset($_SESSION['admin_logged_in']) || isset($_SESSION['staf_logged_in'])) {
                        echo '<td>
                                <a href="pinjam_edit.php?id=' . $row['id_pjm'] . '">Edit</a> /
                                <a href="pinjam.php?delete=' . $row['id_pjm'] . '">Hapus</a>
                            </td>';
                        echo '</tr>';
                    }
                }                
                echo '</table>';
            } else {
                echo 'Data Peminjaman tidak ditemukan.';
            }
        ?>
    </section>

    <section style="text-align: center;">
        <h2>Tambah Peminjaman</h2>
        <form method="post">
            <label>Staf:</label>
            <input type="text" name="staf" required>
            
            <label>Anggota:</label>
            <input type="text" name="anggota" required>
            
            <label>Buku:</label>
            <input type="text" name="buku" required>
            
            <label>Waktu Pinjam:</label>
            <input type="time" name="waktu" required>

            <input type="submit" name="add_pinjam" value="Tambah Peminjaman">
        </form>
    </section>

</body>
</html>
