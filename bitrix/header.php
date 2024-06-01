<?

if ($_SERVER['REQUEST_URI'] == '/index.php') {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: /");
	exit();
}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog.php");?>
