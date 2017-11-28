<?php

$id = uniqid();
file_put_contents("tmp/".$id.".svg", file_get_contents("php://input"));
echo("/svg2pdf/tmp/".$id.".svg");
?>
