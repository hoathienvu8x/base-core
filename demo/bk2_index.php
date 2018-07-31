<?php
include 'inc.php';
$ds_cuahang = array(
    'xnkrga' => 'CH XNK Rạch giá',
    'xnkrs' => 'CH XNK Rạch Sỏi',
    '1400330004' => 'CHTT Lạc Hồng',
    '1400330001' => 'CHTT 33 NBK',
    '1400330005' => 'CHTT Rạch Sỏi',
    '1400330019' => 'CHTT Hòn Tre',
    '1400330010' => 'CHTT Châu Thành',
    '1400330011' => 'CHTT Giồng Riềng',
    '1400330012' => 'CHTT Gò Quao',
    '1400330009' => 'CHTT Tân Hiệp',
    '1400330013' => 'CHTT An Biên',
    '1400330014' => 'CHTT An Minh',
    '1400330015' => 'CHTT Vĩnh Thuận',
    '1400330017' => 'CHTT U Minh Thượng',
    '1400330008' => 'CHTT Hòn Đất',
    '1400330018' => 'CHTT Sóc Xoài',
    '1400330007' => 'CHTT Kiên Lương',
    '1400330006' => 'CHTT Hà Tiên',
    '1400330003' => 'CHTT Giang Thành',
    '1400330016' => 'CHTT Dương Đông',
    '1400330002' => 'CHTT An Thới',
);
$msg = "";
$cuahang = "";
$msg_success = "";
if (isset($_POST['cuahang'], $_POST['fullname'], $_POST['sodienthoai'], $_FILES['chandung'], $_FILES['cmnd_mt'], $_FILES['cmnd_ms'], $_FILES['pyc'])) {
    include ABS_PATH . '/funcs/khachhang.php';
    $cuahang = isset($_POST['cuahang']) ? $_POST['cuahang'] : '';
    $tenkh = TiengVietKhongDau($_POST['fullname']);
    $year_folder = date('Y-m-d');
    $sodt = sanitize($_POST['sodienthoai']);
    $time_id = date('His');
    $directory_base = ABS_PATH . "/upload/"; //$cuahang/$year_folder/$tenkh/";
    if (!is_dir($directory_base)) {
        mkdir($directory_base, 0775, true);
        chmod($directory_base, 0775);
    }
    $directory_base .= "/$cuahang/";
    if (!is_dir($directory_base)) {
        mkdir($directory_base, 0775, true);
        chmod($directory_base, 0775);
    }
    $directory_base .= "$year_folder/";
    if (!is_dir($directory_base)) {
        mkdir($directory_base, 0775, true);
        chmod($directory_base, 0775);
    }
    $directory_base .= "$tenkh/";
    if (!is_dir($directory_base)) {
        mkdir($directory_base, 0775, true);
        chmod($directory_base, 0775);
    }
    $array = explode('.', $_FILES['chandung']['name']);
    $extension = end($array);
    $database_url = "/$cuahang/$year_folder/$tenkh/";
    $file_chandung = "chandung_$sodt" . "." . $extension;

    $file_cmndmt = "cmndmt_$sodt" . "." . $extension;
    $file_cmndms = "cmndms_$sodt" . "." . $extension;
    $file_pyc = "pyc_$sodt" . "." . $extension;
    //createfile
    if (move_uploaded_file($_FILES['chandung']['tmp_name'], $directory_base . $file_chandung)) {
        addFileImage($time_id, $cuahang, $tenkh, $file_chandung, $database_url);
    } else {
        $msg .= "Lỗi: up ảnh chân dung<br />";
    }
    if (move_uploaded_file($_FILES['cmnd_mt']['tmp_name'], $directory_base . $file_cmndmt)) {
        addFileImage($time_id, $cuahang, $tenkh, $file_cmndmt, $database_url);
    } else {
        $msg .= "Lỗi: up ảnh CMND mặt trước<br />";
    }
    if (move_uploaded_file($_FILES['cmnd_ms']['tmp_name'], $directory_base . $file_cmndms)) {
        addFileImage($time_id, $cuahang, $tenkh, $file_cmndms, $database_url);
    } else {
        $msg .= "Lỗi: up ảnh CMND mặt sau<br />";
    }
    if (move_uploaded_file($_FILES['pyc']['tmp_name'], $directory_base . $file_pyc)) {
        addFileImage($time_id, $cuahang, $tenkh, $file_pyc, $database_url);
    } else {
        $msg .= "Lỗi: up ảnh phiếu yêu cầu<br />";
    }
    if (empty($msg)) {
        $msg_success = "Cập nhật hành công " . $_POST['fullname'];
    }
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
            <?php
            if (!empty($msg)) {
                echo "<div class='alert'>$msg</div>";
            }
            if (!empty($msg_success)) {
                echo "<div class='alert alert-success'>$msg_success</div>";
            }
            ?>
            <div class="section section-small">
                <div class="section-header">
                    <h5>NHẬP THÔNG TIN KHÁCH HÀNG</h5>
                </div>
                <div class="section-body">
                    <form id="form1" onsubmit="return onCompleteSubmit();" method="post" name="form1" enctype="multipart/form-data">
                        <div class="control-group" id="details">
                        </div>
                        <div id="progress" style="display: none;" class="progress progress-striped active">
                            <div class="bar" id="process_content" style="width: 0%;"></div>
                        </div>
                        <div class="control-group">
                            <label>Cửa hàng</label>
                            <select id="cuahang" name="cuahang">
                                <?php
                                foreach ($ds_cuahang as $mach => $tench) {
                                    $selected = ($cuahang == $mach) ? "selected='selected'" : "";
                                    echo "<option $selected value='$mach'>$tench</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="time_id" id="time_id" value="" />
                        <div class="control-group">
                            <label>Họ và tên Khách hàng</label>
                            <input name="fullname" id="tenkh" type="text" />
                        </div>
                        <div class="control-group">
                            <label>Số điện thoại</label>
                            <input name="sodienthoai" id="sodienthoai" type="text" />
                        </div>
                        <div class="control-group">
                            <label>Ảnh chân dung: <span id="lb_chandung"></span></label>
                            <input type="file" id="chandung" name="chandung" onchange="fileSelected('chandung');" accept="image/*" />
                        </div>
                        <div class="control-group">
                            <label>CMND mặt trước: <span id="lb_cmnd_mt"></span></label>
                            <input type="file" id="cmnd_mt" name="cmnd_mt" onchange="fileSelected('cmnd_mt');" accept="image/*" />
                        </div>
                        <div class="control-group">
                            <label>CMND mặt sau: <span id="lb_cmnd_ms"></span></label>
                            <input type="file" id="cmnd_ms" name="cmnd_ms" onchange="fileSelected('cmnd_ms');" accept="image/*" />
                        </div>
                        <div class="control-group">
                            <label>Phiếu yêu cầu: <span id="lb_pyc"></span></label>
                            <input type="file" name="pyc" id="pyc" onchange="fileSelected('pyc');" accept="image/*" />
                        </div>
                        <label>
                            <div class="alert alert-block" style="display: none" id="submit_msg"></div>
                        </label>
                        <input class="btn btn-primary" name="submit" value="Hoàn thành" type="submit" />
                        <label>&nbsp;</label>
                    </form>

                </div>
            </div>
        </div> <!-- /container -->
        <script type="text/javascript">
            var file_curent = '';
            var upload_result = [];
            upload_result['lb_chandung'] = false;
            upload_result['lb_cmnd_mt'] = false;
            upload_result['lb_cmnd_ms'] = false;
            upload_result['lb_pyc'] = false;
            function fileSelected(id_process) {
                return "";
                //no thing
                file_curent = 'lb_' + id_process;
                var fd = new FormData();
                var count = document.getElementById(id_process).files.length;
                //var img = document.getElementById(id_process).value; //image muốn gửi
                var tenkh = document.getElementById('tenkh').value;
                var mach = document.getElementById('cuahang').value;
                var time_id = document.getElementById('time_id').value;
                var sodienthoai = document.getElementById('sodienthoai').value;

                if (tenkh == '' || mach == '' || sodienthoai == '' || count <= 0) {
                    alert('Vui lòng chọn Cửa hàng và nhập tên khách hàng')
                } else {
                    for (var index = 0; index < count; index++)
                    {
                        var file = document.getElementById(id_process).files[index];
                        fd.append('myFile', file);
                    }
                    fd.append('tenkh', tenkh);
                    fd.append('cuahang', mach);
                    fd.append('sodienthoai', sodienthoai);
                    fd.append('filename', id_process);
                    fd.append('time_id', time_id);
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener("progress", uploadProgress, false);
                    xhr.addEventListener("load", uploadComplete, false);
                    xhr.addEventListener("error", uploadFailed, false);
                    xhr.addEventListener("abort", uploadCanceled, false);
                    xhr.open("POST", "ajax/process_file.php");
                    xhr.send(fd);
                }
            }
            function uploadFile() {
                var fd = new FormData();
                var count = document.getElementById('fileToUpload').files.length;
                var note = document.getElementById('note').value;
                var computer = document.getElementById('computer').value;
                document.getElementById('progress').style.display = 'block';
                for (var index = 0; index < count; index++)
                {
                    var file = document.getElementById('fileToUpload').files[index];
                    fd.append('myFile', file);
                }
                fd.append('note', note);
                fd.append('computer', computer);
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener("progress", uploadProgress, false);
                xhr.addEventListener("load", uploadComplete, false);
                xhr.addEventListener("error", uploadFailed, false);
                xhr.addEventListener("abort", uploadCanceled, false);
                xhr.open("POST", "ajax/process_file.php");
                xhr.send(fd);
            }
            function uploadProgress(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = Math.round(evt.loaded * 100 / evt.total);
                    document.getElementById('process_content').style.width = percentComplete.toString() + '%';
                    //document.getElementById('progress').innerHTML = percentComplete.toString() + '%';
                } else {
                    //document.getElementById('progress').innerHTML = 'unable to compute';
                }
            }

            function uploadComplete(evt) {
                var result = JSON.parse(evt.target.responseText);
                if (result.status == 'error') {

                    document.getElementById(file_curent).innerHTML = "<font color='#ff0000'>" + result.msg + "</font>";
                } else if (result.status = 'success') {
                    upload_result[file_curent] = true;
                    document.getElementById(file_curent).innerHTML = "<font color='#00b20b'>" + result.msg + "</font>";
                }
            }
            function uploadFailed(evt) {
                document.getElementById(file_curent).innerHTML = "<font color='#ff0000'>Đã có lỗi xẩy ra trong quá trình xử lý</font>";
            }

            function uploadCanceled(evt) {
                document.getElementById(file_curent).innerHTML = "<font color='#ff0000'>Hủy yêu cầu upload</font>";
            }

            function onCompleteSubmit() {
                $("#submit_msg").hide();
                var tenkh = document.getElementById('tenkh').value;
                var mach = document.getElementById('cuahang').value;
                var sodienthoai = document.getElementById('sodienthoai').value;
                var chandung = document.getElementById('chandung').value;
                var cmtmt = document.getElementById('cmnd_mt').value;
                var cmtms = document.getElementById('cmnd_ms').value;
                var pyc = document.getElementById('pyc').value;
                if (tenkh != "" && mach != "" && sodienthoai != "" && chandung != "" && cmtmt != "" && cmtms != "" && pyc != "") {
                    document.getElementById("form1").submit();
                } else {
                    $("#submit_msg").html("Không được để trống dữ liệu").show();
                }
                return false;
                /*
                 $("#submit_msg").hide();
                 if (upload_result['lb_chandung'] == false) {
                 $("#submit_msg").text("Bạn chưa up ảnh chân dung").show();
                 } else {
                 if (upload_result['lb_cmnd_mt'] == false) {
                 $("#submit_msg").text("Bạn chưa up ảnh CMND mặt trước").show();
                 } else {
                 if (upload_result['lb_cmnd_ms'] == false) {
                 $("#submit_msg").text("Bạn chưa up ảnh CMND mặt sau").show();
                 } else {
                 if (upload_result['lb_pyc'] == false) {
                 $("#submit_msg").text("Bạn chưa up ảnh phiếu yêu cầu").show();
                 } else {
                 document.getElementById("form1").submit();
                 }
                 }
                 }
                 
                 }
                 */
            }
        </script>
    </body>
</html>
