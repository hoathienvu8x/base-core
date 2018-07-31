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
$curent_ch = isset($_POST['cuahang']) ? $_POST['cuahang'] : '';
$year_folder = date('Y-m-d');
$msg_success = "";
$msg_error = "";
if (isset($_POST['cuahang'], $_POST['sodienthoai'], $_POST['fullname'])) {
    $tenkh = TiengVietKhongDau($_POST['fullname']);
    $sodt = sanitize($_POST['sodienthoai']);
    $directory_base = ABS_PATH . "/upload/$curent_ch/$year_folder/$tenkh/";
    $array = explode('.', $_POST['chandung']);
    $f_chandung = $directory_base . 'chandung_' . $sodt . '.' . strtolower(end($array));
    $array = explode('.', $_POST['cmnd_mt']);
    $f_cmnd_mt = $directory_base . 'cmnd_mt_' . $sodt . '.' . strtolower(end($array));
    $array = explode('.', $_POST['cmnd_ms']);
    $f_cmnd_ms = $directory_base . 'cmnd_ms_' . $sodt . '.' . strtolower(end($array));
    $array = explode('.', $_POST['pyc']);
    $f_pyc = $directory_base . 'pyc_' . $sodt . '.' . strtolower(end($array));
    //
    if (filesize($f_chandung) < 1) {
        $msg_error .= "Lỗi: up ảnh chân dung<br />";
    }
    if (filesize($f_cmnd_mt) < 1) {
        $msg_error .= "Lỗi: up ảnh CMND mặt trứớc<br />";
    }
    if (filesize($f_cmnd_ms) < 1) {
        $msg_error .= "Lỗi: up CMND mặt sau<br />";
    }
    if (filesize($f_pyc) < 1) {
        $msg_error .= "Lỗi: up phiếu yêu cầu<br />";
    }
    if (empty($msg_error)) {
        $msg_success = "Cập nhật hệ thống thành công";
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
            if (!empty($msg_error)) {
                echo "<div class='alert alert-error'>$msg_error</div>";
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
                    <form id="form1" method="post" name="form1">
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
                                    $selected = ($curent_ch == $mach) ? "selected='selected'" : "";
                                    echo "<option $selected value='$mach'>$tench</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="time_id" id="time_id" value="<?php echo date('His') . round(microtime(true) * 1000); ?>" />
                        <div class="control-group">
                            <label>Họ và tên Khách hàng</label>
                            <input name="fullname" id="tenkh" type="text" />
                        </div>
                        <div class="control-group">
                            <label>Số điện thoại</label>
                            <input name="sodienthoai" id="sodienthoai" type="text" />
                        </div>
                    </form>
                    <div class="control-group">
                        <label>Ảnh chân dung: <span id="lb_chandung"></span></label>
                        <input type="file" id="chandung" name="chandung" onchange="fileSelected('chandung');" accept="image/*"  />
                    </div>
                    <div class="control-group">
                        <label>CMND mặt trước: <span id="lb_cmnd_mt"></span></label>
                        <input type="file" id="cmnd_mt" name="cmnd_mt" onchange="fileSelected('cmnd_mt');" accept="image/*"  />
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
                    <input class="btn btn-primary" name="submit" value="Hoàn thành" type="button" onclick="onCheckSubmit();">
                    <label>&nbsp;</label>

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

            function onCheckSubmit() {
                var cmnd_mt = $('#cmnd_mt').val();
                var cmnd_ms = $('#cmnd_ms').val();
                var pyc = $('#pyc').val();
                var chandung = $('#chandung').val();
                var tenkh = document.getElementById('tenkh').value;
                var mach = document.getElementById('cuahang').value;
                //var time_id = document.getElementById('time_id').value;
                var sodienthoai = document.getElementById('sodienthoai').value;
                var time_id = document.getElementById('time_id').value;

//                var cmnd_mt = document.getElementById('cmnd_mt').files[0].name;
//                var cmnd_ms = document.getElementById('cmnd_ms').files[0].name;
//                var pyc = document.getElementById('pyc').files[0].name;
//                var chandung = document.getElementById('chandung').files[0].name;
//                var tenkh = document.getElementById('tenkh').value;
//                var mach = document.getElementById('cuahang').value;
//                var sodienthoai = document.getElementById('sodienthoai').value;

//                var fd = new FormData();
//                fd.append('tenkh', tenkh);
//                fd.append('cuahang', mach);
//                fd.append('sodienthoai', sodienthoai);
//                fd.append('time_id', time_id);
//                fd.append('cmnd_mt', sodienthoai);
//                fd.append('cmnd_ms', time_id);
//                fd.append('chandung', sodienthoai);
//                fd.append('pyc', time_id);
                $("#submit_msg").hide();
                $.post('ajax/quick_request.php', {'tenkh': tenkh, 'cuahang': mach, 'sodienthoai': sodienthoai, 'cmnd_mt': cmnd_mt, 'cmnd_ms': cmnd_ms, 'pyc': pyc, 'chandung': chandung}, function (result) {
                    if (result.status == 'success') {
                        window.location = "http://kbms.vn/result.php?time_id=" + time_id;
                    } else {
                        $("#submit_msg").html(result.msg).show();
                    }
                }, 'json');

            }


            function onCompleteSubmit() {
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
                                var fd = document.getElementById("form1");
                                var cmnd_mt = document.getElementById('cmnd_mt').files[0].name;
                                var input = document.createElement("input");
                                input.type = "hidden";
                                input.name = "cmnd_mt";
                                input.value = cmnd_mt;
                                fd.appendChild(input);
                                var cmnd_ms = document.getElementById('cmnd_ms').files[0].name;
                                var input = document.createElement("input");
                                input.type = "hidden";
                                input.name = "cmnd_ms";
                                input.value = cmnd_ms;
                                fd.appendChild(input);
                                var pyc = document.getElementById('pyc').files[0].name;
                                var input = document.createElement("input");
                                input.type = "hidden";
                                input.name = "pyc";
                                input.value = pyc;
                                fd.appendChild(input);
                                var chandung = document.getElementById('chandung').files[0].name;
                                var input = document.createElement("input");
                                input.type = "hidden";
                                input.name = "chandung";
                                input.value = chandung;
                                fd.appendChild(input);
                                fd.submit();
                            }
                        }
                    }

                }
            }
        </script>
    </body>
</html>
