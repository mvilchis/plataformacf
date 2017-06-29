<?php
$i = 0;

echo("<script>console.log('Naaaada');</script>");
#G$year=
#$trimestre=
#$indicador=
#$categoria=
#$estado=
#$valor=
#$nacional=
#
#$key_year=null;
#$key_trimestre=null;
#$key_categoria=null;
#$key_indicador=null;
#$key_estado=null;
#$key_nacional=null;
#$key_valor=null;
#
#
#$needles = array('2015');
#$results = array();
#$columns = array();
#if(($handle = fopen('raw_csv/i11.csv', 'r')) !== false) {
#    while(($data = fgetcsv($handle, 4096, ',')) !== false) {
#        if($i == 0)  {
#            // sets the key where column to search
#            $columns = $data;
#            $i++;
#            $manufacturer_key = array_search('t', $data);
#
#        } else {
#            foreach($needles as $needle) {
#                if(stripos($data[$manufacturer_key], $needle) !== false) {
#                    $results[] = $data;
#                }
#            }
#        }
#    }
#    fclose($handle);
#}
#
#array_unshift($results, $columns);
#
#echo '<pre>';
#print_r($results);
#echo '</pre>';
#
#$fp = fopen('file.csv', 'w');
#
#foreach ($results as $row) {
#    fputcsv($fp, $row);
#}
#
#fclose($fp);
?>
