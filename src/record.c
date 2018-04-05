#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>
#include <time.h>
#include <signal.h>
#include <fcntl.h>
#include <sys/stat.h>

#define DEST_DIR "/var/www/html/player"
#define F1 "_scr.mkv"
#define F1_2 "_scr.mp4"
#define F4 "_a1.mp3"
#define F5 "_a2.mp3"
#define F6 "_a3.mp3"

#define AOUTPUT "-acodec libmp3lame"

char dateString[512];
char cmdString[512];
void startRecord(int which );
void startConvert(int which );
void showDetails(int which );
int pidList[32] = { 0, };

int
main() {

  int cc;
  time_t rawtime;
  struct tm *timeinfo;
  int i;
  int sts;
  
  time(&rawtime );
  timeinfo = localtime ( &rawtime);
  cc = strftime(dateString, 512, "%F_%T", timeinfo );

  printf("The time is %s\n", dateString );
  for ( i = 1 ; i <= 6 ; i++ )
  {
    cc = fork();
    if ( cc == 0 )
      {
	startRecord(i );
	printf("Start Record Retruns\n" );
	exit ( 0 );
	// Does not actually return
	break;
      }
    else
      {
	printf("PID %d\n", cc );
	pidList[i] = cc;
      }
    
  }
  
  printf("Strike any key ENTER to stop recordings\n" );
  getchar();

  printf("Stopping\n" );
  // Stop the recordings
  for ( i = 1 ; i <= 6 ; i++ )
    {
      printf("%d\n", pidList[i] );
      if ( pidList[i] > 0)
	{
	  sts = kill(pidList[i], SIGINT );
	  if ( sts )
	    {
	      perror("kill" );
	    }
	}
    }
  sleep( 4 );
  printf("Starting Conversions\n" );
  // Do the conversions
  for ( i = 1 ; i <= 4 ; i++ )
  {
    cc = fork();
    if ( cc == 0 )
    {
      startConvert(i );
      // Does not actually return
      break;
    }
    pidList[i] = cc;
  }
  for ( i = 1 ; i <= 4 ; i++ )
    {
      if ( pidList[i] > 0 )
	{
	  while ( ( sts = kill(pidList[i], 0 ) ) > 0 )
	    {
	      sleep(1 );
	    }
	}
    }
  printf("Done\n" );
  sleep(1);
  for ( i = 1 ; i <= 5 ; i++ )
    {
      showDetails(i );
    }
  return 0;
}

void
startRecord(int which)
{
  char outputFname[256];
  char device[256];
  int sts;
  int video;
  int fd;

  fd = open("/dev/null", O_WRONLY | O_CREAT, 0666 );
  dup2(fd, 1 );

  switch ( which )
    {
    case 1:
      sprintf(outputFname, "%s/%s%s", DEST_DIR, dateString, F1 );
      sts = execl("/usr/bin/ffmpeg",
		  "-hide_banner",
		  "-loglevel", "error",
		  "-nostdin",
		  "-video_size", "1600x900",
		  "-framerate", "25",
		  "-f", "x11grab",
		  "-i", ":0.0",
		  "-vcodec", "libx264",
		  "-pix_fmt", "yuv420p",
		  outputFname,
		  NULL );
      break;
    case 2: case 3: case 4: 
      video = which - 2;
      sprintf(outputFname, "%s/%s_v%d.mkv", DEST_DIR, dateString, video );
      sprintf(device, "/dev/video%d", video );
      sts = execl("/usr/bin/ffmpeg",
		  "-hide_banner",
		  "-loglevel", "error",
		  "-nostdin",
		  "-s", "640x480",
		  "-framerate", "25",
		  "-f", "v4l2",
		  "-pix_fmt", "mjpeg", // "yuv420p",
		  "-i", device,
		  "-vcodec", "libx264",
		  "-crf", "23",
		  "-preset", "medium",
		  "-pix_fmt", "yuv420p",
		  outputFname,
		  NULL );
      break;
      
    case 5:
      sprintf(outputFname, "%s/%s%s", DEST_DIR, dateString, F4 );
      sts = execl("/usr/bin/ffmpeg",
		  "-hide_banner",
		  "-loglevel", "error",
		  "-nostdin",
		  "-f", "alsa",
		  "-i", "plughw:CARD=U0x46d0x8ad,DEV=0",
		  outputFname,
		  NULL );
      break;
    case 6:
      sprintf(outputFname, "%s/%s%s", DEST_DIR, dateString, F5 );
      sts = execl("/usr/bin/ffmpeg",
		  "-hide_banner",
		  "-loglevel", "error",
		  "-nostdin",
		  "-f", "alsa",
		  "-i", "plughw:CARD=VX5000,DEV=0",
		  outputFname,
		  NULL );
    
      break;
    case 7:
      sprintf(outputFname, "%s/%s%s", DEST_DIR, dateString, F6 );
      sts = execl("/usr/bin/ffmpeg",
		  "-hide_banner",
		  "-loglevel", "error",
		  "-nostdin",
		  "-f", "alsa",
		  "-i", "plughw:CARD=0x46d0x825,DEV=0",
		  outputFname,
		  NULL );
    
      break;
    
    }
  close(fd);
  if ( sts < 0 )
    {
      perror("execl" );
    }
}

void
startConvert(int which)
{
  char outputFname[256];
  char inputFname[256];
  int sts;
  int video;
  int fd;
  
  switch ( which )
    {
    case 1:
      sprintf(outputFname, "%s/%s%s", DEST_DIR, dateString, F1_2 );
      sprintf(inputFname, "%s/%s%s", DEST_DIR, dateString, F1 );
      break;

    case 2: case 3: case 4:
      video = which - 2;
      sprintf(outputFname, "%s/%s_v%d.mp4", DEST_DIR, dateString, video );
      sprintf(inputFname, "%s/%s_v%d.mkv", DEST_DIR, dateString, video );
      break;
    }
  fd = open("/dev/null", O_WRONLY | O_CREAT, 0666 );
  dup2(fd, 1 );
  sts = execl("/usr/bin/ffmpeg", "-hide_banner", "-loglevel", "error", "-nostdin",
	      "-i", inputFname,
	      "-vcodec", "copy",
	      "-acodec", "aac",
	      outputFname,
	      NULL );
  close(fd);
}

void
showDetails(int which )
{
  char Fname[128];
  char command[128];
  char buf[512];
  int sts;
  FILE *fp;
  
  switch ( which )
    {
    case 1:
      sprintf(Fname, "%s/%s%s", DEST_DIR, dateString, F1_2 );
      break;
    case 2:
      sprintf(Fname, "%s/%s%s", DEST_DIR, dateString, F1_2 );
      break;
    case 3:
      sprintf(Fname, "%s/%s%s", DEST_DIR, dateString, F1_2 );
      break;
    case 4:
      sprintf(Fname, "%s/%s%s", DEST_DIR, dateString, F1_2 );
      break;
    case 5:
      sprintf(Fname, "%s/%s%s", DEST_DIR, dateString, F1_2 );
      break;
    }
  sprintf(command, "ffprobe -loglevel error -hide_banner -show_entries format=duration %s", Fname );
  
  fp = popen(command, "r" );
  while ( fgets(buf, 512, fp ) != NULL )
    {
      printf("%s", buf );
    }
  pclose(fp);
} 
