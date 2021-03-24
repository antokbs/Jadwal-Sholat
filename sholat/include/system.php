<?php
require_once __DIR__ . "/hijridate.php";

function GetURL()
{
  $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]);
  if (substr($url, -1) !== "/") $url .= "/";

  return $url;
}

function GetData($cFile = "")
{
  return $_SERVER['DOCUMENT_ROOT'] . "/data/$cFile";
}

function GetConfig($cKey, $default = "")
{
  $cFileConfig = GetData("config.json");
  $vaData = [];
  if (is_file($cFileConfig)) {
    $vaData = json_decode(file_get_contents($cFileConfig), true);
  }

  $default = isset($vaData[$cKey]) ? $vaData[$cKey] : $default;
  return $default;
}

/*
Untuk Mendapat Status Murotal 
true = start
false = stop
*/
function GetStatusMurotal()
{
  $cFileData = GetData("status.txt");
  $lStatus = false;
  if (is_file($cFileData)) {
    $cData = file_get_contents($cFileData);
    if (strpos($cData, "start") !== false) $lStatus = true;
  }
  return $lStatus;
}
