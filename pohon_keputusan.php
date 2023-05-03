<!DOCTYPE HTML>
<html>
    <head>
        <title>Pohon Keputusan</title>
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
                        <h2 class="title1">Aturan IF THEN</h2>

                      
<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
include_once "database.php";
include_once "fungsi.php";
//object database class
$db_object = new database();
?>

<?php
if(isset($_GET['act'])){
    $action=$_GET['act'];
    $id=$_GET['id'];
    if($action=='delete'){
        $db_object->db_query("TRUNCATE t_keputusan");
        header('location:index.php?menu=pohon_keputusan');
    }
}

$query=$db_object->db_query("select * from t_keputusan order by(id)");
$jumlah=$db_object->db_num_rows($query);
//jika pohon keputusan kosong
if($jumlah==0){
    echo "<center><h3> Pohon keputusan belum terbentuk...</h3></center>";
}
else{
    //hanya kaprodi yang bisa menghapus pohon keputusan dan menguji akurasi
    if($_SESSION['kepribadian_nbc_c4.5_level']==1){
?>
        <p>
            <a href="?menu=pohon_keputusan&act=delete" class="btn btn-danger" onClick="return confirm('Anda yakin akan hapus pohon keputusan?')">
                Hapus Aturan Yang Terbentuk
            </a>
            <!--<a href="?menu=pohon_tree" >Lihat Pohon Keputusan</a> |-->
            <a href="?menu=uji_rule" class="btn btn-default">Uji Rule</a>
        </p>
    <?php
    }
    echo "Jumlah rule : ".$jumlah."<br>";
    ?>
        <table class='table table-bordered table-striped  table-hover'>
            <tr align='center'>
                <th>Id</th><th>Aturan</th>
            </tr>
            <?php
                $warna1 = '#ffc';
                $warna2 = '#eea';
                $warna  = $warna1;
                $no=1;
                while($row=$db_object->db_fetch_array($query)){
                ?>
                    <tr>
                        <td align='center'><?php echo $row['id'];?></td>
                        <td><?php
                                echo "IF ";
                                if($row['parent']!=''){
                                        echo $row['parent']." AND ";
                                }
                                echo $row['akar']." THEN Label = ".$row['keputusan'];?>
                        </td>
                    </tr>
                <?php
                    $no++;
                }
            ?>
        </table>
<?php
}


/*
//select id dari pohon keputusan
$que_sql = $db_object->db_query("SELECT id FROM t_keputusan");
$id = array();
$l = 0;
while ($bar_row = $db_object->db_fetch_array($que_sql)) {
    $id[$l] = $bar_row[0];
    $l++;
}

$query = $db_object->db_query("SELECT * FROM t_keputusan ORDER BY(id)");
$temp_rule = array();
$temp_rule[0] = '';
$ll = 0; //variabel untuk iterasi id pohon keputusan
while ($bar = $db_object->db_fetch_array($query)) {
    //menampung rule
    if ($bar[1] != '') {
        $rule = $bar[1] . " AND " . $bar[2];
    } else {
        $rule = $bar[2];
    }

    $rule = str_replace("OR", "/", $rule);
    //explode rule
    $exRule = explode(" AND ", $rule);
    $jml_ExRule = count($exRule);
    $jml_temp = count($temp_rule);

    $i = 0;
    while ($i < $jml_ExRule) {
        if ($temp_rule[$i] == $exRule[$i]) {
            $temp_rule[$i] = $exRule[$i];
            $exRule[$i] = "---- ";
        } else {
            $temp_rule[$i] = $exRule[$i];
        }

        if ($i == ($jml_ExRule - 1)) {
            $t = $i;
            while ($t < $jml_temp) {
                $temp_rule[$t] = "";
                $t++;
            }
        }

        //jika terakhir tambah cetak keputusan
        if ($i == ($jml_ExRule - 1)) {
            $strip = '';
            for ($x = 1; $x <= $i; $x++) {
                $strip = $strip . "---- ";
            }
            $sql_que = $db_object->db_query("SELECT keputusan FROM t_keputusan WHERE id=$id[$ll]");
            $row_bar = $db_object->db_fetch_array($sql_que);
            if ($exRule[$i - 1] == "---- ") {
                echo "<font color='#000'><b>" . $exRule[$i] . "</b></font> <i>Maka donor darah = </i><strong>" . $row_bar[0] . " (" . $id[$ll] . ")</strong>";
            } else if ($exRule[$i - 1] != "---- ") {
                echo "<br>" . $strip . "<font color='#000'><b>" . $exRule[$i] . "</b></font> <i>Maka donor darah = </i><strong>" . $row_bar[0] . "  (" . $id[$ll] . ")</strong>";
            }
        }
        //jika pertama
        else if ($i == 0) {
            if ($ll == 1) {
                echo "<font color='#000'><b>" . $exRule[$i] . "</b></font> <b></b>";
            } else {
                echo $exRule[$i] . " ";
            }
        }
        //jika ditengah
        else {
            if ($exRule[$i] == "---- ") {
                echo $exRule[$i] . " ";
            } else {
                if ($exRule[$i - 1] == "---- ") {
                    echo "<font color='#000'><b>" . $exRule[$i] . "</b></font> <b></b>";
                } else {
                    $strip = '';
                    for ($x = 1; $x <= $i; $x++) {
                        $strip = $strip . "---- ";
                    }
                    echo "<br>" . $strip . "<font color='#000'><b>" . $exRule[$i] . "</b></font> <b></b>";
                }
            }
        }
        $i++;
    }
    echo "<br>";
    $ll++;
}

 * 
 */
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