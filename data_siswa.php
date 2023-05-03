<?php
//session_start();
if (!isset($_SESSION['kepribadian_nbc_c4.5_id'])) {
    header("location:index.php?menu=forbidden");
}

include_once "database.php";
include_once "fungsi.php";
//include_once "import/excel_reader2.php";
?>


<!DOCTYPE HTML>
<html>
    <head>
        <title>Data Siswa</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="Glance Design Dashboard Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
        SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />

        <!-- Custom CSS -->
        <link href="css/style.css" rel='stylesheet' type='text/css' />

        <!-- font-awesome icons CSS -->
        <link href="css/font-awesome.css" rel="stylesheet"> 
        <!-- //font-awesome icons CSS -->

        <!-- side nav css file -->
        <link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
        <!-- side nav css file -->

        <!-- js-->
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/modernizr.custom.js"></script>

        <!--webfonts-->
        <link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
        <!--//webfonts--> 

        <!-- Metis Menu -->
        <script src="js/metisMenu.min.js"></script>
        <script src="js/custom.js"></script>
        <link href="css/custom.css" rel="stylesheet">
        <!--//Metis Menu -->

    </head> 
    <body class="cbp-spmenu-push">
        <div class="main-content">

            
            <!-- main content start-->
            <div id="page-wrapper">
                <div class="main-page">
                    <div class="forms">
                        <h2 class="title1">Input Data Siswa</h2>

                        <div class="content-wrapper">
                            <?php
                            //object database class
                            $db_object = new database();

                            $pesan_error = $pesan_success = "";
                            if (isset($_GET['pesan_error'])) {
                                $pesan_error = $_GET['pesan_error'];
                            }
                            if (isset($_GET['pesan_success'])) {
                                $pesan_success = $_GET['pesan_success'];
                            }

                            if (isset($_POST['submit'])) {
                                $sql1 = "INSERT INTO users "
                                        . " (nama, username, password, level)"
                                        . " VALUES (\"".$_POST['nama']."\", \"".$_POST['user_name']."\", md5(\"".$_POST['user_password']."\"), 2)";
                                $result1 = $db_object->db_query($sql1);
                                $id_usernya = $db_object->db_insert_id();
                                
                                $sql = "INSERT INTO data_siswa "
                                    . " (nama_siswa, jenis_kelamin, usia, sekolah, id_user)"
                                    . " VALUES "
                                    . " (\"".$_POST['nama']."\", \"".$_POST['jenis_kelamin']."\", \"".$_POST['usia']."\","
                                        . " \"".$_POST['sekolah']."\", $id_usernya)";
                                $result = $db_object->db_query($sql);
                                
                                
                                if($result && $result1){
                                    ?>
                                    <script> location.replace("?menu=data_siswa&pesan_success=Data berhasil disimpan");</script>
                                    <?php
                                }
                                else{
                                    ?>
                                    <script> location.replace("?menu=data_siswa&pesan_error=Data gagal disimpan");</script>
                                    <?php
                                }
                            }

                            if (isset($_GET['delete'])) {
                                $id_delete = $_GET['delete'];
                                $id_usere = get_id_user_siswa($db_object, $id_delete);
                                $sql = "DELETE FROM data_siswa WHERE id=".$id_delete;
                                $db_object->db_query($sql);
                                
                                $sql = "DELETE FROM users WHERE id_user=".$id_usere;
                                $db_object->db_query($sql);
                                ?>
                                <script> location.replace("?menu=data_siswa&pesan_success=Data siswa berhasil dihapus");</script>
                                <?php
                            }

                            $sql = "SELECT siswa.*, usr.username FROM data_siswa siswa, users usr
                                    WHERE siswa.`id_user` = usr.`id_user`";
                            $query = $db_object->db_query($sql);
                            $jumlah = $db_object->db_num_rows($query);
                            ?>

                            <div class="row">
                                <div class="col-md-12">
                                    
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label>Nama</label>
                                                <input name="nama" type="text" class="form-control" required="">
                                            </div>
                                            <div class="input-group">
                                                <label>Username</label>
                                                <input name="user_name" type="text" class="form-control" required="">
                                            </div>
                                            <div class="input-group">
                                                <label>Password</label>
                                                <input name="user_password" type="password" class="form-control" required="">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <label>Jenis Kelamin</label>
                                                <div class="radio">
                                                    <label>
                                                        <input name="jenis_kelamin" type="radio" value="L" required=""> Laki-laki
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input name="jenis_kelamin" type="radio" value="P" required=""> Perempuan
                                                    </label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <label>Usia</label>
                                                <input name="usia" type="text" class="form-control" required="">
                                            </div>
                                            <br>
                                            <div class="input-group">
                                                <label>Asal Sekolah</label>
                                                <div class="radio">
                                                    <label>
                                                        <input name="sekolah" type="radio" value="Negeri" required=""> Negeri
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input name="sekolah" type="radio" value="Swasta" required=""> Swasta
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input name="submit" type="submit" value="Save" class="btn btn-success">
                                        </div>
                                    </form>

                                    <?php
                                    if (!empty($pesan_error)) {
                                        display_error($pesan_error);
                                    }
                                    if (!empty($pesan_success)) {
                                        display_success($pesan_success);
                                    }


                                    echo "Jumlah data: " . $jumlah . "<br>";
                                    if ($jumlah == 0) {
                                        echo "Data kosong...";
                                    } 
                                    else {
                                        ?>
                                        <table class='table table-bordered table-striped  table-hover'>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Usia</th>
                                                <th>Asal Sekolah</th>
                                                <th>Username</th>
                                                <th>Hapus</th>
                                            </tr>
                                            <?php
                                            $no = 1;
                                            while ($row = $db_object->db_fetch_array($query)) {
                                                echo "<tr>";
                                                echo "<td>" . $no . "</td>";
                                                echo "<td>" . $row['nama_siswa'] . "</td>";
                                                echo "<td>" . $row['jenis_kelamin'] . "</td>";
                                                echo "<td>" . $row['usia'] . "</td>";
                                                echo "<td>" . $row['sekolah'] . "</td>";
                                                echo "<td>" . $row['username'] . "</td>";
                                                echo "<td><a href='?menu=data_siswa&delete=".$row['id']."'>"
                                                        . "<img src='images/delete.png'/></a></td>";
                                                echo "</tr>";
                                                $no++;
                                            }
                                            ?>
                                        </table>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- side nav js -->
        <script src='js/SidebarNav.min.js' type='text/javascript'></script>
        <script>
            $('.sidebar-menu').SidebarNav()
        </script>
        <!-- //side nav js -->

        <!-- Classie --><!-- for toggle left push menu script -->
        <script src="js/classie.js"></script>
        <script>
            var menuLeft = document.getElementById('cbp-spmenu-s1'),
                    showLeftPush = document.getElementById('showLeftPush'),
                    body = document.body;

            showLeftPush.onclick = function () {
                classie.toggle(this, 'active');
                classie.toggle(body, 'cbp-spmenu-push-toright');
                classie.toggle(menuLeft, 'cbp-spmenu-open');
                disableOther('showLeftPush');
            };

            function disableOther(button) {
                if (button !== 'showLeftPush') {
                    classie.toggle(showLeftPush, 'disabled');
                }
            }
        </script>
        <!-- //Classie --><!-- //for toggle left push menu script -->

        <!--scrolling js-->
        <script src="js/jquery.nicescroll.js"></script>
        <script src="js/scripts.js"></script>
        <!--//scrolling js-->

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.js"></script>

    </body>
</html>