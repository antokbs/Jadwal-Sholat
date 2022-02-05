<?php
require_once __DIR__  . "/../include/system.php";
$_GET["cKey"]($_POST);
function ListSurah()
{
  $cDir = GetConfig("cMurotal_Dir", "/home/pi/MP3");
  $path = realpath($cDir);
  $cSurah = "";
  if (is_dir($path)) {
    $vaFile = scandir($path);
    foreach ($vaFile as $surah) {
      if (substr($surah, 0, 1) !== "." && is_dir("$path/$surah")) {
        $vaData = explode(" ", $surah);
        $vaSurah[] = array("nSurah" => $vaData[0], "cSurah" => $surah, "cPath" => $path . "/" . $surah);
        $cSurah = json_encode($vaSurah);
      }
    }
  }
  echo $cSurah;
}
