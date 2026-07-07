<?php
require_once ("tcpdf_barcodes_2d.php");
$code = $_REQUEST['cardnumber'].'!NCT-HD';
$type = "PDF417";
$barcodeobj = new TCPDF2DBarcode($code, $type);
print_r($barcodeobj->getBarcodeHTML(3,3));
?>
