#!/usr/bin/env python
# -*- coding: utf-8 -*-
import json
import ast
import codecs
with open('json/estado_cve.json') as data_file:
        estados = json.load(data_file)
estados['0'] = {u'nombre':'No aplica', 'clave':'-'}

#Indicadores is a list
with open('json/cf_metadata.json') as data_file:
        indicadores = json.load(data_file)
#Transform to dict
dic_indicadores = {}
for indicador in indicadores['results']:
    dic_indicadores[indicador['Clave']] = indicador

allS = ""
with open('to_csv/raw_csv/all', 'r') as f:
  for line in f:
        if line.replace("\n",""):
            line_split = line.split(',')
            #0 -> year
            #1 -> Trimestre
            #2 -> Indicador
            #3 -> Categoria
            #4 -> Estado
            #5 -> Valor
            #6 -> Agregacion
            new_line = line_split[0] +"," #year
            new_line += line_split[1] +"," #trimestre
            new_line += dic_indicadores[line_split[2]]['Nombre_del_objetivo']+ "-" #nombre del objetivo
            new_line += dic_indicadores[line_split[2]]['Nombre_del_indicador']+ "," #nombre del indicador
            new_line += line_split[3] +"," #Categoria
            new_line += estados[line_split[4]]['nombre']+"," #Estado
            new_line += estados[line_split[4]]["clave"]+"," #Estado
            new_line += line_split[5] + ","
            new_line += "Estatal" if line_split[6] == "E" else "Nacional"
            new_line += "\n"
            allS += new_line
f = codecs.open('to_csv/raw_csv/tmp2', 'w', "utf-8")
f.write(allS)
f.close()
