<?php
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
$swagger = \OpenApi\scan('../');
$openApiJsonfile= '../documentation/openapi.json';
$fp = fopen($openApiJsonfile, 'w+');
fwrite($fp, $swagger->toJson());
fclose($fp);
?>