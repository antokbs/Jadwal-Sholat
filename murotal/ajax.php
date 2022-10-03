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
  $cFileConfig2 = GetData("murotal2.json");
  $cFileConfigAll = GetData("murotal_all.json");
  $data = array();
  $data2 = array();
  $data_all = array();
  foreach ($va as $value) {
    $cell = explode(",", $value);
    $data_all[$cell[0]] = array("check" => $cell[1], "repeat" => $cell[2], "volume" => $cell[3], "check2" => $cell[4], "repeat2" => $cell[5]);

    // Jika Surat Untuk Muratal 1 Kita Check
    if ($cell[1] == 1) $data[$cell[0]] = array("check" => $cell[1], "repeat" => $cell[2], "volume" => $cell[3]);

    // Jika Surat Untuk Murotal 2 Kita Check
    if ($cell[4] == 1) $data2[$cell[0]] = array("check" => $cell[4], "repeat" => $cell[5], "volume" => $cell[3]);
  }
  file_put_contents($cFileConfig, json_encode($data));
  file_put_contents($cFileConfig2, json_encode($data2));
  file_put_contents($cFileConfigAll, json_encode($data_all));
  echo ("Data Telah Disimpan .....");
}
