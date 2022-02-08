<?php
require_once __DIR__  . "/../include/system.php";
$_GET["cKey"]($_POST);

function SaveData($va)
{

  $cFileConfig = GetData("config.json");

  file_put_contents($cFileConfig, json_encode($va));

  // Jika Murotal Posisi Start Maka Volume Kita atur, kalau tidak maka tidak usah atur volume

  if (GetStatusMurotal()) {
    shell_exec("amixer set Master,0 " . $va["nMurotal_Volume"] . "%");
  }
  echo ("Data Sudah Disimpan .....");
}
