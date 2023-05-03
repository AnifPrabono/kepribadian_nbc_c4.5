<?php 
require './koneksi.php';

//session_start();
// if (!isset($_SESSION['kepribadian_nbc_c4.5_id'])) {
//     header("location:index.php?menu=forbidden");
// }

include_once "database.php";
include_once "fungsi.php";
include_once "fungsi_proses.php";
include_once "excel_reader2.php";


 ?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Uji Akurasi</title>
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
                        <h2 class="title1" style="text-align:center;">Perhitungan Algoritma Naive Bayes</h2>
        
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
                                    // if(!$input_error){
                                    $data = new Spreadsheet_Excel_Reader($_FILES['file_data_uji']['tmp_name']);

                                    $baris = $data->rowcount($sheet_index = 0);
                                    $column = $data->colcount($sheet_index = 0);
                                    //import data excel dari baris kedua, karena baris pertama adalah nama kolom
                                    // $temp_date = $temp_produk = "";
                                    for ($i=2; $i<=$baris; $i++) {
                        //                for($c=1; $c<=$column; $c++){
                        //                    $value[$c] = $data->val($i, $c);
                        //                }
                                        if(!empty($data->val($i, 2))){
                                            $value = "(\"".$data->val($i, 2)."\", '".$data->val($i, 3)."', "
                                                    .$data->val($i, 4).", '".$data->val($i, 5)."', "
                                                    .$data->val($i, 6).", ".$data->val($i, 7).", "
                                                    .$data->val($i, 8).", ".$data->val($i, 9).", '".$data->val($i, 10)."')";
                                            $sql = "INSERT INTO data_uji "
                                                . " (nama, jenis_kelamin, usia, sekolah, jawaban_a, jawaban_b, jawaban_c, jawaban_d, kelas_asli)"
                                                . " VALUES ".$value;
                                            $result = $db_object->db_query($sql);
                                        }
                                    }
                                    //$values = implode(",", $value);
                                    
                                    if($result){
                                        ?>
                                        <script> location.replace("?menu=uji_akurasi&pesan_success=Data berhasil disimpan");</script>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <script> location.replace("?menu=uji_akurasi&pesan_error=Data gagal disimpan");</script>
                                        <?php
                                    }
                                }

                                if (isset($_POST['delete'])) {
                                    $sql = "TRUNCATE data_uji";
                                    $db_object->db_query($sql);
                                    ?>
                                    <script> location.replace("?menu=uji_akurasi&pesan_success=Data uji berhasil dihapus");</script>
                                    <?php
                                }
                                
                                $sql = "SELECT * FROM data_uji";
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
                                                    <input name="file_data_uji" type="file" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input name="submit" type="submit" value="Upload Data" class="btn btn-success">
                                            
                                                <button name="delete" type="submit"  class="btn btn-danger" onclick="">
                                                    <i class="fa fa-trash-o"></i> Delete All Data Uji
                                                </button>
                                            </div>
                                            
                                            <div class="form-group">
                                                <button name="uji_akurasi" type="submit"  class="btn btn-default" onclick="">
                                                    <i class="fa fa-check"></i> Uji Akurasi
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
                                        } 
                                        else {
                                            ?>
                                            <strong>DATA UJI:</strong>
                                            <table class='table table-bordered table-striped  table-hover'>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Usia</th>
                                                    <th>Asal Sekolah</th>
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
                                        
                                        if(isset($_POST['uji_akurasi'])){
                                            //proses menghitung naive bayes
                                            //loop data uji nya
                                            $sql_hit = "SELECT * FROM data_uji ";
                                            $res = $db_object->db_query($sql_hit);
                                            $aa=1;
                                            while($row = $db_object->db_fetch_array($res)){
                                                echo "<center>";
                                                echo "<b>Data Uji ke-".$aa."</b>";
                                                echo "<br>"
                                                . "<strong>"."Jenis kelamin: "."</strong>".$row['jenis_kelamin']." - "
                                                        . "<strong>"."Usia: "."</strong>".$row['usia']." - "
                                                        . "<strong>"."Asal Sekolah: "."</strong>".$row['sekolah']." - "
                                                        . "<strong>"."Jawaban A: "."</strong>".$row['jawaban_a']." - "
                                                        . "<strong>"."Jawaban B: "."</strong>".$row['jawaban_b']." - "
                                                        . "<strong>"."Jawaban C: "."</strong>".$row['jawaban_c']." - "
                                                        . "<strong>"."Jawaban D: "."</strong>".$row['jawaban_d']
                                                        ;
                                                ProsesNaiveBayes($db_object, $row['id'], $row['jenis_kelamin'], $row['usia'], $row['sekolah'], 
                                                        $row['jawaban_a'], $row['jawaban_b'], $row['jawaban_c'], $row['jawaban_d']);
                                                $aa++;
                                                //echo "<br><br>";
                                            }
                                            
                                            //perhitungan akurasi
                                            $que = $db_object->db_query("SELECT * FROM data_uji");
                                            $jumlah_uji=$db_object->db_num_rows($que);
                                            //$TP=0; $FN=0; $TN=0; $FP=0; $kosong=0;
                                            $TA = $FB = $FC = $FD = 
                                            $FE = $TF = $FG = $FH = 
                                            $FI = $FJ = $TK = $FL = 
                                            $FM = $FN = $FO = $TP = 0;
                                            ?>
                                            <strong>Hasil:</strong>
                                            <table class='table table-bordered table-striped  table-hover'>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Usia</th>
                                                    <th>Asal Sekolah</th>
                                                    <th>Jawaban A</th>
                                                    <th>Jawaban B</th>
                                                    <th>Jawaban C</th>
                                                    <th>Jawaban D</th>
                                                    <th>Kelas Asli</th>
                                                    <th>Kelas Hasil</th>
                                                    <th></th>
                                                </tr>
                                            <?php
                                            $no = 1;
                                            while($row=$db_object->db_fetch_array($que)){
                                                    $asli=$row['kelas_asli'];
                                                    $prediksi=$row['kelas_hasil'];
                                                    if($row['kelas_asli']==$row['kelas_hasil']){
                                        $ketepatan="Benar";
                                                    }else{
                                                        $ketepatan="Salah";
                                                    }
                                                    
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
                                                    echo "<td>" . $row['kelas_hasil'] . "</td>";
                                                    echo "<td>" . $ketepatan . "</td>";
                                                    echo "</tr>";
                                                    $no++;
                                                    
                                                    if($asli=='Sanguin' & $prediksi=='Sanguin'){
                                                            $TA++;
                                                    }
                                                    else if($asli=='Sanguin' & $prediksi=='Koleris'){
                                                            $FB++;
                                                    }
                                                    else if($asli=='Sanguin' & $prediksi=='Melankolis'){
                                                            $FC++;
                                                    }
                                                    else if($asli=='Sanguin' & $prediksi=='Plegmatis'){
                                                            $FD++;
                                                    }
                                                    else if($asli=='Koleris' & $prediksi=='Sanguin'){
                                                            $FE++;
                                                    }
                                                    else if($asli=='Koleris' & $prediksi=='Koleris'){
                                                            $TF++;
                                                    }
                                                    else if($asli=='Koleris' & $prediksi=='Melankolis'){
                                                            $FG++;
                                                    }
                                                    else if($asli=='Koleris' & $prediksi=='Plegmatis'){
                                                            $FH++;
                                                    }
                                                    else if($asli=='Melankolis' & $prediksi=='Sanguin'){
                                                            $FI++;
                                                    }
                                                    else if($asli=='Melankolis' & $prediksi=='Koleris'){
                                                            $FJ++;
                                                    }
                                                    else if($asli=='Melankolis' & $prediksi=='Melankolis'){
                                                            $TK++;
                                                    }
                                                    else if($asli=='Melankolis' & $prediksi=='Plegmatis'){
                                                            $FL++;
                                                    }
                                                    else if($asli=='Plegmatis' & $prediksi=='Sanguin'){
                                                            $FM++;
                                                    }
                                                    else if($asli=='Plegmatis' & $prediksi=='Koleris'){
                                                            $FN++;
                                                    }
                                                    else if($asli=='Plegmatis' & $prediksi=='Melankolis'){
                                                            $FO++;
                                                    }
                                                    else if($asli=='Plegmatis' & $prediksi=='Plegmatis'){
                                                            $TP++;
                                                    }
                                                    else if($prediksi==''){
                                                            $kosong++;
                                                    }
                                            }
                                            ?>
                                            </table>
                                            <?php
                                            $tepat=($TA+$TF+$TK+$TP);
                                            $tidak_tepat=($FB+$FC+$FD+$FE+$FG+$FH+$FI+$FJ+$FL+$FM+$FN+$FO+$kosong);
                                            $akurasi=($tepat/$jumlah_uji)*100;
                                            $laju_error=($tidak_tepat/$jumlah_uji)*100;
                        //                        $sensitivitas=($TP/($TP+$FN))*100;
                        //                        $spesifisitas=($TN/($FP+$TN))*100;

                                            $akurasi = round($akurasi,2);	
                                            $laju_error = round($laju_error,2);
                                            $sensitivitas = round($sensitivitas,2);	
                                            $spesifisitas = round($spesifisitas,2);	


                                            echo "<br><br>";
                                            echo "<center><h4>";
                                            echo "Jumlah prediksi: $jumlah_uji<br>";
                                            echo "Jumlah tepat: $tepat<br>";
                                            echo "Jumlah tidak tepat: $tidak_tepat<br>";
                                            if($kosong!=0){ echo "Jumlah data yang prediksinya kosong: $kosong<br></h4>"; }
                                            echo "<h2>AKURASI = $akurasi %<br>";
                                            echo "LAJU ERROR = $laju_error %<br></h2>";
                                            /*
                                            echo "<h4>TP: $TP | TN: $TN | FP: $FP | FN: $FN<br></h4>";
                                            echo "<table>";
                                                echo "<tr>";
                                                    echo "<td>Sensitivitas</td> <td>=</td> <td>(TP / (TP + FN) ) x 100</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                    echo "<td>&nbsp;</td> <td>=</td> <td>($TP / ($TP + $FN) ) x 100</td>";
                                                echo "</tr>";
                                                $TP_plus_FN = $TP+$FN;
                                                echo "<tr>";
                                                    echo "<td>&nbsp;</td> <td>=</td> <td>($TP / ($TP_plus_FN) ) x 100</td>";
                                                echo "</tr>";
                                                $last = $TP/($TP+$FN);
                                                echo "<tr>";
                                                    echo "<td>&nbsp;</td> <td>=</td> <td>($last) x 100</td>";
                                                echo "</tr>";
                                            echo "</table>";

                                            echo "<h2>SENSITIVITAS = $sensitivitas %<br></h2>";
                                    //        $spesifisitas=($TN/($FP+$TN))*100;
                                            echo "<table>";
                                                echo "<tr>";
                                                    echo "<td>Spesifisitas</td> <td>=</td> <td>(TN / (FP + TN) ) x 100</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                    echo "<td>&nbsp;</td> <td>=</td> <td>($TN / ($FP + $TN) ) x 100</td>";
                                                echo "</tr>";
                                                $FP_plus_TN = $FP+$TN;
                                                echo "<tr>";
                                                    echo "<td>&nbsp;</td> <td>=</td> <td>($TN / ($FP_plus_TN) ) x 100</td>";
                                                echo "</tr>";
                                                $last1 = $TN/($FP+$TN);
                                                echo "<tr>";
                                                    echo "<td>&nbsp;</td> <td>=</td> <td>($last1) x 100</td>";
                                                echo "</tr>";
                                            echo "</table>";
                                            echo "<h2>SPESIFISITAS = $spesifisitas %<br>";
                                            echo "</h2>";
                                            echo "</center>";
                                            * 
                                            */
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