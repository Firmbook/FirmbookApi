<?php
function dump($object) {
	print '<pre style="font-size: 10px; background-color: #BBDE16;">';
	var_dump($object);
	debug_print_backtrace();
	print '</pre>';
}
?>