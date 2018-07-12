#!/bin/bash

#分支查看
function show()
{
	#type
	if test -z "$1" 
	then
	  echo "pelase select type: person or tff"
	  exit
	fi

	#project_name
	if test -z "$2" 
	then
	  echo "pelase input the project_name, example: usersvcs,account......"
	  exit
	fi

	cd /d/Dev/PHP/phpStudy/WWW 
	cd $1
	cd $2

	#branch_keywords
	if test -z "$3" 
	then
	  #echo "pelase input branch keywords, example: master,develop......"
	  #exit
	  branch_list=(`git branch|xargs|sed "s/*//"`)
	else
	  branch_list=(`git branch|grep -i -E "$3"|xargs|sed "s/*//"`)
	fi
	count=1
	for branch in  ${branch_list[@]} 
	do
	echo "$count. $branch"
	let "count+=1"
	done
}

#分支删除
function delete()
{
	#type
	if test -z "$1" 
	then
	  echo "pelase select type: person or tff"
	  exit
	fi

	#project_name
	if test -z "$2" 
	then
	  echo "pelase input the project_name, example: usersvcs,account......"
	  exit
	fi

    # master\|develop
	#branch_keywords
	if test -z "$3" 
	then
	  echo "pelase input branch keywords, example: master,develop......"
	  exit
	fi

	cd /d/Dev/PHP/phpStudy/WWW 
	cd $1
	cd $2

	branch_list=(`git branch|grep -i -E "$3"|xargs|sed "s/*//"`)

	echo "需要删除的分支如下: "
	count=1
	for branch in  ${branch_list[@]} 
	do
	echo "$count. $branch"
	let "count+=1"
	done

	git branch|grep -i -E "$3"|xargs git branch -D
}

PS3="请选择您要执行的操作："

select menu in "show" "delete" "quit"
do
    case $menu in
    show)
      echo "查看项目分支"
      show $1 $2 $3
      ;;
    delete)
	  echo "删除项目分支"	
	  read -p "你确定要执行分支删除操作吗？ 确认 yes 取消 no"
	  case $REPLY in 
	  yes)
	     echo "删除开始进行"
	     delete $1 $2 $3
	     echo "删除完成"
	     ;;	
	  no)
		echo "取消"
		 ;;
	  *)
        echo "输入无效"	
      	 ;;
      esac
      ;;
    quit)
      echo "退出完成 谢谢使用"
      break
      ;;
     *)
      echo "other"
      ;;
     esac
done

