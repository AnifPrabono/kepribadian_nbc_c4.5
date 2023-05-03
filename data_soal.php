<?php 
require './koneksi.php';

session_start();
 if (!isset($_SESSION['kepribadian_nbc_c4.5_id'])) {
     header("location:index.php?menu=forbidden");
}

include_once "database.php";
include_once "fungsi.php";
include_once "excel_reader2.php";

 ?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Data Soal</title>
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
                        <h2 class="title1">Data Soal</h2>                          
                             
                        
                                        
                                    

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
                                                    $data = new Spreadsheet_Excel_Reader($_FILES['file_data_soal']['tmp_name']);

                                                    $baris = $data->rowcount($sheet_index = 0);
                                                    $column = $data->colcount($sheet_index = 0);
                                                    //import data excel dari baris kedua, karena baris pertama adalah nama kolom
                                                    // $temp_date = $temp_produk = "";
                                                    for ($i = 2; $i <= $baris; $i++) {
                                                        $value = "(\"" . $data->val($i, 2) . "\", \"" . $data->val($i, 3) . "\", ".
                                                                "\"". $data->val($i, 4) . "\", \"" . $data->val($i, 5) . "\")";
                                                        $sql = "INSERT INTO data_soal "
                                                                . " (pilihan_a, pilihan_b, pilihan_c, pilihan_d)"
                                                                . " VALUES " . $value;
                                                        $result = $db_object->db_query($sql);
                                                    }
                                                    if ($result) {
                                                        ?>
                                                        <script> location.replace("?menu=data_soal&pesan_success=Data berhasil disimpan");</script>
                                                        <?php
                                                    } 
                                                    else {
                                                        ?>
                                                        <script> location.replace("?menu=data_soal&pesan_error=Data gagal disimpan");</script> 
                                                        <?php
                                                    }
                                                }

                                                if (isset($_POST['delete'])) {
                                                    $sql = "TRUNCATE data_soal";
                                                    $db_object->db_query($sql);
                                                    ?>
                                                    <script> location.replace("?menu=data_soal&pesan_success=Data soal berhasil dihapus");</script>
                                                    <?php
                                                }

                                                $sql = "SELECT soal.* FROM data_soal soal ORDER BY id";
                                                $query = $db_object->db_query($sql);
                                                $jumlah = $db_object->db_num_rows($query);
                                                ?>

                                                <div class="row">
                                                    <div class="col-md-12">

                                                        <!--UPLOAD EXCEL FORM-->
                                                        <form method="post" enctype="multipart/form-data" action="">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <label>Import data from excel</label>
                                                                    <input name="file_data_soal" type="file" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <input name="submit" type="submit" value="Upload Data" class="btn btn-success">
                                                                <button name="delete" type="submit"  class="btn btn-danger" onclick="">
                                                                    <i class="fa fa-trash-o"></i> Delete All Data Soal
                                                                </button>
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
                                                        } else {
                                                            ?>
                                                            <table class='table table-bordered table-striped  table-hover'>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Pilihan A</th>
                                                                    <th>Pilihan B</th>
                                                                    <th>Pilihan C</th>
                                                                    <th>Pilihan D</th>
                                                                </tr>
                                        <?php
                                        $no = 1;
                                        while ($row = $db_object->db_fetch_array($query)) {
                                            echo "<tr>";
                                            echo "<td>" . $no . "</td>";
                                            echo "<td>" . $row['pilihan_a'] . "</td>";
                                            echo "<td>" . $row['pilihan_b'] . "</td>";
                                            echo "<td>" . $row['pilihan_c'] . "</td>";
                                            echo "<td>" . $row['pilihan_d'] . "</td>";
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

