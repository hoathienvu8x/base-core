<?php
if(!defined('MSG_DONTWAIT')) define('MSG_DONTWAIT', 0x40);
class ChatWS {
    private static $IP_ADDR = '0.0.0.0';
	private static $HTTP_PORT = '8080';
	private static $socket = null;
	private static $clients = null;
    private static $socketpath = 'ws/chatws.php';
    private static $magickey = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
	private $null = null;
	private static $msg = array();
	public static function getInstance() {
		if (self::$socket == null) {
			self::$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_set_option(self::$socket, SOL_SOCKET, SO_REUSEADDR, 1);
			socket_bind(self::$socket, self::$IP_ADDR, self::$HTTP_PORT);
			socket_listen(self::$socket);
			self::$clients = array(self::$socket);
		}
		self::run();
		socket_close(self::$socket);
	}
	public static function run() {
		while (true) {
			$changed = self::$clients;
			socket_select($changed, $null, $null, 0, 10);
			if (in_array(self::$socket, $changed)) {
				$socket_new = socket_accept(self::$socket);
				self::$clients[] = $socket_new;
				$header = socket_read($socket_new, 1024);
				self::client($header, $socket_new, self::$IP_ADDR, self::$HTTP_PORT, self::$socketpath, self::$magickey);
				socket_getpeername($socket_new, $ip);
                // First connect response full data to client connected
				$rsp = array(
                    'type' => 'system',
                    'message' => $ip.' connected'
                );
				$response = self::encode(json_encode($rsp));
				self::reply($socket_new,$response);
				$found_socket = array_search(self::$socket, $changed);
				unset($changed[$found_socket]);
			}
			// Check client is disconnected
			foreach ($changed as $changed_socket) {
				$buf = '';
                while (socket_recv($changed_socket, $buf, 10000, 0) >= 1) {
                    $received_text = self::decode($buf);
                    $received_text = stripslashes($received_text);
                    $tst_msg = json_decode($received_text);
                    $user_mode = (empty($tst_msg->mode) ? null : $tst_msg->mode);
                    $user_name = (empty($tst_msg->name) ? null : $tst_msg->name);
                    $user_message = (empty($tst_msg->message) ? null : $tst_msg->message);
                    $user_date = (empty($tst_msg->udate) ? null : $tst_msg->udate);
                    $response_text = self::encode( json_encode( array( 'type' => $user_mode, 'name' => $user_name, 'message' => $user_message, 'date' => $user_date ) ) );
                    self::send($response_text);
                    break 2;
                }
                $buf = @socket_read($changed_socket, 10000, PHP_BINARY_READ);
                if ($buf === false) {
                    $found_socket = array_search($changed_socket, self::$clients);
                    socket_getpeername($changed_socket, $ip);
                    unset(self::$clients[$found_socket]);
                    $response = self::encode( json_encode( array( 'type' => 'system', 'message' => $ip . ' disconnected' ) ) );
                    self::send($response);
                }
			}
			usleep(1000000);
		}
	}
	public static function decode($text) {
		$length = ord($text[1]) & 127;
		if($length == 126) {
			$messages = substr($text, 4, 4);
			$data = substr($text, 8);
		}
		elseif($length == 127) {
			$messages = substr($text, 10, 4);
			$data = substr($text, 14);
		}
		else {
			$messages = substr($text, 2, 4);
			$data = substr($text, 6);
		}
		$text = "";
		for ($i = 0; $i < strlen($data); ++$i) {
			$text .= $data[$i] ^ $messages[$i%4];
		}
		return $text;
	}
	public static function encode($text){
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($text);
		
		if($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header.$text;
	}
	public static function client($receved_header,$client_conn, $host, $port, $socketpath, $magickey) {
		$headers = array();
		$lines = preg_split("/\r\n/", $receved_header);
		foreach($lines as $line)
		{
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
			{
				$headers[$matches[1]] = $matches[2];
			}
		}
		$secKey = isset($headers['Sec-WebSocket-Key']) ? $headers['Sec-WebSocket-Key'] : '';
		$secAccept = base64_encode(pack('H*', sha1($secKey . $magickey)));
		$upgrade  = "HTTP/1.1 101 Switching Protocols\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"Sec-WebSocket-Version: 13\r\n" .
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
		socket_write($client_conn,$upgrade,strlen($upgrade));
	}
	// Send to all client connected
	public static function send($msg) {
		foreach(self::$clients as $changed_socket) {
			@socket_write($changed_socket,$msg,strlen($msg));
		}
		return true;
	}
	// Send to single client
	public static function reply($client, $msg) {
		@socket_write($client,$msg,strlen($msg));
		return true;
	}
}
// Well done we are run !
ChatWS::getInstance();
