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
        mysqli_query($conn, "DELETE FROM laporan WHERE id_blk = $id");
        header("Location: laporan.php");
        exit();
    }

    if (isset($_POST['add_laporan'])) {
        $jp = mysqli_real_escape_string($conn, $_POST['jp']);
        $jb = mysqli_real_escape_string($conn, $_POST['jb']);
        $ja = mysqli_real_escape_string($conn, $_POST['ja']);

        $query = "INSERT INTO laporan (jumlah_pinjam, jumlah_balik, jumlah_akun, waktu_laporan) VALUES 
                  ('$jp', '$jb', '$ja', NOW())";
        mysqli_query($conn, $query);
        header("Location: laporan.php");
        exit();
    }

    if (isset($_POST['edit_laporan'])) {
        $id = intval($_POST['id']);
        $jp = mysqli_real_escape_string($conn, $_POST['jp']);
        $jb = mysqli_real_escape_string($conn, $_POST['jb']);
        $ja = mysqli_real_escape_string($conn, $_POST['ja']);

        $query = "UPDATE laporan SET jumlah_pinjam='$jp', jumlah_balik='$jb', jumlah_akun='$ja', waktu_laporan='NOW()'
                WHERE id_laporan=$id";
        mysqli_query($conn, $query);
        header("Location: laporan.php");
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
        <h1>Laporan Perpusatakan</h1>
        <form method='post' action='laporan.php'>
            <input type='text' name="search" placeholder="Cari dari ID, Jumlah Pinjam, Jumlah Balik, atau Jumlah Akun" />
            <input type='submit' name='search_btn' value='Search'>
            
            <select name='sort'>
                <option value='id_asc'>ID (Ascending)</option>
                <option value='id_desc'>ID (Descending)</option>
                <option value='jp_asc'>Jumlah Pinjam (A - Z)</option>
                <option value='jp_desc'>Jumlah Pinjam (Z - A)</option>
                <option value='jb_asc'>Jumlah Balik (A - Z)</option>
                <option value='jb_desc'>Jumlah Balik (Z - A)</option>
                <option value='ja_asc'>Jumlah Akun (A - Z)</option>
                <option value='ja_desc'>Jumlah Akun (Z - A)</option>
            </select>
            <input type='submit' name='sort_btn' value='Sort'>
        </form>

        <?php
            include 'db.php';
            $query = 'SELECT * FROM laporan';
            
            if (isset($_POST['search_btn']) && !empty($_POST['search'])) {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                $query = "SELECT * FROM laporan WHERE 
                            id_laporan LIKE '%$search%' OR 
                            jumlah_pinjam LIKE '%$search%' OR 
                            jumlah_balik LIKE '%$search%' OR
                            jumlah_akun LIKE '%$search%'";
            }

            if (isset($_POST['sort_btn'])) {
                $order = $_POST['sort'];
                if ($order == 'id_asc') $query .= ' ORDER BY id_bku ASC';
                else if ($order == 'id_desc') $query .= ' ORDER BY id_bku DESC';
                else if ($order == 'jp_asc') $query .= ' ORDER BY jumlah_pinjam ASC';
                else if ($order == 'jp_desc') $query .= ' ORDER BY jumlah_pinjam DESC';
                else if ($order == 'jb_asc') $query .= ' ORDER BY jumlah_balik ASC';
                else if ($order == 'jb_desc') $query .= ' ORDER BY jumlah_balik DESC';
                else if ($order == 'ja_asc') $query .= ' ORDER BY jumlah_akun ASC';
                else if ($order == 'ja_desc') $query .= ' ORDER BY jumlah_akun DESC';
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<table border="1">';
                echo '<tr>
                        <th>ID laporan</th>
                        <th>Jumlah Pinjam</th>
                        <th>Jumlah Balik</th>
                        <th>Jumlah Akun</th>
                        <th>Waktu Laporan</th>';
                if (isset($_SESSION['admin_logged_in']) || isset($_SESSION['staf_logged_in'])) {
                    echo '<th>Aksi</th>';
                }
                echo '</tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id_laporan'] . '</td>';
                    echo '<td>' . $row['jumlah_pinjam'] . '</td>';
                    echo '<td>' . $row['jumlah_balik'] . '</td>';
                    echo '<td>' . $row['jumlah_akun'] . '</td>';
                    echo '<td>' . $row['waktu_laporan'] . '</td>';
                    if (isset($_SESSION['admin_logged_in']) || isset($_SESSION['staf_logged_in'])) {
                        echo '<td>
                                <a href="laporan_edit.php?id=' . $row['id_laporan'] . '">Edit</a> /
                                <a href="laporan.php?delete=' . $row['id_laporan'] . '">Hapus</a>
                            </td>';
                        echo '</tr>';
                    }
                }                
                echo '</table>';
            } else {
                echo 'Data Laporan tidak ditemukan.';
            }
        ?>
    </section>

    <section style="text-align: center;">
        <h2>Tambah Laporan</h2>
        <form method="post">
            <label>Jumlah Pinjam:</label>
            <input type="text" name="jp" required>
            
            <label>Jumlah Balik:</label>
            <input type="text" name="jb" required>
            
            <label>Jumlah Akun:</label>
            <input type="text" name="ja" required>

            <input type="submit" name="add_laporan" value="Tambah Laporan">
        </form>
    </section>

</body>
</html>
