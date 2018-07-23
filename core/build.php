<?php
define('ROOT_DIR', dirname(__FILE__));
define('DIR_SEP', DIRECTORY_SEPARATOR);
define('INC_DIR', ROOT_DIR . DIR_SEP . 'inc');
define('SRC_DIR', ROOT_DIR . DIR_SEP . 'src');

include INC_DIR. DIR_SEP . 'struct.php';
include INC_DIR. DIR_SEP . 'funcs.php';

$args = isset($_SERVER['argv']) ? array_slice($_SERVER['argv'],1) : array();
$dbuser = isset($args[0]) && !empty($args[0]) ? trim($args[0]) : 'root';
$dbpass = isset($args[1]) && !empty($args[1]) ? trim($args[1]) : '';
$dbname = isset($args[2]) && !empty($args[2]) ? trim($args[2]) : basename($dist).'db';
$prefix = isset($args[3]) && !empty($args[3]) ? trim($args[3]) : 'table_';
$dbhost = isset($args[3]) && !empty($args[3]) ? trim($args[3]) : 'localhost';

$dist = dirname(__FILE__) . DIR_SEP . 'baseapp';
c($dist, $struct);
$actions = $struct['action'];
foreach($actions as $act) {
	if ($act == 'logout.php') continue;
	$fname = str_replace('.php','',$act);
	if ($fname == 'home') {
		$fname = 'index';
	}
    
    $instance_cls = in_array($act,$struct['model']) ? "\n\$Model = ".ucfirst($fname)."::getInstance();\n" : "";
    
	$c = "<?php\nif ( ! defined ( 'INAPP' ) ) {\n\theader('HTTP/1.1 404 Not Found');\n\texit;\n}\n$instance_cls\nrequire_once Xtemplate::get( '$fname' );\nXtemplate::output();\nexit;";
	file_put_contents($dist . DIR_SEP . 'action' . DIR_SEP . $act, $c);
}
$model = $struct['model'];
$csql = "-- Table ".$prefix."options --
drop table if exists options;
create table if not exists options (
    option_name varchar(255) not null default '',
    option_value text not null,
    option_desc varchar(500) not null default '',
    autoload varchar(1) not null default 'y',
    primary key (option_name)
) engine=innodb charset=utf8;";
foreach($model as $m) {
	$cname = ucfirst(str_replace('.php','',$m));
    
    $tbname = preg_match('/y$/i',$cname) ? substr($cname,0,strlen($cname)-1).'ies' : $cname.'s';
    $tbname = strtolower($tbname);
    $csql .= "\n-- Table $tbname --\ndrop table if exists $tbname;\ncreate table if not exists $prefix$tbname (\n\tid int not null auto_increment,\n\t\n\tprimary key (id)\n) engine=innodb charset=utf8;";
	$c = "<?php
if ( ! defined ( 'INAPP' ) ) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
class $cname {
	private static \$db = null;
	private static \$instance = null;
	private function __construct() {
		self::\$db = Database::getInstance();
	}
	public static function getInstance() {
		if (self::\$instance == null) {
			self::\$instance = new $cname();
        }
		return self::\$instance;
	}
	public function add(\$data, \$isSyntax = false) {
		if (isset(\$data['id'])) {
			unset(\$data['id']);
        }
		if (empty(\$data)) {
		  return -1;
		}
		\$keys = \$values = array();
		foreach(\$data as \$key => \$value) {
			\$keys[] = \$key;
			\$values[] = \$isSyntax ? \$value : \"'\".self::\$db->escape_string(\$value).\"'\";
		}
		self::\$db->query(\"insert into \".DB_PREFIX.\"".$tbname." (\".implode(',',\$keys).\") values (\".implode(',',\$values).\")\");
		if (self::\$db->affected_rows() > 0) {
			return self::\$db->insert_id();
		}
        return -1;
    }
    public function update(\$data, \$id, \$isSyntax = false) {
        if (isset(\$data['id'])) {
			unset(\$data['id']);
        }
		if (empty(\$data) || intval(\$id) <= 0) {
            return -1;
		}
        \$items = array();
        foreach(\$data as \$key => \$value) {
			\$items[] = \$isSyntax ? \$key.\" = \".\$value : \"\$key = '\".self::\$db->escape_string(\$value).\"'\";
		}
		self::\$db->query(\"update \".DB_PREFIX.\"".$tbname." set \".implode(',',\$items).\" where id = \" . intval(\$id));
		if (self::\$db->affected_rows() > 0) {
			return \$id;
		}
        return -1;
    }
    public function delete(\$id) {
        if (intval(\$id) <= 0) {
            return -1;
        }
        self::\$db->query(\"delete from \".DB_PREFIX.\"".$tbname." where id = \" . intval(\$id));
        if (self::\$db->affected_rows() > 0) {
            return \$id;
        }
        return -1;
    }
    public function getOne(\$id) {
        if (intval(\$id) <= 0) {
            return false;
        }
        \$row = self::\$db->once_fetch_array(\"select * from \".DB_PREFIX.\"".$tbname." where id = \" . intval(\$id));
        return !\$row ? false : \$row;
    }
    public function getAll(\$options = array(), \$page = 1, \$limit = 20) {
        \$sql = \"select * from \".DB_PREFIX.\"".$tbname."\";
        \$conds = array();
        if (isset(\$options['keyword']) && !empty(\$options['keyword'])) {
            \$conds[] = \" like '%\".self::\$db->escape_string(\$options['keyword']).\"%'\";
        }
        
        if (!empty(\$conds)) {
            \$sql .= \" where \".implode(' and ',\$conds);
        }
        \$orders = array();
        
        if (!empty(\$orders)) {
            \$sql .= \" order by \".implode(',',\$orders);
        }
        \$sql .= \" limit \".((\$page - 1) * \$limit).\",\".\$limit;
        \$rows = array();
        \$query = self::\$db->query(\$sql);
        while (\$row = self::\$db->fetch_array(\$query)) {
            \$rows[] = \$row;
        }
        return \$rows;
    }
}";
    file_put_contents($dist . DIR_SEP . 'model' . DIR_SEP . $m, $c);
}
file_put_contents($dist . DIR_SEP . 'backups' . DIR_SEP . 'database.sql', $csql);
$tpl = $struct['template'];
unset($tpl['css'],$tpl['js']);
foreach($tpl as $t) {
    if ($t == 'style.css') continue;
    $c = "<?php
if ( ! defined ( 'INAPP' ) ) {
	header('HTTP/1.1 404 Not Found');
	exit;
}
";
    if (!in_array($t,array('header.php','footer.php','navigation.php'))) {
        $c .= "require_once Xtemplate::get('header');\n?>\n\n<?php\nrequire_once Xtemplate::get('footer');\n?>";
    }
    file_put_contents($dist . DIR_SEP . 'template' . DIR_SEP . $t, $c);
}

if (is_dir(SRC_DIR)) {
    $incs = $struct['include'];
    foreach($incs as $inc) {
        if (file_exists(SRC_DIR . DIR_SEP . 'include' . DIR_SEP . $inc)) {
            echo "Copying $inc\n";
            @copy(SRC_DIR . DIR_SEP . 'include' . DIR_SEP . $inc, $dist . DIR_SEP . 'include' . DIR_SEP . $inc);
        }
    }
    foreach($struct as $dir => $files) {
        if (is_array($files)) continue;
        if (file_exists(SRC_DIR . DIR_SEP . $files)) {
            echo "Copying $files\n";
            @copy(SRC_DIR . DIR_SEP . $files, $dist . DIR_SEP . $files);
            if ($files == 'config.php') {
                $content = file_get_contents($dist . DIR_SEP . $files);
                $keyhash = r(32).md5($dist.time());
                $cookieName = str_replace(array(' ','-'),'',basename($dist).'_'.r(32,false));
                $content = str_replace(array(
                    '{DB_HOST}','{DB_USER}','{DB_PASS}','{DB_NAME}','{DB_PREFIX}', '{KEY_HASH}','{COOKIE_NAME}'
                ), array(
                    $dbhost, $dbuser, $dbpass, $dbname, $prefix,$keyhash,$cookieName
                ), $content);
                file_put_contents($dist . DIR_SEP . $files,$content);
            }
        }
    }
}
