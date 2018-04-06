#! /bin/bash
PATH_RAW="raw_data/Data.csv"
PATH_OUTPUT="bucket/json/partition/"
INDICADORES=("i111" "i11" "i121" "i12" "i131" "i13" "i14" "i15" "i16" "i17" "i181" "i18" "i211" "i21" "i221" "i22" "i231" "i23" "i24" "i25" "i261" "i26" "i27" "i311" "i31" "i321" "i32" "i331" "i33" "i34" "i351" "i35" "i36" "i411" "i41" "i421" "i42" "i431" "i43" "i44" "i45" "i461" "i46" "i47")

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
