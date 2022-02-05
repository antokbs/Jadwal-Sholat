<?php
require_once __DIR__ . "/../include/system.php";
$url = GetURL();
$cFileConfig = GetData("murotal.json");

if (isset($_POST["nSave"])) {
    file_put_contents($cFileConfig, json_encode($_POST));
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

<body onload="Body_Onload()">
    <form name="form1" action="<?= $url ?>" method="POST">
        <div style="width: 70%; float:left;">
            <table id="surah" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><input type="checkbox" onclick="checkall(this);"></th>
                        <th>Surat</th>
                        <th>Repeat</th>
                        <th>Volume</th>
                    </tr>
                </thead>
                <tbody id="bodytable">

                </tbody>
            </table>
        </div>
        <div style="width: 30%; float:right;">
            <input type="hidden" name="nSave">
            &nbsp;<input type="button" value="Simpan" name="cmdSave" onclick="submit();">
        </div>
    </form>
</body>

</html>