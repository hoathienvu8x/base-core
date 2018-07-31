<?php
define('SITE_ROOT', dirname(__FILE__));
define('SITE_URL', 'http://kbms.vn/');
define('UPLOAD_URL', SITE_URL . 'upload/');
define('UPLOAD_PATH', SITE_ROOT . DIRECTORY_SEPARATOR . 'upload');
define('AUTH_KEY', sha1('mtrquang$123'));

define('DB_NAME', 'thongtinthuebao');
define('DB_USER', 'root');
define('DB_PASSWORD', 'ddde310b09da243b1');
define('DB_HOST', 'localhost');

$_REQUEST = $_POST + $_GET;

if (!isset($_REQUEST['key']) || $_REQUEST['key'] != AUTH_KEY) {
    header('Content-Type:text/plain;charset=utf-8');
    //echo AUTH_KEY;
    exit('No Access');
}

require_once SITE_ROOT . '/mysqlii.php';

$DB = MySqlii::getInstance();

if (!isset($_GET['customer']) || empty($_GET['customer'])) {
    // i.e: ?key=you_access_key&page=1
    // i.e: ?key=you_access_key&limit=100
    // i.e: ?key=you_access_key&ondate=201804,?key=you_access_key&ondate=20180423 
    // i.e: ?key=you_access_key&shopid=1213 
    $paged = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 20;

    $ondate = isset($_GET['ondate']) && preg_match('/^[0-9]{6,8}$/',$_GET['ondate']) ? trim($_GET['ondate']) : '';
    $shopid = isset($_GET['shopid']) && !empty($_GET['shopid']) ? trim($_GET['shopid']) : '';

    $sql = "select distinct time_id, tenkhachhang, ngaytao from khachhang";

    $conds = array();

    if (!empty($ondate)) {
        if (strlen($ondate) == 8) {
            $conds[] = "date_format(ngaytao, '%Y%m%d') = '".$DB->escape_string($ondate)."'";
        } else if (strlen($ondate) == 6) {
            $conds[] = "date_format(ngaytao, '%Y%m') = '".$DB->escape_string($ondate)."'";
        } else if (strlen($ondate) == 4) {
            $conds[] = "year(ngaytao) = '".$DB->escape_string($ondate)."'";
        }
    }

    if (!empty($shopid)) {
        $conds[] = "cuahang = '".$DB->escape_string($shopid)."'";
    }

    if (empty($conds)) {
        $conds[] = "date_format(ngaytao, '%Y-%m-%d') = (select max(date_format(ngaytao, '%Y-%m-%d')) from khachhang)";
    }

    if (!empty($conds)) {
        $sql .= " where ".implode(' and ', $conds);
    }
    $sql .= " order by ngaytao desc limit ".(($paged - 1) * $limit).", $limit";

    $query = $DB->query($sql);
    $jsonp = isset($_GET['jsonp']) && !empty($_GET['jsonp']) ? trim($_GET['jsonp']) : '';
    define('JSONP_VAR', !empty($jsonp));
    if (JSONP_VAR) {
        header('Content-Type:application/json;charset=utf-8');
        if ($DB->num_rows($query) <= 0) {
            echo $jsonp.'("No data");';
            exit;
        } else {
            $rows = array();
            while($row = $DB->fetch_array($query)) {
                $rows[] = $row;
            }
            $total = $DB->num_rows($query);
            $query = $DB->query("select distinct time_id from khachhang".(!empty($conds) ? " where ".implode(' and ', $conds) : ''));
            $total = $DB->num_rows($query);
            if ($total > 0) {
                $limit = $limit <= 0 ? 20 : $limit;
                $pages = ceil($total/$limit);
            } else {
                $pages = 1;
            }
            $data = array('results' => $rows, 'pages' => $pages);
            echo $jsonp.'('.json_encode($data,JSON_UNESCAPED_UNICODE).');';
            exit;
        }
    }
    ?>
<!doctype html><html><head><meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="utf-8"><title>Danh sách khách hàng đã cập nhật thông tin</title><style><?php ob_start(); ?>
* {
	margin: 0;
	padding: 0;
}

body {
	background-color: #f1f1f1;
	font-size: 13px;
	font-family: Arial, Helvetica, sans-serif;
    padding: 2%;
}
    a {
        text-decoration: none;
        color: brown;
    }
    table {
        min-width: 500px;
        max-width: 750px;
        border-collapse: collapse;
        background-color: #fff;
        margin-bottom: 10px;
        box-shadow: 0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);
    }
    tr, td,th {
        border-collapse: collapse;
    }
    td, th {
        padding: 6px 3px;
    }
    tr:nth-child(2n+2) {
        background-color: #eee;
    }
    th:first-child,td:first-child {
        padding-left: :6px;
    }
    th:last-child, td:last-child {
        padding-right: 6px;
        text-align: right;
    }
    a.pager, span.pager {
        display: inline-block;
        padding: 3px 8px;
        background-color: darkcyan;
        color: white;
        margin-right: 3px;
        border-radius: 3px;
    }
    span.pager {
        background-color: orange;
    }
@media only screen and (min-width :320px) and (max-width :780px) {
    body {
        font-size: 90%;
    }
}
<?php
$content = ob_get_contents();
ob_clean();
ob_end_clean();
ob_end_flush();
$content = preg_replace('/[\r\n\t]*/','',$content);
$content = preg_replace('/ {2,}/',' ',$content);
echo trim($content);
?>
</style></head><body>
    <?php
    if ($DB->num_rows($query) <= 0) {
        echo 'No data';
    } else {
        ?>
        <table>
        <?php
        while($row = $DB->fetch_array($query)) {
            ?>
            <tr>
                <td><a href="<?php echo SITE_URL;?>admin.php?key=<?php echo AUTH_KEY; ?>&customer=<?php echo $row['time_id'];?>"><?php echo $row['time_id']; ?></a></td>
                <td><?php echo $row['tenkhachhang']; ?></td>
                <td><?php echo date('d/m/Y @H:i', strtotime($row['ngaytao'])); ?></td>
            </tr>
            <?php
        }
        ?>
        </table>    
        <?php
        $query = $DB->query("select distinct time_id from khachhang".(!empty($conds) ? " where ".implode(' and ', $conds) : ''));
        $total = $DB->num_rows($query);
        if ($total > 0) {
            $limit = $limit <= 0 ? 20 : $limit;
            $pages = ceil($total/$limit);
            $start = $paged - 1 > 0 ? $paged - 1 : 1;
            $end = $start + 1 > $pages ? $pages : $start + 1;
            $args = array();
            if ($limit > 20) {
                $args[] = 'limit='.$limit;
            }
            if (!empty($ondate)) {
                $args[] = 'ondate='.$ondate;
            }
            if (!empty($shopid)) {
                $args[] = 'shopid='.$shopid;
            }
            $query_args = !empty($args) ? '&'.implode('&',$args) : '';
            if ($paged >= 3) {
                ?>
                <a class="pager first" href="<?php echo SITE_URL; ?>admin.php?key=<?php echo AUTH_KEY.$query_args;?>">Trang đầu</a>
                <a class="pager" href="<?php echo SITE_URL; ?>admin.php?key=<?php echo AUTH_KEY.$query_args;?>&page=<?php echo ($paged - 1); ?>">&larr;</a>
                <?php
            }
            for($i = $start; $i <= $end; $i++) {
                if ($i == $paged) {
                    echo '<span class="pager">'.$paged.'</span>';
                } else {
                    ?><a class="pager" href="<?php echo SITE_URL; ?>admin.php?key=<?php echo AUTH_KEY.$query_args;?>&page=<?php echo $i; ?>"><?php echo $i; ?></a><?php
                }
            }
            if ($paged <= $pages - 1) {
                 ?>
                <a class="pager" href="<?php echo SITE_URL; ?>admin.php?key=<?php echo AUTH_KEY.$query_args;?>&page=<?php echo ($paged + 1); ?>">&rarr;</a>
                <a class="pager last" href="<?php echo SITE_URL; ?>admin.php?key=<?php echo AUTH_KEY.$query_args;?>&page=<?php echo $pages;?>">Trang cuối</a>
                <?php
            }
        }
    }
    ?>
    </body></html>
    <?php
} else {
    $time_id = trim($_GET['customer']);
    $jsonp = isset($_GET['jsonp']) && !empty($_GET['jsonp']) ? trim($_GET['jsonp']) : '';
    define('JSONP_VAR', !empty($jsonp));
    if (JSONP_VAR) {
        header('Content-Type:application/json;charset=utf-8');
    }
    $query = $DB->query("select * from khachhang where time_id = '".$DB->escape_string($time_id)."'");
    if ($DB->num_rows($query) <= 0) {
        if (JSONP_VAR) {
            echo $jsonp.'("No data");';
        } else {
            echo 'No data';
        }
    } else {
        $raw = array();
        while ($row = $DB->fetch_array($query)) {
            $raw['tenkhachhang'] = $row['tenkhachhang'];
            $raw['time_id'] = $row['time_id'];
            $raw['ngaytao'] = $row['ngaytao'];
            if (preg_match('/^chandung_/i',$row['tenfile'])) {
                $raw['chandung'] = array(
                    'tenfile' => $row['tenfile'],
                    'dir_server' => $row['dir_server'],
                    'url' => UPLOAD_URL . trim($row['dir_server']) . '/'.$row['tenfile']
                );
            }
            if (preg_match('/^pyc_/i',$row['tenfile'])) {
                $raw['pyc'] = array(
                    'tenfile' => $row['tenfile'],
                    'dir_server' => $row['dir_server'],
                    'url' => UPLOAD_URL . trim($row['dir_server']) . '/'.$row['tenfile']
                );
            }
            if (preg_match('/^cmnd_mt_/i',$row['tenfile']) || preg_match('/^cmndmt_/i',$row['tenfile'])) {
                $raw['cmndmt'] = array(
                    'tenfile' => $row['tenfile'],
                    'dir_server' => $row['dir_server'],
                    'url' => UPLOAD_URL . trim($row['dir_server']) . '/'.$row['tenfile']
                );
            }
            if (preg_match('/^cmnd_ms_/i',$row['tenfile']) || preg_match('/^cmndms_/i',$row['tenfile'])) {
                $raw['cmndms'] = array(
                    'tenfile' => $row['tenfile'],
                    'dir_server' => $row['dir_server'],
                    'url' => UPLOAD_URL . trim($row['dir_server']) . '/'.$row['tenfile']
                );
            }
        }
        if (JSONP_VAR) {
            echo $jsonp.'('.json_encode($raw, JSON_UNESCAPED_UNICODE).');';
            exit;
        }
        ?><!doctype html><html><head></head><body>
        <pre>
        <?php print_r($raw); ?>
        </pre>
    </body></html>
        <?php
    }
}