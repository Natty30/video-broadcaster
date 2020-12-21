ffmpeg -re -i bbb.mp4 -vcodec copy -loop -1 -c:a aac -b:a 160k -ar 44100 -strict -2 -f flv rtmp://a.rtmp.youtube.com/live2/96xc-hfz7-j5u4-8vby-25s3
