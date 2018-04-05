#!/bin/bash

F1=/var/www/html/player/${1}.mkv
F2=/var/www/html/player/${1}.mp4
REC_ARGS="-hide_banner -loglevel error"

#echo ffmpeg -i $F1 -codec:v libx264 -crf 23 -preset medium -pix_fmt yuv420p -movflags +faststart $F2
#ffmpeg $REC_ARGS -i $F1 -codec:v libx264 -crf 23 -preset medium -c:a copy -pix_fmt yuv420p -movflags +faststart $F2
#mv $F1 $F2
ffmpeg $REC_ARGS -i $F1 -vcodec copy -acodec aac $F2
