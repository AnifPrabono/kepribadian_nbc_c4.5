<?php
session_start(); // harus ada di bagian paling atas kode
$path_to_root = "";
include $path_to_root . 'database.php';

//object database class
$db = new database();

$user = strip_tags(trim($_POST['username'])); #echo $user;
$pass = strip_tags(trim($_POST['password'])); #echo $pass;

$sql = get_sql_login_admin_page($user, $pass);

$result = $db->db_query($sql);
$num_rows = $db->db_num_rows($result);

if ($num_rows > 0) {
    $rows = $db->db_fetch_array($result);
    
        unset($_POST); // hapus post form
        $_SESSION['kepribadian_nbc_c4.5_id'] = $rows['id_user']; // mengisi session
        $_SESSION['kepribadian_nbc_c4.5_nama'] = $rows['nama'];
        $_SESSION['kepribadian_nbc_c4.5_username'] = $rows['username'];
        $_SESSION['kepribadian_nbc_c4.5_level'] = $rows['level'];
        $_SESSION['kepribadian_nbc_c4.5_id_siswa'] = get_id_siswa_by_id_user($db, $rows['id_user']);

        $level_name = ($_SESSION['kepribadian_nbc_c4.5_level']==1)?"guru":"siswa";
        $_SESSION['kepribadian_nbc_c4.5_level_name'] = $level_name;
        $_SESSION['kepribadian_nbc_c4.5_key'] = sha1(date("Y-m-d H:i:s") . $rows['id']);
        $_SESSION['kepribadian_nbc_c4.5_last_login'] = date("d-m-Y H:i:s");
        header("location:index.php");
} else {
    header("location:login.php?login=1");
}

function get_id_siswa_by_id_user($db, $id_user){
    $sql = "SELECT id FROM data_siswa WHERE id_user = ".$id_user;
    $result = $db->db_query($sql);
    $row = $db->db_fetch_array($result);
    return (!empty($row['id'])?$row['id']:0);
}

/**
 * query get login 
 * @param string $user username
 * @param string $pass password
 * @return string
 */
function get_sql_login_admin_page($user, $pass){
    $sql = "SELECT * FROM users"
        . " WHERE username = '" . $user . "' AND password = MD5('" . $pass . "')";
        echo $sql;
    return $sql;
}

?>
