<?php

$id = uniqid();
file_put_contents("tmp/".$id.".svg", file_get_contents("php://input"));
exec("rsvg-convert --background-color=white  -f png -o tmp/".$id.".png tmp/".$id.".svg");
echo("/svg2pdf/tmp/".$id.".png");
?>
