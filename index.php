<?php
require_once "./sholat/include/system.php";
$url = GetURL() . "sholat/";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body onload="location.href = '<?= $url ?>';">
  test
</body>

</html>