
<?php
session_start();
if ( isset($_SESSION['kepribadian_nbc_c4.5_id']) ) {
    header("location:index.php");
}

$login = 0;
if (isset($_GET['login'])) {
    $login = $_GET['login'];
}

if ($login == 1) {
    $komen = "Silahkan Login Ulang, Cek username dan Password Anda!!";
}

include_once "fungsi.php";
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title>Login</title>
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

            <!--left-fixed -navigation-->
            <?php
            include "sidebar.php";
            include "header-menu.php";
            ?>
            <!-- //header-ends -->
            <!-- main content start-->
            <div id="page-wrapper">
                <div class="main-page">
                    <div class="forms">
                        <h2 class="title1" style="text-align:center; font-family:'Lucida Sans';">Form Login</h2> 
                        
                        <div class="content-wrapper">

                            <div class="row" >
                                <div class="col-md-3">
                                </div>
                                    <div class="col-md-6" >
                                        <br />
                                        <?php
                                            if (isset($komen)) {
                                                display_error("Login failed");
                                            }
                                        ?>
                                        <form method="post" action="cek_login.php" >
                                            <label style="font-family:'Montserrat';">Username : </label>
                                            <input type="text" class="form-control" name="username" autofocus />
                                            <label style="font-family:'Montserrat';">Password :  </label>
                                            <input type="password" class="form-control" name="password" />
                                            <hr />
                                            <div style="text-align:center">
                                                <button type="submit" class="btn btn-info" style="background-color:#f2b33f;font-family:'Montserrat';">
                                                    <span class="glyphicon glyphicon-user"  ></span> &nbsp; Log In
                                                </button>&nbsp;
                                            </div>
                                                    
                                        </form>
                                    </div>
                            <div class="col-md-3">
                        </div>

                    </div>
                </div>
            </div>



            <!--footer-->
            
            <!--//footer-->
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