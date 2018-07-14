---
title: 2018040203
date: 2018-04-02 23:55:07
tags:
- linux
- shell
categories:
- linux
- shell
---
# shell数组操作


---

<ul>
<li><a href="#define">定义</a></li>
<li><a href="#length">数组长度</a></li>
<li><a href="#scan">数组遍历</a></li>
<li><a href="#set">数组赋值</a></li>
<li><a href="#set">数组赋值</a></li>
<li><a href="#burst">数据切片</a></li>
<li><a href="#replace">数组元素替换</a></li>
<li><a href="#delete">数组元素删除</a></li>
<li><a href="#add">添加新元素到数组末尾</a></li>
<li><a href="#map">关联数组</a></li>
<li><a href="#index">获取所有键名</a></li>
</ul>

<h2 id="define">定义</h2>
    array_name=(
      刘泽明
      刘艳
      刘倩
      )
      
    names=(刘泽明 刘艳 刘倩)
    
    array_name[0]="刘泽明"
    array_name[1]="刘艳"
    array_name[2]="刘倩"
    
    array_name=([0]="刘泽明" [1]="刘艳" [2]="刘倩")
    
    str="刘泽明 刘艳 刘倩"
    array_name=($str)

<h2 id="length">数组长度</h2>

    names=(刘泽明 刘艳 刘倩)
    
    echo ${#names[*]}
    
    echo ${#names[@]}  

<h2 id="scan">数组遍历</h2>
    
    str="刘泽明 刘艳 刘倩"
    array_name=($str)
    
    for name in ${array_name[*]}
    do
        echo $name
    done
    
    for name in ${array_name[@]}
    do
        echo $name
    done
    
<h2 id="set">赋值</h2>

     names=(刘泽明 刘艳 刘倩)
     
     echo ${#names[*]}
     
    for name in ${names[@]}
    
    do
    
    echo $name
    
    done
    
    names[0]="邓琴琴"
    
    echo ${names[@]}

<h2 id="burst">数据切片</h2>
    array=(zero one two three four)
    
    echo $array #默认取数组第一个元素
    
    echo ${array[0]}
    
    echo ${array[@]}
    
    echo ${array[@]:1} #表示从索引1开始,取剩下的全部元素
    
    echo ${array[@]:0:3} #表示从索引0开始,取3个元素
    
    echo ${array[@]::4} #表示从索引0开始,取4个元素
    
    echo ${array[@]:(-2):2} #-1表示数组最后一个元素,2表示取两个元素
    
    new_array=(${array[@]:1:4}) #切片后组成新的数组
    
    echo ${new_array[@]}
    
<h2 id="replace">数组元素替换</h2>

    ${array[@]/x/y}     最小匹配替换，每个元素只替换一次
    
    ${array[@]//x/y}    最大匹配替换，每个元素可替换多次
    
    ${array[@]/x/}      最小匹配删除，只删除一个符合规定的元素
    
    ${array[@]//x/}     最大匹配删除，可删除多个符合规定的元素
    
            echo ${array[@]/e/E}       #zEro onE two thrEe four
            
            echo ${array[@]//e/E}      #zEro onE two thrEE four
            
            echo ${array[@]/e/}      #zro on two thre four
            
            echo ${array[@]//e/}      #zro on two thr four
            
    ${array[@]/#x/y}     从左往右匹配替换，只替换每个元素最左边的字符

    ${array[@]/%x/y}     从右往左匹配替换，只替换每个元素最右边的字符
            
            echo ${array[@]/#o/O}     #zero One two three four
            
            echo ${array[@]/%o/O}     #zerO one twO three four
    
<h2 id="delete">数组元素删除</h2>

    (1)删除整个数组元素
    
    array=(zero one two three four)
        
    echo ${array[@]}
        
    unset array[0]
        
    echo ${array[@]}
    
    (2)删除某个数组元素的一部分
     
     #  每个元素,从左向右进行最短匹配
     
     ## 每个元素,从左向右进行最长匹配
    
     %  每个元素,从右向左进行最短匹配   
    
     %% 每个元素,从右向左进行最长匹配
    
        list=(book food)
        
        echo ${list[@]#b*o}        #ok food
        
        echo ${list[@]##b*o}       #k food
        
        echo ${list[@]%o*d}        #book fo
        
        echo ${list[@]%%o*d}       #book f
    
<h2 id="add">添加新元素到数组末尾</h2>

    number=(1111 2222 3333)
    
    echo ${number[@]}
    
    number=(${number[@]} 4444)
    
    echo ${number[@]}

<h2 id="map">关联数组</h2>

    declare -A ass_arr  #必须申明为数组
    ass_arr["apple"]=12;
    ass_arr["orange"]=19
    echo ${ass_arr[@]}
    for t in "${ass_arr[@]}"
    do
      echo $t
    done
    
    #19 12
    #19
    #12

    echo "ass_arr[\"orange\"]="${ass_arr["orange"]} #ass_arr[“orange”]=19
    
<h2 id="index">获取所有键名</h2>

    echo ${!ass_arr[@]} #or ${!ass_arr[*]}
    echo ${!ass_arr[*]}

