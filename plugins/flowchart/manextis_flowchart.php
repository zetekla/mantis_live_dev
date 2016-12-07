<?php
header('Content-Type: application/json');
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, 'http://erp-2/ews/ManexWebService.asmx/GetWorkInstructionsByWorkOrderNo?WorkOrderNo=191915a');
$result = curl_exec($ch);
curl_close($ch);
$obj = json_decode($result);
echo $result;
?>