<?php 
$menu = '';
if(isset($_GET['menu'])){
    $menu = $_GET['menu'];
}
?>

<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
    <!--left-fixed -navigation-->
    <aside class="sidebar-left">
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><span class="fa fa-area-chart"></span>Tes Kepribadian</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="sidebar-menu">
                    <li class="header" style="font-size: 13px;">Menu</li>
                    <li class="treeview">

                    <li><a <?php echo ($menu==''||$menu=='home')?"class='menu-top-active'":""; ?> 
                        href="index.php"><i class="fa fa-home"></i>Home</a></li>
                        <?php
                        if (empty($_SESSION['kepribadian_nbc_c4.5_id']) == false){
                            if($_SESSION['kepribadian_nbc_c4.5_level'] == 2){
                                ?>
                                <li class="header" style="font-size: 13px;">Tes Kepribadian</li>
                                <li><a <?php echo ($menu=='klasifikasi')?"class=''":""; ?>
                                        href="index.php?menu=klasifikasi"><i class="fa fa-angle-right text-red"></i>Mulai Tes</a></li>
                                
                                <!-- <li><a <?php echo ($menu=='klasifikasi_c45')?"class=''":""; ?>
                                        href="index.php?menu=klasifikasi_c45"><i class="fa fa-angle-right text-red"></i>Algoritma C4.5</a></li> -->
                                <?php
                            }
                            else{
                                ?>
                                <li><a <?php echo ($menu=='data_latih')?"class=''":""; ?>
                                        href="index.php?menu=data_latih"><i class="fa fa-edit"></i>Data Latih</a></li>
                                <li><a <?php echo ($menu=='data_soal')?"class=''":""; ?>
                                        href="index.php?menu=data_soal"><i class="fa fa-book"></i>Data Soal</a></li>
                                
                                <li class="header" style="font-size: 13px;">Naive Bayes Classifier</li>
                                <li><a <?php echo ($menu=='uji_akurasi')?"class=''":""; ?> 
                                        href="index.php?menu=uji_akurasi"><i class="fa fa-angle-right text-red"></i> <span>Proses Perhitungan</span></a></li>
                                
                                
                                <li class="header" style="font-size: 13px;">Algoritma C4.5</li>
                                <li><a <?php echo ($menu=='mining')?"class=''":""; ?> 
                                        href="index.php?menu=mining"><i class="fa fa-angle-right text-red"></i> <span>Proses Perhitungan</span></a></li>
                                <li><a <?php echo ($menu=='pohon_keputusan')?"class=''":""; ?> 
                                        href="index.php?menu=pohon_keputusan"><i class="fa fa-angle-right text-red"></i> <span>Uji Akurasi</span></a></li>
                                
                                <li class="header" style="font-size: 13px;">Laporan Hasil Klasifikasi</li>
                                        <li><a <?php echo ($menu=='laporan_hasil')?"class=''":""; ?> 
                                        href="index.php?menu=laporan_hasil"><i class="fa fa-angle-right text-red"></i>Hasil Tes Kepribadian</a></li>
                                <!-- <li><a <?php echo ($menu=='hasil_klasifikasi')?"class=''":""; ?> 
                                        href="index.php?menu=hasil_klasifikasi"><i class="fa fa-angle-right text-red"></i>C4.5</a></li> -->
                                
                                <li><a <?php echo ($menu=='data_siswa')?"class=''":""; ?> 
                                        href="index.php?menu=data_siswa"><i class="fa fa-clipboard"></i>Input Data Siswa</a></li>
                                <?php
                            }
                            ?>
                            <li><a href="logout.php"><i class="fa fa-arrow-circle-right"></i>Logout</a></li>
                            <?php
                        }
                        ?>
                </ul>
            </div>
        </nav>
    </aside>
</div>