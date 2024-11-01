<?php

require_once("currencyexchange_class.php");

$cEx =  new currencyExchange();
$cEx->getData();
 
$curFrom   = $_REQUEST["CURFROM"];
$curTo     = $_REQUEST["CURTO"];
$curAmount = $_REQUEST["CURAMOUNT"];

if ($result = $cEx->Convert($curFrom, $curTo, $curAmount))
{
	$result=number_format($result,2);
}

// Start XML file, echo parent node
$data = "<currencyexchange>";

$data .= "<resultexchange ";
$data .= 	'amount="' . $result . '" ';
$data .= "/>\n";

$data .= "</currencyexchange>";
echo $data;
?>