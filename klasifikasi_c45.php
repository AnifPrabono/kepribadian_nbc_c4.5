<?php
//session_start();
if (!isset($_SESSION['kepribadian_nbc_c4.5_id'])) {
    header("location:index.php?menu=forbidden");
}

include_once "database.php";
include_once "fungsi.php";
include_once "proses_mining.php";
//include_once "fungsi_proses.php";
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Klasifikasi</title>
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
        <div id="page-wrapper">
            <div class="main-page">
                <div class="forms">
                    <h2 class="title1" style="text-align:center">Tes Kepribadian Menggunakan Algoritma C4.5</h2>
            <div class="content-wrapper">
            <div class="row">
            <div class="col-md-12">
            <?php
            //object database class
            $db_object = new database();
            $sql = "SELECT * FROM data_soal";
            $query = $db_object->db_query($sql);
            $jumlah = $db_object->db_num_rows($query);

            $pesan_error = $pesan_success = "";
            if (isset($_GET['pesan_error'])) {
                $pesan_error = $_GET['pesan_error'];
            }
            if (isset($_GET['pesan_success'])) {
                $pesan_success = $_GET['pesan_success'];
            }

            if (isset($_POST['submit'])) {
                $success = true;
                $lihat_hasil = false;
                $pesan_gagal = $pesan_sukses = "";
                $idSiswa = $_SESSION['kepribadian_nbc_c4.5_id_siswa'];
                if ($idSiswa <= 0) {
                    $success = false;
                    $pesan_gagal = "Anda bukan siswa";
                }

                if (sudah_klasifikasi_c45($db_object, $idSiswa)) {
                    $success = false;
                    $lihat_hasil = true;
                    $pesan_gagal = "Anda sudah melakukan klasifikasi";
                }

                if ($success) {
                    $val_in = $di_jawab_a = $di_jawab_b = $di_jawab_c = $di_jawab_d = array();
                    foreach ($_POST['soal'] as $key => $value) {
                        if (empty($value)) {
                            $success = false;
                            $pesan_gagal = "Ada yang belum diisi";
                            break;
                        }
                        //key = id_soal, value=jawaban A/B/C/D
                        $val_in[] = "(" . $_SESSION['kepribadian_nbc_c4.5_id'] . "," . $idSiswa .
                                "," . $key . ",'" . $value . "')";
                        if ($value == 'A') {
                            $di_jawab_a[] = $key;
                        }
                        if ($value == 'B') {
                            $di_jawab_b[] = $key;
                        }
                        if ($value == 'C') {
                            $di_jawab_c[] = $key;
                        }
                        if ($value == 'D') {
                            $di_jawab_d[] = $key;
                        }
                    }
                    //insert ke jawaban_kuisioner_c45
                    if ($idSiswa > 0) {
                        $value_sql_to_in = implode(",", $val_in);
                        $sql_in_jawaban = "INSERT INTO jawaban_kuisioner_c45
                                            (id_user, id_siswa, id_soal, jawaban)
                                            VALUES " . $value_sql_to_in;
                        $db_object->db_query($sql_in_jawaban);

                        //hitung naive bayes
                        $siswa = get_data_siswa($db_object, $idSiswa);
                        $jawaban_a = count($di_jawab_a);
                        $jawaban_b = count($di_jawab_b);
                        $jawaban_c = count($di_jawab_c);
                        $jawaban_d = count($di_jawab_d);

                        $hasil = klasifikasi($db_object, $siswa['jenis_kelamin'], $siswa['usia'], $siswa['sekolah'], 
                        $jawaban_a, $jawaban_b, $jawaban_c, $jawaban_d);

                        //simpan ke table hasil
                        $sql_in_hasil = "INSERT INTO data_hasil_klasifikasi_c45
                                    (id_siswa, jenis_kelamin, usia, sekolah, jawaban_a, jawaban_b, jawaban_c, jawaban_d, 
                                    kelas_hasil, id_rule)
                                    VALUES
                                    ($idSiswa, '" . $siswa['jenis_kelamin'] . "', " . $siswa['usia'] . ", '" . $siswa['sekolah'] . "', "
                                . $jawaban_a . ", " . $jawaban_b . ", " . $jawaban_c . ", " . $jawaban_d . ", "
                                . "'" . $hasil['keputusan'] . "', '" . $hasil['id_rule'] . "')";
                        $db_object->db_query($sql_in_hasil);
                        
                        //simpan ke data uji
                        $sql_data_uji = "INSERT INTO data_uji "
                                . "(nama, jenis_kelamin, usia, sekolah, jawaban_a, jawaban_b, jawaban_c, jawaban_d, kelas_asli) "
                                . " VALUES "
                                . "('".$siswa['nama_siswa']."', '".$siswa['jenis_kelamin']."', '".$siswa['usia']."'"
                                . ", '".$siswa['sekolah']."', '".$jawaban_a."', '".$jawaban_b."'"
                                . ", '".$jawaban_c."', '".$jawaban_d."', '".$hasil['keputusan']."')";
                        $db_object->db_query($sql_data_uji);
                    }
                }


                if ($success) {
                    echo "<br>";
                    echo "<br>";
                    if($hasil['keputusan'] == 'Koleris'){
                        $message = "keterangan korelis silahakan tulis disini";
                    }elseif ($hasil['keputusan'] == 'Sanguin') {
                        $message = "keterangan Sanguin silahakan tulis disini";
                    }elseif($hasil['keputusan'] == 'Plegmatis' ){
                        $message = "keterangan Plagmatis silahakan tulis disini";
                    }elseif ($hasil['keputusan'] == 'Melankolis' ){
                        $message = "keterangan Melankolis silahakan tulis disini";
                    }
                    echo "<br>";
                    echo "<center>"
                    . "<h3 class='typoh2'>"
                            . "Klasifikasi karakteristik kepribadian Anda: " 
                    . "</h3>"
                            . "<h2 class='typoh2'>"
                            . $hasil['keputusan']
                            . "</h2>"
                    . "</center>";
                    
                    
                    echo "<h3 style='color:red'>Pesan sasori: <br>".$message."</h3>";
                } else {
                    display_error($pesan_gagal);
                    if ($lihat_hasil) {
                        $hasilSiswa = get_hasil_klasifikasi_c45($db_object, $idSiswa);
                        //echo "<center><h3 class='typoh2'>Klasifikasi karakteristik kepribadian Anda: " . $hasilSiswa['kelas_hasil']."</h3></center>";
                        echo "<br>";
                        echo "<br>";
                        echo "<br>";
                        echo "<center>"
                        . "<h3 class='typoh2'>"
                                . "Klasifikasi karakteristik kepribadian Anda: " 
                        . "</h3>"
                                . "<h2 class='typoh2'>"
                                .$hasilSiswa['kelas_hasil']
                                . "</h2>"
                        . "</center>";
                    }
                }
            }


//                if (!empty($pesan_error)) {
//                    display_error($pesan_error);
//                }
//                if (!empty($pesan_success)) {
//                    display_success($pesan_success);
//                }

            if (!isset($_POST['submit'])) {
                if (sudah_klasifikasi_c45($db_object, $_SESSION['kepribadian_nbc_c4.5_id_siswa'])) {
                    $hasilSiswa = get_hasil_klasifikasi_c45($db_object, $_SESSION['kepribadian_nbc_c4.5_id_siswa']);
                    echo "<br>";
                    echo "<br>";
                    if($hasilSiswa['kelas_hasil'] == 'Koleris'){
                        $message = "keterangan korelis silahakan tulis disini";
                    }elseif ($hasilSiswa['kelas_hasil'] == 'Sanguin') {
                        $message = "keterangan Sanguin silahakan tulis disini";
                    }elseif($hasilSiswa['kelas_hasil'] == 'Plegmatis' ){
                        $message = "keterangan Plagmatis silahakan tulis disini";
                    }elseif ($hasilSiswa['kelas_hasil'] == 'Melankolis' ){
                        $message = "keterangan Melankolis silahakan tulis disini";
                    }
                    echo "<br>";
                    echo "<center>"
                    . "<h3 class='typoh2'>"
                            . "Anda sudah melakukan klasifikasi sebelumnya."
                            . "<br>"
                            . "<br>"
                            . "Klasifikasi karakteristik kepribadian Anda: " 
                    . "</h3>"
                            . "<h2 class='typoh2'>"
                            .$hasilSiswa['kelas_hasil']
                            . "</h2>"
                    . "</center>";
                    //echo "<br>";
                    echo "<h3 style='color:red'>Pesan sasori: <br>".$message."</h3>";
                } 
                else {

                    if($jumlah <= 0){
                        echo "<br>";
                        echo "<br>";
                        echo "<br>";
                        echo "<center>"
                        . "<h3 class='typoh2'>"
                                . "Soal Kuisioner belum ada"
                        . "</h3>"
                        . "</center>";
                    }
                    else{
                    ?>
                    <!--UPLOAD EXCEL FORM-->
                    <form method="post" action="">
                    <?php
                    while ($row = $db_object->db_fetch_array($query)) {
                        ?>
                            <label>No. <?php echo $row['id']; ?></label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="soal[<?php echo $row['id']; ?>]" value="A" required=""/>
                                    <?php echo $row['pilihan_a']; ?>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="soal[<?php echo $row['id']; ?>]" value="B" required=""/>
                                    <?php echo $row['pilihan_b']; ?>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="soal[<?php echo $row['id']; ?>]" value="C" required=""/>
                                    <?php echo $row['pilihan_c']; ?>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="soal[<?php echo $row['id']; ?>]" value="D" required=""/>
                                    <?php echo $row['pilihan_d']; ?>
                                </label>
                            </div>
                        <?php
                    }
                    ?>

                        <div class="form-group">
                            <input name="submit" type="submit" value="Submit" class="btn btn-success">
                        </div>
                    </form>
                        <?php
                    }
                    }
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