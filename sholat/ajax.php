<?php
require_once __DIR__  . "/../include/system.php";
$_GET["cKey"]($_POST);

function CheckConfig()
{
  $cFileConfig = GetData("config.json");
  $cData = "";
  if (is_file($cFileConfig)) {
    $cData = file_get_contents($cFileConfig);

    $vaData = json_decode($cData, true);

    // Ambil Tanggal Hijriah Tambahkan Ke vaData biar dikirim ke client
    $hijri = new HijriDate(GetConfig("nHijriah", 0));
    $vaData['hijriah'] = $hijri->get_date();

    $cData = json_encode($vaData);

    // Reload Kita Ganti menjadi 0 biar tidak refresh terus menerus
    if (isset($vaData["Reload"]) && $vaData["Reload"] == "1") {
      $vaData["Reload"] = "0";
      file_put_contents($cFileConfig, json_encode($vaData));
    }
  }
  echo ($cData);
}

function SetVolume0($va)
{
  exec("amixer set Master,0 10%");
  echo ("Volume 10%");
}

function AdzanStart($va)
{
  // Naikan Volume Ke 100%
  $nVolume = GetConfig("n" . $va["Waktu"] . "_Volume", 100);
  UpdStatusMurotal("stop");
  exec("amixer set Master,0 $nVolume%");
  $cFile = strtolower($va["Waktu"]) == "subuh" ? "adzan-subuh.mp3" : "adzan.mp3";
  playMP3(GetData($cFile));
  echo ("Start Adzan {$va["Waktu"]} Volume : $nVolume %");
}

function playMP3($cFile)
{
  if (is_file($cFile)) {
    if (isset($_SERVER["HTTP_HOST"]) && strpos($_SERVER["HTTP_HOST"], "localhost") !== false) {
      echo ("File Ketemu : $cFile \n");
      shell_exec("cvlc --play-and-exit '$cFile' > /dev/null 2>/dev/null &");
    } else {
      echo ("File hanya bisa di akses dari localhost");
    }
  } else {
    echo ("File tidak Ketemu\n");
  }
}

function StopMurotal()
{
  UpdStatusMurotal("stop");
  echo ("Matikan Murotal");
}

function StartMurotal()
{
  $nVolume = GetConfig("nMurotal_Volume", 60);
  shell_exec("amixer set Master,0 $nVolume%");
  UpdStatusMurotal("start");
  echo ("Menjalankan Murotal");
}

function UpdStatusMurotal($cStatus)
{
  $cFile = GetData("status.txt");
  file_put_contents($cFile, $cStatus);
}

function SholatMalam()
{
  echo ("Waktunya Sholat Malam \n");
  IqomahStart();
}

function IqomahStart()
{
  UpdStatusMurotal("stop");
  exec("amixer set Master,0 100%");
  playMP3(GetData("beep60.mp3"));
  echo ("Iqomah");
}

function TerbitStart()
{
  UpdStatusMurotal("stop");
  exec("amixer set Master,0 100%");
  playMP3(GetData("beep10.mp3"));
  echo ("Matahari Terbit");
}

function CheckAyat()
{
  $cFile = GetData("ayat_aktif.txt");
  $cStatus = GetStatusMurotal() ? "start" : "stop";
  $vaData = [
    "ayat" => "",
    "status" => $cStatus
  ];
  if (is_file($cFile)) {
    $va = json_decode(file_get_contents($cFile), true);
    if (isset($va["ayat"])) {
      $cAyat = basename($va["ayat"]);
      $cAyat = explode(".", $cAyat)[0];
      $nSurat = intval(substr($cAyat, 0, 3));
      $cAyat = intval(substr($cAyat, 3));

      $va["ayat"] = dirname($va["ayat"]);
      $cSurat = basename($va["ayat"]);

      $vaData["ayat"] = "$cSurat - $cAyat / " . TotalAyat($nSurat);
    }
  }
  echo (json_encode($vaData));
}

function TotalAyat($nSurat)
{
  $vaSurat = [
    1 => 7, 2 => 286, 3 => 200, 4 => 176, 5 => 120, 6 => 165, 7 => 206, 8 => 75, 9 => 129, 10 => 109,
    11 => 123, 12 => 111, 13 => 43, 14 => 52, 15 => 99, 16 => 128, 17 => 111, 18 => 110, 19 => 98, 20 => 135,
    21 => 112, 22 => 78, 23 => 118, 24 => 64, 25 => 77, 26 => 227, 27 => 93, 28 => 88, 29 => 69, 30 => 60,
    31 => 34, 32 => 30, 33 => 73, 34 => 54, 35 => 45, 36 => 83, 37 => 182, 38 => 88, 39 => 75, 40 => 85,
    41 => 54, 42 => 53, 43 => 89, 44 => 59, 45 => 37, 46 => 35, 47 => 38, 48 => 29, 49 => 18, 50 => 45,
    51 => 60, 52 => 49, 53 => 62, 54 => 55, 55 => 78, 56 => 96, 57 => 29, 58 => 22, 59 => 24, 60 => 13,
    61 => 14, 62 => 11, 63 => 11, 64 => 18, 65 => 12, 66 => 12, 67 => 30, 68 => 52, 69 => 52, 70 => 44,
    71 => 28, 72 => 28, 73 => 20, 74 => 56, 75 => 40, 76 => 31, 77 => 50, 78 => 40, 79 => 46, 80 => 42,
    81 => 29, 82 => 19, 83 => 36, 84 => 25, 85 => 22, 86 => 17, 87 => 19, 88 => 26, 89 => 30, 90 => 20,
    91 => 15, 92 => 21, 93 => 11, 94 => 8, 95 => 8, 96 => 19, 97 => 5, 98 => 8, 99 => 8, 100 => 11,
    101 => 11, 102 => 8, 103 => 3, 104 => 9, 105 => 5, 106 => 4, 107 => 7, 108 => 3, 109 => 6, 110 => 3,
    111 => 5, 112 => 4, 113 => 5, 114 => 6
  ];

  return isset($vaSurat[$nSurat]) ? $vaSurat[$nSurat] : 0;
}
