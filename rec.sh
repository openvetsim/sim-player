#!/bin/bash
DATE=`date '+%Y-%m-%d_%H:%M:%S'`
DIR=/var/www/html/player
F1_1=${DIR}/${DATE}_scr.mkv
F2_1=${DIR}/${DATE}_v1.mkv
F3_1=${DIR}/${DATE}_v2.mkv
F4_1=${DIR}/${DATE}_a1.mp3
F5_1=${DIR}/${DATE}_a2.mp3
F6_1=${DIR}/${DATE}_v3.mkv

F1_2=${DIR}/${DATE}_scr_h264.mp4
F2_2=${DIR}/${DATE}_v1_h264.mp4
F2_3=${DIR}/${DATE}_v2_h264.mp4
F2_4=${DIR}/${DATE}_v2.mkv
A2_1=${DIR}/${DATE}_a1.wav

REC_ARGS="-hide_banner -loglevel error -nostdin"
REC_AUDIO="-f alsa "
#REC_LOSSLESS="-framerate 25 -rtbufsize 1M -vcodec libx264 -crf 22 -preset ultrafast -pix_fmt yuv420p"
REC_SCREEN="-video_size 1600x900 -framerate 25 -f x11grab -i :0.0 -vcodec libx264 -pix_fmt yuv420p"
#REC_STD="-framerate 25 -rtbufsize 1M -vsync vfr -g 1 -pix_ -s 640x480"
#REC_STD="-framerate 25 -rtbufsize 1M -vsync vfr -pix_fmt yuv420p -s 640x480"
REC_STD="-f v4l2 -s 640x480 -pix_fmt yuv420p"

ADEV1="-i plughw:CARD=U0x46d0x8ad,DEV=0"
ADEV2="-i plughw:CARD=VX5000,DEV=0"
VOUTPUT="-vcodec libx264 -crf 23 -preset medium  -pix_fmt yuv420p"
AOUTPUT="-acodec libmp3lame"

#ffmpeg $REC_ARGS $REC_SCREEN    $F1_1 </dev/null >&1 &
#pid1=$!
#ffmpeg $REC_ARGS $REC_AUDIO $ADEV1 $F4_1 </dev/null >&1 &
#pid4=$!
#ffmpeg $REC_ARGS $REC_AUDIO $ADEV2 $F5_1 </dev/null >&1 &
#pid5=$!
#ffmpeg $REC_ARGS $REC_STD -i /dev/video0 $VOUTPUT $F2_1  </dev/null >&1 &
#pid2=$!
#ffmpeg $REC_ARGS $REC_STD -i /dev/video1 $VOUTPUT $F3_1  </dev/null >&1 &
#pid3=$!
ffmpeg $REC_ARGS $REC_STD -i /dev/video1 $VOUTPUT $F2_1   &
pid2=$!
echo $pid1 $pid2 $pid3 $pid4 $pid5

read -p "Press any key to stop recording... " -n1 -s
kill -SIGINT $pid1 $pid2 $pid3 $pid4 $pid5
echo Waiting for Recordings to finish
sleep 4
ls -l ${DIR}/${DATE}_*
#echo Converting Screen Stream to H.264
#./convert_to_h264.sh ${DATE}_scr
#echo Converting Camera Streams to H.264
#./convert_to_h264.sh ${DATE}_v1
#./convert_to_h264.sh ${DATE}_v2

echo Done
