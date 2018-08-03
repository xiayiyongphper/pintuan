#/bin/bash
cur_dir=$(cd "$(dirname "$0")"; pwd)
cd $cur_dir
cd ..
echo '目录：'`pwd`',svn update'
svn up
cd framework
echo '目录：'`pwd`',svn update'
svn up