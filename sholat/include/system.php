<?php

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
