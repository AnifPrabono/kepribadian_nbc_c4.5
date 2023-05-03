

<?php
//session_start();
if (!isset($_SESSION['kepribadian_nbc_c4.5_id'])) {
    header("location:index.php?menu=forbidden");
}

include_once "database.php";
include_once "fungsi.php";
include_once "fungsi_proses.php";
include_once "excel_reader2.php";
?>


<!DOCTYPE HTML>
<html>
    <head>
        <title>Home</title>
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
                        <h2 class="title1" style="text-align:center" >Tes Karakteristik Kepribadian Siswa</h2>
                        
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
                            if($idSiswa <=0){
                                $success = false;
                                $pesan_gagal = "Anda bukan siswa";
                            }
                            
                            if(sudah_klasifikasi($db_object, $idSiswa)){
                                $success = false;
                                $lihat_hasil = true;
                                $pesan_gagal = "Anda sudah melakukan klasifikasi";
                            }
                            
                            if($success){
                                    $val_in = $di_jawab_a = $di_jawab_b = $di_jawab_c = $di_jawab_d = array();
                                    foreach ($_POST['soal'] as $key => $value) {
                                        if(empty($value)){
                                            $success = false;
                                            $pesan_gagal = "Ada yang belum diisi";
                                            break;
                                        }
                                        //key = id_soal, value=jawaban A/B/C/D
                                        $val_in[] = "(".$_SESSION['kepribadian_nbc_c4.5_id'].",".$idSiswa.
                                                ",".$key.",'".$value."')";
                                        if($value=='A'){
                                            $di_jawab_a[] = $key;
                                        }
                                        if($value=='B'){
                                            $di_jawab_b[] = $key;
                                        }
                                        if($value=='C'){
                                            $di_jawab_c[] = $key;
                                        }
                                        if($value=='D'){
                                            $di_jawab_d[] = $key;
                                        }

                                    }
                                    //insert ke jawaban_kuisioner
                                    if($idSiswa > 0){
                                        $value_sql_to_in = implode(",", $val_in);
                                        $sql_in_jawaban = "INSERT INTO jawaban_kuisioner
                                                            (id_user, id_siswa, id_soal, jawaban)
                                                            VALUES ".$value_sql_to_in;
                                        $db_object->db_query($sql_in_jawaban);

                                        //hitung naive bayes
                                        $siswa = get_data_siswa($db_object, $idSiswa);
                                        $jawaban_a = count($di_jawab_a);
                                        $jawaban_b = count($di_jawab_b);
                                        $jawaban_c = count($di_jawab_c);
                                        $jawaban_d = count($di_jawab_d);
                                        $hasil = ProsesNaiveBayes($db_object, 0, $siswa['jenis_kelamin'], $siswa['usia'], $siswa['sekolah'], 
                                            $jawaban_a, $jawaban_b, $jawaban_c, $jawaban_d, false);

                                        //simpan ke table hasil
                                        $sql_in_hasil = "INSERT INTO data_hasil_klasifikasi
                                                    (id_siswa, jenis_kelamin, usia, sekolah, jawaban_a, jawaban_b, jawaban_c, jawaban_d, 
                                                    kelas_hasil, nilai_sanguin, nilai_koleris, nilai_melankolis, nilai_plegmatis)
                                                    VALUES
                                                    ($idSiswa, '".$siswa['jenis_kelamin']."', ".$siswa['usia'].", '".$siswa['sekolah']."', "
                                                .$jawaban_a. ", ".$jawaban_b.", ".$jawaban_c.", ".$jawaban_d.", "
                                                . "'".$hasil[0]."', '".$hasil[1]."', '".$hasil[2]."', '".$hasil[3]."', '".$hasil[4]."')";
                                        $db_object->db_query($sql_in_hasil);
                                        
                                        //simpan juga ke data uji
                                        $sql_data_uji = "INSERT INTO data_uji"
                                                . "(nama, jenis_kelamin, usia, sekolah, jawaban_a, jawaban_b, jawaban_c, jawaban_d, kelas_asli) "
                                                . " VALUES "
                                                . " ('".$siswa['nama_siswa']."' , '".$siswa['jenis_kelamin']."' , '".$siswa['usia']."' "
                                                . ", '".$siswa['sekolah']."' , '".$jawaban_a."' , '".$jawaban_b."' "
                                                . ", '".$jawaban_c."' , '".$jawaban_d."' , '".$hasil[0]."' ) ";
                                        $db_object->db_query($sql_data_uji);
                                        //nama, jenis_kelamin, usia, sekolah, jawaban_a, jawaban_b, jawaban_c, jawaban_d, kelas_asli
                                    }
                                    
                            }
                            

                            if ($success) {

                                echo "<h3><center>Karakteristik kepribadian anda adalah </h3> <br> <h1 style='color:#F2B33F;font-family:Lucida Handwriting'><center>".$hasil[0];                         
                                
                                
                                if($hasil[0] == 'Sanguin') {
                                    $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                1. Selalu ingin diperhatikan. <br>
                                                2. Mempunyai Watak dasar Ekstrovet, pembicara, optimis. <br>
                                                3. Mempunyai personality yang periang, lincah, dan sopan. <br> <br>
                                                CARA BERKOMUNIKASI :<br>
                                                1. Berikan penghargaan yang benar-benar tulus. <br>
                                                2. Lebih banyak mendengar. <br>
                                                3. Jangan mengkritik secara langsung. <br>
                                                4. Jangan bicara hal-hal yang detail. <br>
                                                5. Bertanyalah hal-hal yang dia suka. ";
                                }elseif($hasil[0] == 'Koleris'){
                                    $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                1. Ingin selalu dihargai. <br>
                                                2. Mempunyai Watak dasar ekstrovet, pelaku, optimis. <br>
                                                3. Mempunyai personality suka petualangan, persuasive, percaya diri. <br> <br>
                                                CARA BERKOMUNIKASI :<br>
                                                1. Beri penghargaan tulus atas hasil kerjanya. <br>
                                                2. Berbicara langsung pada persoalan. <br>
                                                3. Mintalah pandangan atau pendapatnya. <br>
                                                4. Usahakanlah keputusan yang diambil seolah-olah keputusan dia. <br>
                                                5. Jangan menyalahkan secara langsung. ";
                                }elseif ($hasil[0] == 'Melankolis' ){
                                    $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                1. Suka terhadap kesempurnaan. <br>
                                                2. Mempunyai Watak dasar Introvet, pemikir, pesimis. <br>
                                                3. Mempunyai personality setia, penuh pemikiran, tekun. <br> <br>
                                                CARA BERKOMUNIKASI :<br>
                                                1. Bersikap sopan. <br>
                                                2. Berbicara sistematis. <br>
                                                3. Penjelasan terperinci disertai fakta atau bukti. <br>
                                                4. Jangan didesak untuk mengambil keputusan. <br>
                                                5. Siapkan pembagian alternatif. ";
                                }elseif($hasil[0] == 'Plegmatis' ){
                                    $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                1. Suka terhadap kedamaian. <br>
                                                2. Mempunyai Watak dasar introvert, pengamat, pesimis. <br>
                                                3. Mempunyai personality bersikap tenang, setia, tekun. <br> <br>
                                                CARA BERKOMUNIKASI :<br>
                                                1. Berbicaralah dengan cara yang bersahabat. <br>
                                                2. Penjelasan maslah sederhana dan jangan telalu rumit. <br>
                                                3. Jangan berbicara terlalu agresif. <br>
                                                4. Jangan didesak atau diburu-buru. <br>
                                                5. Bisa memberi keyakinan. ";
                                }
                                echo "<br>";
                                echo "<br>";
                                echo "<h3 style='text-align: left;color:black;font-family:Times New Roman'>KARAKTER :</h3>".$message;
                                echo "<br>";
                                
                                // echo "Probabilitas:";
                                // echo "<br>";
                                // echo "Nilai Sanguin:".$hasil[1];
                                // echo "<br>";
                                // echo "Nilai Koleris:".$hasil[2];
                                // echo "<br>";
                                // echo "Nilai Melankolis:".$hasil[3];
                                // echo "<br>";
                                // echo "Nilai Plegmatis:".$hasil[4];
                            } 
                            else {
                                display_error($pesan_gagal);
                                if($lihat_hasil){
                                    $hasilSiswa = get_hasil_klasifikasi($db_object, $idSiswa);
                                    $hasilSiswa = get_hasil_klasifikasi($db_object, $idSiswa);
                
                                    echo "<h3><center>Karakteristik kepribadian anda adalah </h3> <br> <h1 style='color:#F2B33F;font-family:Lucida Handwriting'><center>".$hasilSiswa['kelas_hasil'];
                                   
                                    if($hasilSiswa['kelas_hasil'] == 'Sanguin') {
                                        $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                    1. Selalu ingin diperhatikan. <br>
                                                    2. Mempunyai Watak dasar Ekstrovet, pembicara, optimis. <br>
                                                    3. Mempunyai personality yang periang, lincah, dan sopan. <br> <br>
                                                    CARA BERKOMUNIKASI :<br>
                                                    1. Berikan penghargaan yang benar-benar tulus. <br>
                                                    2. Lebih banyak mendengar. <br>
                                                    3. Jangan mengkritik secara langsung. <br>
                                                    4. Jangan bicara hal-hal yang detail. <br>
                                                    5. Bertanyalah hal-hal yang dia suka. ";
                                    }elseif($hasilSiswa['kelas_hasil'] == 'Koleris'){
                                        $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                    1. Ingin selalu dihargai. <br>
                                                    2. Mempunyai Watak dasar ekstrovet, pelaku, optimis. <br>
                                                    3. Mempunyai personality suka petualangan, persuasive, percaya diri. <br> <br>
                                                    CARA BERKOMUNIKASI :<br>
                                                    1. Beri penghargaan tulus atas hasil kerjanya. <br>
                                                    2. Berbicara langsung pada persoalan. <br>
                                                    3. Mintalah pandangan atau pendapatnya. <br>
                                                    4. Usahakanlah keputusan yang diambil seolah-olah keputusan dia. <br>
                                                    5. Jangan menyalahkan secara langsung. ";
                                    }elseif ($hasilSiswa['kelas_hasil'] == 'Melankolis' ){
                                        $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                    1. Suka terhadap kesempurnaan. <br>
                                                    2. Mempunyai Watak dasar Introvet, pemikir, pesimis. <br>
                                                    3. Mempunyai personality setia, penuh pemikiran, tekun. <br> <br>
                                                    CARA BERKOMUNIKASI :<br>
                                                    1. Bersikap sopan. <br>
                                                    2. Berbicara sistematis. <br>
                                                    3. Penjelasan terperinci disertai fakta atau bukti. <br>
                                                    4. Jangan didesak untuk mengambil keputusan. <br>
                                                    5. Siapkan pembagian alternatif. ";
                                    }elseif($hasilSiswa['kelas_hasil'] == 'Plegmatis' ){
                                        $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                    1. Suka terhadap kedamaian. <br>
                                                    2. Mempunyai Watak dasar introvert, pengamat, pesimis. <br>
                                                    3. Mempunyai personality bersikap tenang, setia, tekun. <br> <br>
                                                    CARA BERKOMUNIKASI :<br>
                                                    1. Berbicaralah dengan cara yang bersahabat. <br>
                                                    2. Penjelasan maslah sederhana dan jangan telalu rumit. <br>
                                                    3. Jangan berbicara terlalu agresif. <br>
                                                    4. Jangan didesak atau diburu-buru. <br>
                                                    5. Bisa memberi keyakinan. ";
                                    }
                                    echo "<br>";
                                    echo "<br>";
                                    echo "<h3 style='text-align: left;color:black;font-family:Times New Roman'>KARAKTER :</h3>".$message;
                                    echo "<br>";
                                   
                                   
                                    // echo "Klasifikasi karakteristik kepribadian Anda: ".$hasilSiswa['kelas_hasil'];
                                    // echo "<br>";
                                    // echo "<br>";
                                    // echo "Probabilitas:";
                                    // echo "<br>";
                                    // echo "Nilai Sanguin:".$hasilSiswa['nilai_sanguin'];
                                    // echo "<br>";
                                    // echo "Nilai Koleris:".$hasilSiswa['nilai_koleris'];
                                    // echo "<br>";
                                    // echo "Nilai Melankolis:".$hasilSiswa['nilai_melankolis'];
                                    // echo "<br>";
                                    // echo "Nilai Plegmatis:".$hasilSiswa['nilai_plegmatis'];
                                }
                            }
                        }
                
                                        
                        if (!isset($_POST['submit'])) {
                            if(sudah_klasifikasi($db_object, $_SESSION['kepribadian_nbc_c4.5_id_siswa'])){
                                $hasilSiswa = get_hasil_klasifikasi($db_object, $_SESSION['kepribadian_nbc_c4.5_id_siswa']);
                                // echo "Klasifikasi karakteristik kepribadian Anda: ".$hasilSiswa['kelas_hasil'];
                                //     echo "<br>";
                                //     echo "Probabilitas:";
                                //     echo "<br>";
                                //     echo "Nilai Sanguin:".$hasilSiswa['nilai_sanguin'];
                                //     echo "<br>";
                                //     echo "Nilai Koleris:".$hasilSiswa['nilai_koleris'];
                                //     echo "<br>";
                                //     echo "Nilai Melankolis:".$hasilSiswa['nilai_melankolis'];
                                //     echo "<br>";
                                //     echo "Nilai Plegmatis:".$hasilSiswa['nilai_plegmatis'];
                                    echo "<h3><center>Anda sudah melakukan tes kepribadian sebelumnya <br>
                                    Karakteristik kepribadian anda adalah </h3> <br> <h1 style='color:#F2B33F;font-family:Lucida Handwriting'><center>".$hasilSiswa['kelas_hasil'];
                                   
                                    if($hasilSiswa['kelas_hasil'] == 'Sanguin') {
                                        $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                    1. Selalu ingin diperhatikan. <br>
                                                    2. Mempunyai kelemahan : tidak disiplin, terbiasa lupa dengan janjinya dan mudah bosan. <br>
                                                    3. Mudah bergaul, bnyak ide dan rasa humor. <br>
                                                    4. Mempunyai Watak dasar Ekstrovet, pembicara, optimis. <br>
                                                    5. Mempunyai personality yang periang, lincah, dan sopan. <br> <br>
                                                    CARA BERKOMUNIKASI :<br>
                                                    1. Berikan penghargaan yang benar-benar tulus. <br>
                                                    2. Ciptakan suasana yang hangat dan bersahabat. <br>
                                                    3. Buat proyek jangka pendek dengan hadiah (reward). <br>
                                                    4. Untuk mengajaknya beri tahu apa yang telah dilakukan oleh orang lain. <br>
                                                    5. Lebih banyak mendengar. <br>
                                                    6. Jangan mengkritik secara langsung. <br>
                                                    7. Jangan bicara hal-hal yang detail. <br>
                                                    8. Bertanyalah hal-hal yang dia suka. ";
                                    }elseif($hasilSiswa['kelas_hasil'] == 'Koleris'){
                                        $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                    1. Ingin selalu dihargai. <br> 
                                                    2. Melakukan segala sesuatunya dengan cepat, spesifik dan to the point. <br>
                                                    3. Memiliki kelemahan : mudah marah, diktator, keras kepala. <br>
                                                    4. Mempunyai Watak dasar ekstrovet, optimis, kepemimpinan, tegas, energi besar dan pantang menyerah. <br>
                                                    5. Mempunyai personality suka petualangan, persuasive. <br> <br>
                                                    CARA BERKOMUNIKASI :<br>
                                                    1. Beri penghargaan tulus atas hasil kerjanya. <br>
                                                    2. Cukup berbicara singkat dan spesifik. <br>
                                                    3. Bersikap logis atau masuk akal (rasional). <br>
                                                    4. Stimulus dengan tantangan (karena tipe koleris suka tantangan). <br>
                                                    5. Berbicara langsung pada persoalan. <br>
                                                    6. Mintalah pandangan atau pendapatnya. <br>
                                                    7. Usahakanlah keputusan yang diambil seolah-olah keputusan dia. <br>
                                                    8. Jangan menyalahkan secara langsung. ";
                                    }elseif ($hasilSiswa['kelas_hasil'] == 'Melankolis' ){
                                        $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                    1. Suka terhadap kesempurnaan. <br>
                                                    2. Tidak suka dikritik, perubahan mendadak dan kesalahan. <br>
                                                    3. Memiliki kelemahan : cenderung negatif, kurang fleksibel, tertutup, memikirkan hal yang tidak perlu. <br>
                                                    4. Mempunyai Watak dasar Introvet, pemikir, pesimis. <br>
                                                    5. Mempunyai personality setia, penuh pemikiran, tekun. <br> <br>
                                                    CARA BERKOMUNIKASI :<br>
                                                    1. Bersikap sopan. <br>
                                                    2. Ungkapkan sisi pro dan kontra secara seimbang. <br>
                                                    3. Hindari kejutan dan tidak suka konflik. <br>
                                                    4. Berlaku sabar dan berbicara hal yang bersifat detail. <br>
                                                    5. Berbicara sistematis. <br>
                                                    6. Penjelasan terperinci disertai fakta atau bukti. <br>
                                                    7. Jangan didesak untuk mengambil keputusan. <br>
                                                    8. Antisipasi pertanyaan mereka dan berikan jawaban yang berbobot. ";
                                    }elseif($hasilSiswa['kelas_hasil'] == 'Plegmatis' ){
                                        $message = "<h3 style='text-align:left;color:black;font-family:Times New Roman'>
                                                    1. Suka terhadap kedamaian. <br>
                                                    2. Memiliki kelemahan : kurang inisiatif, mudah, dimanipulasi, malas membuat keputusan, pasif. <br>
                                                    3. Tidak suka terhadap orang yang kasar, kejutan, didesak dan konflik. <br>
                                                    4. Mempunyai Watak dasar introvert, pengamat, pesimis. <br>
                                                    5. Mempunyai personality bersikap tenang, setia, tekun. <br> <br>
                                                    CARA BERKOMUNIKASI :<br>
                                                    1. Berbicaralah dengan cara yang bersahabat. <br>
                                                    2. Berikan waktu untuk menyesuaikan diri dengan perubahan. <br>
                                                    3. Tunjukkan kesabaran. <br>
                                                    4. Penjelasan masalah sederhana dan jangan telalu rumit. <br>
                                                    5. Jangan berbicara terlalu agresif. <br>
                                                    6. Jangan didesak atau diburu-buru. <br>
                                                    7. Bisa memberi keyakinan. ";
                                    }
                                    echo "<br>";
                                    echo "<br>";
                                    echo "<h3 style='text-align: left;color:black;font-family:Times New Roman'>KARAKTER :</h3>".$message;
                                    echo "<br>";

                            }
                            else{

                                if($jumlah <= 0){
                                    echo "Data Soal masih belum ada...";
                                }
                                else{
                        ?>
                        <!--UPLOAD EXCEL FORM-->
                        <form method="post" action="">
                        <h4 style="font-family:Times New Roman; margin-bottom:10px">Pilihlah pernyataan yang sesuai dengan apa yang ada dalam diri anda pada setiap nomornya!</h4>
                            <?php
                            while($row = $db_object->db_fetch_array($query)){
                            ?>
                            <label>No. <?php echo $row['id']; ?>  </label>
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