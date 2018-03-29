#/usr/bin/bash
cd /mnt/hgfs/www/Blog 
dir="/mnt/hgfs/www/Blog/"
git status|sed s/[[:space:]]//g >> lines.txt
sed -i "s/#//g" lines.txt
lines=`cat lines.txt`
rm -f lines.txt
for line in $lines
do
result=$(echo $line | grep "source/")
if [[ "$result" != "" ]]
then
  file=${dir}${result}
  echo $file
  if [ -e $file ]
  then
     scp $file root@118.24.47.193:/usr/local/nginx/html/Blog/source/_posts
  fi
fi
done
