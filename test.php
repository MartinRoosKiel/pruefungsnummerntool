<?php
session_start();
include_once('sessionhelpers.inc.php');

echo "check get_brevet()<br>";
print_r(get_brevet("Benthien", "Torben"));
?>