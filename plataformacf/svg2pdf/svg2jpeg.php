<?php

$id = uniqid();
file_put_contents("tmp/".$id.".svg", file_get_contents("php://input"));
exec("rsvg-convert --background-color=white  -f jpg -o tmp/".$id.".jpg tmp/".$id.".svg");
echo("/svg2pdf/tmp/".$id.".jpg");
?>
