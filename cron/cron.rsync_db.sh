#!/bin/bash

#find absolute path of this file, and cd to location dir
SOURCE="${BASH_SOURCE[0]}"
DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
cd $DIR

#run php
php -f cron.rsync_sb.php member
php -f cron.rsync_sb.php music_tool
php -f cron.rsync_sb.php news
php -f cron.rsync_sb.php news_type
php -f cron.rsync_sb.php sign
php -f cron.rsync_sb.php talent_verify
php -f cron.rsync_sb.php upload_file
php -f cron.rsync_sb.php user
php -f cron.rsync_sb.php works

exit
