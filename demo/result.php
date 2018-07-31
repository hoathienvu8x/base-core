<?php
include 'inc.php';
if (isset($_GET['time_id'])) {
    $timeid = sanitize($_GET['time_id']);
    $result = sql_select("SELECT * FROM `khachhang` WHERE `time_id`='$timeid' ");
    //print_r($result);
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Cập nhật thông tin thuê bao</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <script src="js/jquery-1.7.2.min.js"></script>
        <style type="text/css">
            @media (min-width: 979px) {
                body {
                    padding-top: 60px;
                    padding-bottom: 40px;
                }
            }
        </style>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
    </head>

    <body>

        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">Cập nhật thông tin thuê bao</a>
                    <div class="nav-collapse">

                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">
            <div class="section section-small">
                <div class="section-header">
                    <h5>THÔNG TIN KHÁCH HÀNG ĐÃ CẬP NHẬT</h5>
                </div>
                <div class="section-body" style="margin-bottom: 20px;">
                    <?php
                    foreach ($result as $img_info) {
                        echo "<img width='150' src='upload/" . $img_info['dir_server'] . $img_info['tenfile'] . "' />";
                    }
                    ?>
                </div>
                <a href="http://kbms.vn/anminh.php" class="btn btn-primary">Quay lại đăng ký</a>

            </div>
        </div>

    </body>
</html>
