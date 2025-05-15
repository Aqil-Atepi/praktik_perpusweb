<?php
    session_start();
    include 'db.php';

    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: index.php");
        exit();
    }

    if (isset($_GET['delete'])) {
        $id = intval($_GET['delete']);
        mysqli_query($conn, "DELETE FROM accounts WHERE id_acc = $id");
        header("Location: akun.php");
        exit();
    }

    if (isset($_POST['add_user'])) {
        $username = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_DEFAULT);
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        $query = "INSERT INTO accounts (acc_username, acc_email, acc_password, acc_role) VALUES 
                  ('$username', '$email', '$password', '$role')";
        mysqli_query($conn, $query);
        header("Location: akun.php");
        exit();
    }

    if (isset($_POST['edit_user'])) {
        $id = intval($_POST['id']);
        $username = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_DEFAULT);
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        $query = "UPDATE accounts SET acc_username='$username', acc_email='$email', acc_password='$password', acc_role='$role'
                WHERE id_acc=$id";
        mysqli_query($conn, $query);
        header("Location: akun.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Admin Panel
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
            <a href="akun.php">Akun</a>
        </div>
        <div class="right-link">
            <a>Hi, <?php echo $_SESSION['username']?></a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <section class="con">
        <h1>Data Akun</h1>
        <form method='post' action='akun.php'>
            <input type='text' name="search" placeholder="Cari dari ID, Nama, Email, atau Role" />
            <input type='submit' name='search_btn' value='Search'>
            
            <select name='sort'>
                <option value='id_asc'>ID (Ascending)</option>
                <option value='id_desc'>ID (Descending)</option>
                <option value='nama_asc'>Nama (A - Z)</option>
                <option value='nama_desc'>Nama (Z - A)</option>
                <option value='email_asc'>Email (A - Z)</option>
                <option value='email_desc'>Email (Z - A)</option>
                <option value='role_asc'>Role (A - Z)</option>
                <option value='role_desc'>Role (Z - A)</option>
            </select>
            <input type='submit' name='sort_btn' value='Sort'>
        </form>

        <?php
            include 'db.php';
            $query = 'SELECT * FROM accounts';
            
            if (isset($_POST['search_btn']) && !empty($_POST['search'])) {
                $search = mysqli_real_escape_string($conn, $_POST['search']);
                $query = "SELECT * FROM accounts WHERE 
                            id_acc LIKE '%$search%' OR 
                            acc_username LIKE '%$search%' OR 
                            acc_email LIKE '%$search%' OR
                            acc_role LIKE '%$search%'";
            }

            if (isset($_POST['sort_btn'])) {
                $order = $_POST['sort'];
                if ($order == 'id_asc') $query .= ' ORDER BY id_acc ASC';
                else if ($order == 'id_desc') $query .= ' ORDER BY id_acc DESC';
                else if ($order == 'nama_asc') $query .= ' ORDER BY acc_username ASC';
                else if ($order == 'nama_desc') $query .= ' ORDER BY acc_username DESC';
                else if ($order == 'email_asc') $query .= ' ORDER BY acc_email ASC';
                else if ($order == 'email_desc') $query .= ' ORDER BY acc_email DESC';
                else if ($order == 'role_asc') $query .= ' ORDER BY acc_role DESC';
                else if ($order == 'role_desc') $query .= ' ORDER BY acc_role DESC';
            }

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<table border="1">';
                echo '<tr>
                        <th>ID Akun</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Aksi</th>';
                echo '</tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['id_acc'] . '</td>';
                    echo '<td>' . $row['acc_username'] . '</td>';
                    echo '<td>' . $row['acc_email'] . '</td>';
                    echo '<td>' . $row['acc_password'] . '</td>';
                    echo '<td>' . $row['acc_role'] . '</td>';
                    echo '<td>
                                <a href="akun_edit.php?id=' . $row['id_acc'] . '">Edit</a> /
                                <a href="akun.php?delete=' . $row['id_acc'] . '">Hapus</a>
                            </td>';
                    echo '</tr>';
                }                
                echo '</table>';
            } else {
                echo 'Data User tidak ditemukan.';
            }
        ?>
    </section>

    <section style="text-align: center;">
        <h2>Tambah User</h2>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="name" required>
            
            <label>Email:</label>
            <input type="text" name="email" required>
            
            <label>Password:</label>
            <input type="password" name="password" required>
            
            <label>Role:</label>
            <input list="roles" name="role" required>
            <datalist id="roles" required>
                <option value="admin">
                <option value="staf">
                <option value="anggota">
            </datalist>

            <input type="submit" name="add_user" value="Tambah User">
        </form>
    </section>

</body>
</html>
