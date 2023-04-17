#!/bin/bash
# read and evaluate SML output received from EMH eHZ
 
# set serial device
INPUT_DEV="/dev/ttyUSB0"
 
#set $INPUT_DEV to 9600 8N1
stty -F $INPUT_DEV
1:0:8bd:0:3:1c:7f:15:4:5:1:0:11:13:1a:0:12:f:17:16:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0
 
SML_START_SEQUENCE="1B1B1B1B0101010176"
METER_OUTPUT__START_SEQUENCE=""
 
while [ "$METER_OUTPUT__START_SEQUENCE" != "$SML_START_SEQUENCE" ]
do
        METER_OUTPUT=`cat $INPUT_DEV 2>/dev/null | xxd -p -u -l 365`
        METER_OUTPUT__START_SEQUENCE=$(echo "${METER_OUTPUT:0:18}")
done
 
echo "Content-Type: text/html; charset=utf-8"
echo ""
echo ""
echo "<html>"
echo " <head>"
echo "   <title>Z&auml;hlerauswertung</title>"
echo "   <meta name=\"viewport\" content=\"width=device-width,
initial-scale=1.0, user-scalable=no\">"
echo " </head>"
echo " <body>"
 
echo " <h1> Z&auml;hlerst&auml;nde </h1>"
 
let METER_180=0x${METER_OUTPUT:390:10}
VALUE=$(echo "scale=2; $METER_180 / 10000" |bc)
echo " <tt>Bezug.......: " $VALUE "kWh</tt><br>"
 
let METER_180=0x${METER_OUTPUT:347:10}
VALUE=$(echo "scale=2; $METER_180 / 10000" |bc)
echo " <tt>Einspeisung.: " $VALUE "kWh</tt><br>"
 
let METER_180=0x${METER_OUTPUT:518:8}
VALUE=$(echo "scale=2; $METER_180 / 10" |bc)
echo " <tt>Wirkleistung: " $VALUE "W</tt>"
 
echo " </body>"
echo "</html>"