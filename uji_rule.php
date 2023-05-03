<!DOCTYPE HTML>
<html>
    <head>
        <title>Uji Rule</title>
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
                        <h2 class="title1">Uji Akurasi Metode Decision Tree C4.5</h2>

                                   

<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
include_once "database.php";
include_once "excel_reader2.php";
include_once "fungsi.php";
//object database class
$db_object = new database();
?>

            <?php
            if(isset($_POST['upload'])){
                $data = new Spreadsheet_Excel_Reader($_FILES['file_data']['tmp_name']);

                $baris = $data->rowcount($sheet_index = 0);
                $column = $data->colcount($sheet_index = 0);
                //import data excel dari baris kedua, karena baris pertama adalah nama kolom
                // $temp_date = $temp_produk = "";
                for ($i = 2; $i <= $baris; $i++) {
                    if(!empty($data->val($i, 2))){
                        $value = "(\"" . $data->val($i, 2) . "\", '" . $data->val($i, 3) . "', "
                                . $data->val($i, 4) . ", '" . $data->val($i, 5) . "', "
                                . $data->val($i, 6) . ", " . $data->val($i, 7) . ", "
                                . $data->val($i, 8) . ", " . $data->val($i, 9) . ", '" . $data->val($i, 10) . "')";
                        $sql = "INSERT INTO data_uji "
                                . " (nama, jenis_kelamin, usia, sekolah, jawaban_a, jawaban_b, jawaban_c, jawaban_d, kelas_asli)"
                                . " VALUES " . $value;
                        $result = $db_object->db_query($sql);
                    }
                }
                if ($result) {
                    ?>
                    <script> location.replace("?menu=uji_rule&pesan_success=Data berhasil disimpan");</script>
                    <?php
                } 
                else {
                    ?>
                    <script> location.replace("?menu=uji_rule&pesan_error=Data gagal disimpan");</script>
                    <?php
                }
            }
            
            if (isset($_GET['act'])) {
                $action = $_GET['act'];
                //delete semua data
                if ($action == 'delete_all') {
                    $db_object->db_query("TRUNCATE data_uji");
                    //header('location:?menu=uji_rule');
                    ?>
                    <script> location.replace("?menu=uji_rule&pesan_success=Data uji berhasil dihapus");</script>
                    <?php
                }
            } 
            else {
                if (isset($_POST['submit'])) {
                    include "hitung_akurasi.php";
                } 
                else {
                    $query = $db_object->db_query("SELECT * FROM data_uji order by(id)");
                    $jumlah = $db_object->db_num_rows($query);
                    echo "<br><br>";
                    ?>

                    <form method="post" enctype="multipart/form-data" action="">
                        <div class="form-group">
                            <div class="input-group">
                                <label>Import data from excel</label>
                                <input name="file_data" type="file" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <input name="upload" type="submit" value="Upload Data" class="btn btn-success">
                            <a href="?menu=uji_rule&act=delete_all" class="btn btn-danger"
                               onClick="return confirm('Anda yakin akan hapus semua data?')">
                                <i class="fa fa-trash"></i> Delete All Data uji
                            </a>
                        </div>
                    </form>
                    <?php
                    if ($jumlah == 0) {
                        echo "<center><h3>Data uji masih kosong...</h3></center>";
                    } 
                    else {
            ?>
                        <center>
                            <form method="POST" action=''>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="submit" name="submit" value="HitungAkurasi" class="btn btn-success">
                                    </div>
                                </div>
                            </form>
                        </center>
                        Jumlah data uji: <?php echo $jumlah; ?>

                        <table class='table table-bordered table-striped  table-hover'>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Usia</th>
                                <th>Sekolah</th>
                                <th>Jawaban A</th>
                                <th>Jawaban B</th>
                                <th>Jawaban C</th>
                                <th>Jawaban D</th>
                                <th>Kelas Asli</th>
                            </tr>
            <?php
    $no = 1;
    while ($row = $db_object->db_fetch_array($query)) {
        echo "<tr>";
        echo "<td>" . $no . "</td>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td>" . $row['jenis_kelamin'] . "</td>";
        echo "<td>" . $row['usia'] . "</td>";
        echo "<td>" . $row['sekolah'] . "</td>";
        echo "<td>" . $row['jawaban_a'] . "</td>";
        echo "<td>" . $row['jawaban_b'] . "</td>";
        echo "<td>" . $row['jawaban_c'] . "</td>";
        echo "<td>" . $row['jawaban_d'] . "</td>";
        echo "<td>" . $row['kelas_asli'] . "</td>";
        echo "</tr>";
        $no++;
    }
    ?>
            
                        </table>
                            <?php
                        }
                    }
                }
                ?>




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