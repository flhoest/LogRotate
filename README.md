# LogRotate
Rotate logs in a Linux file system

I was struggling to rotate logs for a specific application and I wanted to mimic the Linux system log rotation. Hence the creation of this little script that can be schedule on the frequency that match your application the most.

This is an old project but I wanted to share it with the communicty since it is in production for few years now.

Simply edit the script and edit the various configuration variables such as : 

```
// Original log file you need to rotate
$logFile="mylog.log";
// Retention (how many logs to keep)
$logRetention=5;
// Maximum size before rotating (in Kb, 1 Mb = 1024 Kb). This is the actual uncompress log size
$maxSize="90";
```
  
  And ... that's it !
