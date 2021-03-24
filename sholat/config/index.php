<?php
require_once "../include/system.php";
$url = GetURL();
$cFileConfig = GetData("config.json");

if (isset($_POST["nSave"])) {
  file_put_contents($cFileConfig, json_encode($_POST));

  // Jika Murotal Posisi Start Maka Volume Kita atur, kalau tidak maka tidak usah atur volume
  if (GetStatusMurotal()) {
    shell_exec("amixer set Master,0 " . $_POST["nMurotal_Volume"] . "%");
  }
}
if (is_file($cFileConfig)) $va = json_decode(file_get_contents($cFileConfig), true);
$nSubuh = isset($va["nSubuh"]) ? $va["nSubuh"] : 0;
$nDzuhur = isset($va["nDzuhur"]) ? $va["nDzuhur"] : 0;
$nAshar = isset($va["nAshar"]) ? $va["nAshar"] : 0;
$nMaghrib = isset($va["nMaghrib"]) ? $va["nMaghrib"] : 0;
$nIsya = isset($va["nIsya"]) ? $va["nIsya"] : 0;
$nSubuh_Iqomah = isset($va["nSubuh_Iqomah"]) ? $va["nSubuh_Iqomah"] : 0;
$nDzuhur_Iqomah = isset($va["nDzuhur_Iqomah"]) ? $va["nDzuhur_Iqomah"] : 0;
$nAshar_Iqomah = isset($va["nAshar_Iqomah"]) ? $va["nAshar_Iqomah"] : 0;
$nMaghrib_Iqomah = isset($va["nMaghrib_Iqomah"]) ? $va["nMaghrib_Iqomah"] : 0;
$nIsya_Iqomah = isset($va["nIsya_Iqomah"]) ? $va["nIsya_Iqomah"] : 0;
$nLamaAdzan = isset($va["nLamaAdzan"]) ? $va["nLamaAdzan"] : 3;

$nSubuh_Volume = isset($va["nSubuh_Volume"]) ? $va["nSubuh_Volume"] : 100;
$nDzuhur_Volume = isset($va["nDzuhur_Volume"]) ? $va["nDzuhur_Volume"] : 100;
$nAshar_Volume = isset($va["nAshar_Volume"]) ? $va["nAshar_Volume"] : 100;
$nMaghrib_Volume = isset($va["nMaghrib_Volume"]) ? $va["nMaghrib_Volume"] : 100;
$nIsya_Volume = isset($va["nIsya_Volume"]) ? $va["nIsya_Volume"] : 100;
$nMurotal_Volume = isset($va["nMurotal_Volume"]) ? $va["nMurotal_Volume"] : 60;

$nHijriah = isset($va["nHijriah"]) ? $va["nHijriah"] : 0;

$nTimeZone = isset($va["nTimeZone"]) ? $va["nTimeZone"] : 7;
$cKoordinat = isset($va["cKoordinat"]) ? $va["cKoordinat"] : "-7.938679, 112.659707";
$lYa = isset($va["nMatikanMurotalMalam"]) ? $va["nMatikanMurotalMalam"] == "Y" : true;
$Reload = isset($va["Reload"]) ? $va["Reload"] : "0";

$hijri = new HijriDate($nHijriah); //Wajib ada
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Sholat Dan Murotal</title>
  <link type='text/css' rel='stylesheet' href='<?= $url ?>css.css'>
</head>

<body>
  <form action="<?= $url ?>" method="POST">
    <table style="width:100%">
      <tr>
        <td colspan="3" class="cellHeader">
          :: LOKASI
        </td>
      </tr>
      <tr>
        <td width="100px">Koordinat</td>
        <td width="5px">:</td>
        <td>
          <input type="text" name="cKoordinat" value="<?= $cKoordinat ?>" style="width:100%">
        </td>
      <tr>
        <td></td>
        <td width="5px"></td>
        <td>
          Format : Latitude, Longitude ( Copy Dari Google Map)
        </td>
      </tr>
      <tr>
        <td>Timezone</td>
        <td width="5px">:</td>
        <td>
          <input type="number" name="nTimeZone" value="<?= $nTimeZone ?>" class="numCfg"> GMT
        </td>
      </tr>
      <tr>
        <td>Tgl. Hijriah</td>
        <td width="5px">:</td>
        <td>
          <input type="number" name="nHijriah" value="<?= $nHijriah ?>" class="numCfg"> Hari -
          (<?= $hijri->get_date() ?>) <a href="<?= $url ?>calendar/" target="_new">Open Calendar</a>
        </td>
      </tr>
      <tr>
        <td>Lama Adzan</td>
        <td width="5px">:</td>
        <td>
          <input type="number" name="nLamaAdzan" value="<?= $nLamaAdzan ?>" class="numCfg"> Menit
        </td>
      </tr>

      <tr>
        <td colspan="3" class="cellHeader">
          :: ATUR WAKTU SHOLAT ( MENIT )
        </td>
      </tr>
      <tr>
        <td></td>
        <td width="5px"></td>
        <td>Adzan / Iqomah / Volume
        </td>
      </tr>
      <tr>
        <td>Subuh</td>
        <td width="5px">:</td>
        <td>
          <input type="number" name="nSubuh" value="<?= $nSubuh ?>" class="numCfg"> /
          <input type="number" name="nSubuh_Iqomah" value="<?= $nSubuh_Iqomah ?>" class="numCfg"> /
          <input name="nSubuh_Volume" class="numCfg" type="number" min="1" max="100" value="<?= $nSubuh_Volume ?>"> %
        </td>
      </tr>
      <tr>
        <td>Dzuhur</td>
        <td>:</td>
        <td>
          <input type="number" name="nDzuhur" value="<?= $nDzuhur ?>" class="numCfg"> /
          <input type="number" name="nDzuhur_Iqomah" value="<?= $nDzuhur_Iqomah ?>" class="numCfg"> /
          <input name="nDzuhur_Volume" class="numCfg" type="number" min="1" max="100" value="<?= $nDzuhur_Volume ?>"> %
        </td>
      </tr>
      <tr>
        <td>Ashar</td>
        <td>:</td>
        <td>
          <input type="number" name="nAshar" value="<?= $nAshar ?>" class="numCfg"> /
          <input type="number" name="nAshar_Iqomah" value="<?= $nAshar_Iqomah ?>" class="numCfg"> /
          <input name="nAshar_Volume" class="numCfg" type="number" min="1" max="100" value="<?= $nAshar_Volume ?>"> %
        </td>
      </tr>
      <tr>
        <td>Maghrib</td>
        <td>:</td>
        <td>
          <input type="number" name="nMaghrib" value="<?= $nMaghrib ?>" class="numCfg"> /
          <input type="number" name="nMaghrib_Iqomah" value="<?= $nMaghrib_Iqomah ?>" class="numCfg"> /
          <input name="nMaghrib_Volume" class="numCfg" type="number" min="1" max="100" value="<?= $nMaghrib_Volume ?>"> %
        </td>
      </tr>
      <tr>
        <td>Isya</td>
        <td>:</td>
        <td>
          <input type="number" name="nIsya" value="<?= $nIsya ?>" class="numCfg"> /
          <input type="number" name="nIsya_Iqomah" value="<?= $nIsya_Iqomah ?>" class="numCfg"> /
          <input name="nIsya_Volume" class="numCfg" type="number" min="1" max="100" value="<?= $nIsya_Volume ?>"> %
        </td>
      </tr>
      <tr>
        <td colspan="3" class="cellHeader">
          :: MUROTAL
        </td>
      </tr>
      <tr>
        <td>Volume</td>
        <td width="5px">:</td>
        <td>
          <input name="nMurotal_Volume" class="numCfg" type="number" min="1" max="100" value="<?= $nMurotal_Volume ?>"> %
        </td>
      </tr>
      <tr>
        <td colspan="3">Mematikan Murotal Malam Hari</td>
      </tr>
      <tr>
        <td colspan="3">
          <input type="radio" name="nMatikanMurotalMalam" value="Y" <?php if ($lYa) echo ("checked=true") ?>> Ya
          <input type="radio" name="nMatikanMurotalMalam" value="T" <?php if (!$lYa) echo ("checked=true") ?>> Tidak
        </td>
      </tr>
      <tr>
        <td colspan="3" class="cellHeader">
          :: Reload Aplikasi
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <input type="radio" name="Reload" value="1" <?php if ($Reload == "1") echo ("checked=true") ?>> Ya
          <input type="radio" name="Reload" value="0" <?php if ($Reload == "0") echo ("checked=true") ?>> Tidak
        </td>
      </tr>
      <tr>
        <td colspan="3" class="cellHeader" style="text-align: center;">
          <input type="hidden" name="nSave">
          <input type="button" value="Simpan" name="cmdSave" onclick="submit();">
        </td>
      </tr>
    </table>
  </form>
</body>

</html>