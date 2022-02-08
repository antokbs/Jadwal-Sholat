<?php
require_once __DIR__ . "/../include/system.php";
$url = GetURL();
function ListSurah()
{
  $vaList = array();
  $cFileConfig = GetData("murotal.json");
  if (is_file($cFileConfig)) {
    $vaList = json_decode(file_get_contents($cFileConfig), true);
  }

  $cDir = GetConfig("cMurotal_Dir", "/home/pi/MP3");
  $nVolume = GetConfig("nMurotal_Volume", 60);
  $path = realpath($cDir);
  $vaSurah = array();
  if (is_dir($path)) {
    $vaFile = scandir($path);
    foreach ($vaFile as $surah) {
      if (substr($surah, 0, 1) !== "." && is_dir("$path/$surah")) {
        $vaData = explode(" ", $surah);
        $cSurah = $path . "/" . $surah;

        $vol = $nVolume;
        $rep = 1;
        $check = "";
        if (isset($vaList[$cSurah])) {
          $vol = $vaList[$cSurah]["volume"];
          $rep = $vaList[$cSurah]["repeat"];
          $check = $vaList[$cSurah]["check"] == 1 ? "checked" : "";
        }

        $vaSurah[$vaData[0]] = array("surah" => $surah, "path" => $cSurah, "volume" => $vol, "repeat" => $rep, "check" => $check);
      }
    }
  }
  return $vaSurah;
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Config Surat</title>
  <link rel="stylesheet" type="text/css" href="<?= $url ?>style.css">
  <script type="text/javascript" charset="utf8" src="<?= $url ?>index.js"></script>
  <script type="text/javascript" src="<?= $url ?>../include/system.js"></script>
</head>

<body>
  <form name="form1" action="<?= $url ?>" method="POST">
    <table id="surah" class="display" style="width:100%">
      <thead>
        <tr>
          <th width="40px">No</th>
          <th width="40px"><input type="checkbox" onclick="checkall(this);"></th>
          <th>Surat</th>
          <th width="60px">Repeat</th>
          <th width="90px">Volume</th>
        </tr>
      </thead>
      <tbody id="bodytable">
        <?php
        $vaSurah = ListSurah();
        foreach ($vaSurah as $key => $value) {
          echo ("
          <tr>
          <td style='text-align:center'>$key</td>
          <td style='text-align:center'><input type='checkbox' {$value['check']} id='status_$key'><input type='hidden' id='cPath_$key' value='{$value['path']}'></td>
          <td>{$value['surah']}</td>
          <td style='text-align:center'><input min='1' max='100' type='number' class='numCfg' id='repeat_$key' value='{$value['repeat']}'></td>
          <td style='text-align:center'><input min='1' max='100' type='number' class='numCfg' id='volume_$key' value='{$value['volume']}'>&nbsp;%</td>
          </tr>
          ");
        }
        ?>
      </tbody>
    </table>
    <div style="padding:8px;text-align:center">
      <input type="button" value="Simpan" name="cmdSave" onclick="cmdSave_onClick();">
    </div>
  </form>
</body>

</html>