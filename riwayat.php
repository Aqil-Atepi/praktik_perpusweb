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
        <h1>Riwayat Peminjaman Buku</h1>
        <form method='post' action='riwayat.php'>
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
            $query = 'SELECT * FROM history_pinjam';
            
            if (isset($_POST['search_btn']) && !empty($_POST['search'])) {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                $query = "SELECT * FROM history_pinjam WHERE 
                            id_htp LIKE '%$search%' OR 
                            htp_id_staf LIKE '%$search%' OR 
                            htp_id_anggota LIKE '%$search%' OR
                            htp_id_buku LIKE '%$search%'";
            }

            if (isset($_POST['sort_btn'])) {
                $order = $_POST['sort'];
                if ($order == 'id_asc') $query .= ' ORDER BY id_htp ASC';
                else if ($order == 'id_desc') $query .= ' ORDER BY id_htp DESC';
                else if ($order == 'staf_asc') $query .= ' ORDER BY htp_id_staf ASC';
                else if ($order == 'staf_desc') $query .= ' ORDER BY htp_id_staf DESC';
                else if ($order == 'anggota_asc') $query .= ' ORDER BY htp_id_anggota ASC';
                else if ($order == 'anggota_desc') $query .= ' ORDER BY htp_id_anggota DESC';
                else if ($order == 'buku_desc') $query .= ' ORDER BY htp_id_buku ASC';
                else if ($order == 'buku_desc') $query .= ' ORDER BY htp_id_buku DESC';
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<table border="1">';
                echo '<tr>
                        <th>ID Riwayat</th>
                        <th>Staf</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Waktu Pinjam</th>
                        <th>Waktu History</th>';
                echo '</tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id_htp'] . '</td>';
                    echo '<td>' . $row['htp_id_staf'] . '</td>';
                    echo '<td>' . $row['htp_id_anggota'] . '</td>';
                    echo '<td>' . $row['htp_id_buku'] . '</td>';
                    echo '<td>' . $row['htp_waktu_pinjam'] . '</td>';
                    echo '<td>' . $row['htp_waktu_history'] . '</td>';
                }                
                echo '</table>';
            } else {
                echo 'Data Riwayat tidak ditemukan.';
            }
        ?>
    </section>
</body>
</html>
