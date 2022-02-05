<?php
require_once __DIR__  . "/../include/system.php";
$_GET["cKey"]($_POST);
function ListSurah()
{
  $cDir = GetConfig("cMurotal_Dir", "/home/pi/MP3");
  $nVolume = GetConfig("nMurotal_Volume", 60);
  $path = realpath($cDir);
  $cSurah = "";
  if (is_dir($path)) {
    $vaFile = scandir($path);
    foreach ($vaFile as $surah) {
      if (substr($surah, 0, 1) !== "." && is_dir("$path/$surah")) {
        $vaData = explode(" ", $surah);
        $vaSurah[] = array("nSurah" => $vaData[0], "cSurah" => $surah, "cPath" => $path . "/" . $surah, "nVolume" => $nVolume);
        $cSurah = json_encode($vaSurah);
      }
    }
  }
  echo $cSurah;
}

function SaveData($va)
{
  $cFileConfig = GetData("murotal.json");
  $data = array();
  foreach ($va as $value) {
    $cell = explode(",", $value);
    $data[$cell[0]] = array("check" => $cell[1], "repeat" => $cell[2], "volume" => $cell[3]);
  }
  file_put_contents($cFileConfig, json_encode($data));
  echo ("Data Telah Disimpan .....");
}
