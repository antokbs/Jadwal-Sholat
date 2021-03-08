<?php
$vaProsess = exec("pgrep -l -f -a murotal.php");
print_r($vaProsess);
