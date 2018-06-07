#!/usr/bin/bash
function scan () {
   path=$1
      echo $path
  
      if [ -e $path -a -f $path ] 
      then
          scp $path root@118.24.47.193:/usr/local/nginx/html/Blog/source/_posts
      fi
 
      if [  -e $path -a -d $path ] 
      then
          for sub_path in `ls $path`
          do
            scan $sub_path
          done
      fi
}

#源代码目录
sourceCodeDir="/mnt/hgfs/www/Blog/"
cd $sourceCodeDir

#查看那些文件被修改过,去掉前面的空格,保存文件路径到文件lines.txt
git status|sed s/[[:space:]]//g > lines.txt
#去掉前面的#号
sed -i "s/#//g" lines.txt

lines=`cat lines.txt`
#rm -f lines.txt

#图片目录
imagePathList=$(cat lines.txt |grep -v "md"|grep "source")
imageDirNameArr=()
for imagePath in $imagePathList
do
  if [ ! -d "$sourceCodeDir$imagePath" ]
  then
    continue
  fi
  imagePath=(${imagePath//// })
  len=${#imagePath[@]}
  index=$[$len-1]
  imageDirName=${imagePath[$index]}
  imageDirNameArr[${#imageDirNameArr[@]}]=$imageDirName
done

imageDirNameLen=${#imageDirNameArr[@]}
./mkdir.exp ${imageDirNameArr[@]}

#上传图片
for imagePath in $imagePathList
do
  if [ ! -d "$sourceCodeDir$imagePath" ]
  then
    continue
  fi
  absolutePath=$sourceCodeDir$imagePath
  imagePath=(${imagePath//// })
  len=${#imagePath[@]}
  index=$[$len-1]
  imageDirName=${imagePath[$index]}
  imageDirNameArr[${#imageDirNameArr[@]}]=$imageDirName
  #scp -r $absolutePath root@118.24.47.193:/usr/local/nginx/html/Blog/source/_posts/$imageDirName
  scp -r $absolutePath root@118.24.47.193:/usr/local/nginx/html/Blog/source/_posts/
done

#遍历所有修改了的文件
for line in $lines
do

  #判断是否为md文件
  result=$(echo $line |grep "md")

  if [[ "$result" != "" ]] 
  then

    #去掉""号
    result=`echo $result|sed "s/\"//g"`

    #获取source第一次出现的位置
    index=`expr index $result "s"`
    #从source开始截取
    result=${result:$index-1}

    #不是以source开头,加上source/
    if [ $index -eq 0 ] 
    then
      dir="$sourceCodeDirsource/"
    else
      dir=${sourceCodeDir}
    fi

    path=${dir}${result}
    flag=`echo $path|grep "source"`


    if [[ "$flag" != "" ]] 
    then
      scan $path
    fi

  fi

done

#提交到github
git add source/*
git commit -m "CURD"
git push

#部署到博客服务器
./deploy.exp

