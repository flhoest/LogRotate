<?php

	// ----------------------------------------------------------------------------------------------
	//                      LogRotate.php - (c) 2018 - Frederic Lhoest
	// ----------------------------------------------------------------------------------------------

	// This script rotates log files when reaching certain size. It compress it in tar gzip and rename it to $logFile.n
	// "n" is the rotation number.

	// If the maximum number of retention is reached, all file are renamed in the sequence.
	// Example with a retention of 3 :
	// file.log		-> file.log.1
	// file.log.1	-> file.log.2
	// file.log.2	-> file.log.3
	// file.log.3	-> deleted !
	//
	// Target environment is Linux

	// Original log file you need to rotate
	$logFile="mylog.log";

	// Retention (how many logs to keep)
	$logRetention=5;

	// Maximum size before rotating (in Kb, 1 Mb = 1024 Kb). This is the actual uncompress log size
	$maxSize="90";

	if(file_exists($logFile))
	{
		exec("ls -1 ".$logFile."*",$res);

		// If file size is above limit, we need to rotate
		if(filesize($logFile)>$maxSize)
		{
			// Determine what is the highest number before rotating
			// last line of the ls result indicates last file. If last section of the file name is a number,
			// we need to increment it and name the file with that increment

			$tmp=explode(".", $res[count($res)-1]);
			if(is_numeric($tmp[count($tmp)-1]))
			{
				print("Size criteria match!\n");
				// last section is numeric, then we need to increment for next file
				$ext=$tmp[count($tmp)-1]+1;
// 				print("-> ".$ext."\n");

				// if $ext = $logRetention then need to rotate all files. last one is deleted n-1 become n, etc ...

				if($ext>$logRetention)
				{
					print("Need to adjust retention and delete oldest file\n");
					exec("rm -f ".$logFile.".".($ext-1));
// 					print("rm -f ".$logFile.".".($ext-1));
					print("\n");
					for($i=$ext-1;$i>1;$i--)
					{
						exec("mv ".$logFile.".".($i-1)." ".$logFile.".".$i."\n");
// 						print("mv ".$logFile.".".($i-1)." ".$logFile.".".$i."\n");
					}
					exec("mv ".$logFile." ".$logFile.".1");
// 					print("mv ".$logFile." ".$logFile.".1");
				}
				else
				{
					// create file
					print("Creating next file in the loop : ".$ext."\n");
					$fileName=$logFile.".".$ext;
					exec("tar cvf ".$fileName." ".$logFile." > /dev/null",$res2);
				}

				// zero out new logfile since original has been archived

				print("Creating new empty log file\n");
				exec("rm -f ".$logFile,$res3);
				exec("touch ".$logFile,$res4);
			}
			else
			{
				print("Creating the first rotated log\n");
				// There is not numeric extention, create the first one .1
				$fileName=$logFile.".1";

				exec("tar cvf ".$fileName." ".$logFile." > /dev/null",$res5);

				// zero out new logfile since original has been archived

				exec("rm -f ".$logFile,$res6);
				exec("touch ".$logFile,$res7);
			}
			print("\n");
		}
		else
		{
			print("File size criteria does NOT match, exiting.\n");
		}
	}
	else
	{
		print("Log file ".$logFile." not found, nothing to do, exiting\n");
		exit();
	}

?>
