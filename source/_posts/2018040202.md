---
title: 2018040202
date: 2018-04-02 23:53:33
tags:
- linux
- shell
categories:
- linux
- shell
---
# shell中判断字符串包含子字符串判断

<ul>
<li><a href="#grep">利用grep查找</a></li>
<li><a href="#match1">利用字符串运算符</a></li>
<li><a href="#match2">利用通配符</a></li>
<li><a href="#case">case in</a></li>
<li><a href="#replace">利用替换</a></li>
</ul>
---
<h2 id="grep">利用grep查找</h2>

    strA="long string"
    strB="string"
    result=$(echo $strA | grep "${strB}")
    if [[ "$result" != "" ]]
    then
      echo "包含"
    else
      echo "不包含"
    fi

<h2 id="match1">利用字符串运算符</h2>

    strA="helloworld"
    strB="low"
    if [[ $strA =~ $strB ]]
    then
      echo "包含"
    else
      echo "不包含"
    fi

<h2 id="match2">利用通配符</h2>

    A="helloworld"
    B="low"
    if [[ $A == *$B* ]]
    then
      echo "包含"
    else
      echo "不包含"
    fi

<h2 id="case">case in</h2>
    thisString="1 2 3 4 5" # 源字符串
    searchString="1 2" # 搜索字符串
    case $thisString in 
      *"$searchString"*) echo Enemy Spot ;;
      *) echo nope ;;
    esac

<h2 id="replace">利用替换</h2>
    STRING_A=$1
    STRING_B=$2
    if [[ ${STRING_A/${STRING_B}//} == $STRING_A ]]
      then
        ## is not substring.
        echo N
      else
        ## is substring.
        echo Y
     fi    
