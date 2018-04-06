#! /bin/sh
#
# create_all_csv.sh
# Copyright (C) 2017 mvilchis <mvilchis@mvilchis-hp>
#
# Distributed under terms of the MIT license.
#

#Create mega file
for file in $(ls bucket/to_csv/raw_csv/*.csv); do  tail -n +2 $file >> bucket/to_csv/raw_csv/all; done
