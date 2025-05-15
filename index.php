<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BILIBRARY</title>
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
            <a href="index.php">Home</a>
        </div>
        <div class="right-link">
            <a href="login.php">Login</a>
        </div>
    </nav>


    <section class="con">
        <h1>Koleksi Buku BI</h1>
        <form method='post' action='index.php'>
            <input type='text' name="search" placeholder="Cari dari ID, Judul, Penulis, Penerbit atau Katalog" />
            <input type='submit' name='search_btn' value='Search'>
            
            <select name='sort'>
                <option value='id_asc'>ID (Ascending)</option>
                <option value='id_desc'>ID (Descending)</option>
                <option value='judul_asc'>Judul (A - Z)</option>
                <option value='judul_desc'>Judul (Z - A)</option>
                <option value='penulis_asc'>Penulis (A - Z)</option>
                <option value='penulis_desc'>Penulis (Z - A)</option>
                <option value='penerbit_asc'>Penerbit (A - Z)</option>
                <option value='penerbit_desc'>Penerbit (Z - A)</option>
                <option value='katalog_asc'>Katalog (A - Z)</option>
                <option value='katalog_desc'>Katalog (Z - A)</option>
            </select>
            <input type='submit' name='sort_btn' value='Sort'>
        </form>

        <?php
            include 'db.php';
            $query = 'SELECT * FROM buku';
            
            if (isset($_POST['search_btn']) && !empty($_POST['search'])) {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                $query = "SELECT * FROM books WHERE 
                            id_bku LIKE '%$search%' OR 
                            bku_judul LIKE '%$search%' OR 
                            bku_nama_penulis LIKE '%$search%' OR
                            bku_nama_penerbit LIKE '%$search%' OR
                            bku_katalog LIKE '%$search%'";
            }

            if (isset($_POST['sort_btn'])) {
                $order = $_POST['sort'];
                if ($order == 'id_asc') $query .= ' ORDER BY id_bku ASC';
                else if ($order == 'id_desc') $query .= ' ORDER BY id_bku DESC';
                else if ($order == 'judul_asc') $query .= ' ORDER BY bku_judul ASC';
                else if ($order == 'judul_desc') $query .= ' ORDER BY bku_judul DESC';
                else if ($order == 'penulis_asc') $query .= ' ORDER BY bku_nama_penulis ASC';
                else if ($order == 'penulis_desc') $query .= ' ORDER BY bku_nama_penulis DESC';
                else if ($order == 'penerbit_asc') $query .= ' ORDER BY bku_nama_penerbit ASC';
                else if ($order == 'penerbit_desc') $query .= ' ORDER BY bku_nama_penerbit DESC';
                else if ($order == 'katalog_asc') $query .= ' ORDER BY bku_katalog ASC';
                else if ($order == 'katalog_desc') $query .= ' ORDER BY bku_katalog DESC';
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<table border="1">';
                echo '<tr>
                        <th>ID Buku</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Penerbit</th>
                        <th>Katalog</th>
                    </tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id_bku'] . '</td>';
                    echo '<td>' . $row['bku_judul'] . '</td>';
                    echo '<td>' . $row['bku_nama_penulis'] . '</td>';
                    echo '<td>' . $row['bku_nama_penerbit'] . '</td>';
                    echo '<td>' . $row['bku_katalog'] . '</td>';
                    echo '</tr>';
                }                
                echo '</table>';
            } else {
                echo 'Data Buku tidak ditemukan.';
            }
        ?>
    </section>

</body>
</html>
