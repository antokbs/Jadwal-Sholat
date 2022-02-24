<?php
require_once __DIR__ . "/../include/system.php";
$url = GetURL();

function ListSurah()
{
  $vaList = array();
  $cFileConfig = GetData("murotal_all.json");
  if (is_file($cFileConfig)) {
    $vaList = json_decode(file_get_contents($cFileConfig), true);
  }

  $cDir = GetConfig("cMurotal_Dir", "/home/pi/MP3");
  $nVolume = GetConfig("nMurotal_Volume", 60);
  $path = realpath($cDir);
  $vaSurah = array();
  if (is_dir($path)) {
    $vaQari = scandir($path);
    foreach ($vaQari as $qari) {
      if (substr($qari, 0, 1) !== "." && is_dir("$path/$qari")) {
        $vaFile = scandir("$path/$qari");
        foreach ($vaFile as $surah) {
          if (substr($surah, 0, 1) !== "." && is_dir("$path/$qari/$surah")) {
            $vaData = explode(" ", $surah);
            $cSurah = "$path/$qari/$surah";

            $vol = $nVolume;
            $rep = 1;
            $check = "";
            if (isset($vaList[$cSurah])) {
              $vol = $vaList[$cSurah]["volume"];
              $rep = $vaList[$cSurah]["repeat"];
              $check = $vaList[$cSurah]["check"] == 1 ? "checked" : "";
            }

            $vaSurah[$qari][$vaData[0]] = array("surah" => $surah, "path" => $cSurah, "volume" => $vol, "repeat" => $rep, "check" => $check);
          }
        }
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
  <form name="form1">
    <table id="surah" class="display" style="width:100%">
      <thead id="tableHeader" class="tableHeader">
        <tr>
          <th width="60px">No</th>
          <th width="60px"></th>
          <th>Surat</th>
          <th width="100px">Repeat</th>
          <th width="150px">Volume</th>
        </tr>
      </thead>
      <tbody id="bodytable">
        <?php
        $vaQari = ListSurah();
        $x = 1;
        foreach ($vaQari as $qari => $vaSurah) {
          $x++;
          echo ("
            <tr style='background-color: blue;color:white'>
            <td></td>
            <td style='text-align:center'><input class='ckBox' type='checkbox' id='status_id_$x' onclick='checkall(this);'></td>
            <td onClick='setupDisplay($x);' colspan='3'><strong>$qari</strong></td>
            </tr>
          ");
          foreach ($vaSurah as $key => $value) {
            $id = "id_" . $x . "_" . $key;
            echo ("
            <tr class='qari_$x'>
            <td id='$id' style='text-align:center'>$key</td>
            <td style='text-align:center'><input class='ckBox' type='checkbox' {$value['check']} id='status_$id'><input type='hidden' id='cPath_$id' value='{$value['path']}'></td>
            <td>{$value['surah']}</td>
            <td style='text-align:center'><input min='1' max='100' type='number' class='numCfg' id='repeat_$id' value='{$value['repeat']}'></td>
            <td style='text-align:center'><input min='1' max='100' type='number' class='numCfg' id='volume_$id' value='{$value['volume']}'>&nbsp;%</td>
            </tr>
            ");
          }
        }
        ?>
      </tbody>
    </table>
    <div class="divButton">
      <input class="cmdButton" type="button" value="Simpan" name="cmdSave" onclick="cmdSave_onClick();">
    </div>
  </form>
</body>

</html>