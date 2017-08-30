#! /bin/bash
PATH_RAW="raw_data/Data.csv"
PATH_OUTPUT="json/data_explanation.json"

#### INIT VARIABLES ####
total_proyect=0
total_amount=0

proyect_indicators=(`cat $PATH_RAW | grep "i[0-9]1" | cut -f1 -d, | sort | uniq`)
echo "var data_explanation = {">$PATH_OUTPUT

for indicator in "${proyect_indicators[@]}";
  do
    total_proyect_str=`cat $PATH_RAW | grep "$indicator"| awk -v FS="," '{sum += $4} END{print sum}'`
    total_proyect=$(($total_proyect + $total_proyect_str));

  done
echo "\"total_proyect\":$total_proyect," >>$PATH_OUTPUT
proyect_indicators=(`cat $PATH_RAW | grep "i[0-9]3\"," | cut -f1 -d, | sort | uniq`)
for indicator in "${proyect_indicators[@]}";
  do
    echo $indicator
    total_proyect_str=`cat $PATH_RAW | grep "$indicator"| awk -v FS="," '{sum += $4} END{print sum}'`
    total_amount=$(($total_amount + $total_proyect_str));
  done

##FALTA PASAR A MILLONES DE PESOS
echo "\"total_amount\":$total_amount," >>$PATH_OUTPUT
echo "\"total_plataforms\":5,">>$PATH_OUTPUT
echo "};" >>$PATH_OUTPUT
