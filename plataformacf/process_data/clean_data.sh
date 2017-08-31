#! /bin/bash
PATH_RAW="raw_data/MiCochinitoData.csv"
PATH_OUTPUT="json/partition/"
INDICADORES=("i31" "i32" "i33" "i34" "i35" "i36" )

for indicador in "${INDICADORES[@]}"
do
  cat $PATH_RAW| awk -v FS="," -v indicador_copy="$indicador" '$1 ~ indicador_copy {print $0}' |sort -k 3,3 |sed "s/\"//g" > tmp.csv;
  cat  tmp.csv | \
  jq --slurp --raw-input --raw-output \
      'split("\n") | .[0:-1] | map(split(",")) |
          map({"id": .[0],
               "cve": .[1],
               "t": .[2],
               "valor": .[3],
               "m": .[4],
               "id2": .[5],
               "DesGeo": .[6],
                })' > $PATH_OUTPUT"$indicador.json"
done