# PHP运算符的坑
```
	$a = 3;
	if ($a = 5) {
	    $a++;
	}
	var_dump($a);// (int)6

	编码习惯 对于数字来说 最好把它放前面写成 5 == $a 这样即使你写成了 5 = $a 此时会报错 不会是隐式的赋值
```

```
	<?php
		echo -10%3;
	?>

因为-的优先级比%求余的优先级低，也就是-(10%3)
```

```
	//file1.php
	<?php
		$a = '123';
	?>
	//file2.php
	<?php
		echo include('file1.php');
	?>

	因include()也是一个函数，有返回值。在成功时返回1，失败时返回错误信息。如果被包含的文件有return，则inculde()成功时返回该文件的返回值。
```

```
	<?php
		$count = 5;
		function get_count() {
		    static $count = 0;
		    return $count++;
		}
		++$count;
		get_count();
		echo get_count();
	?>

	因static count，所以只在第一次调用get_count 
 的时候对count赋值为0，第二次再进来这个函数，则不会第二次赋值。其次就是return count++和return++count了，前者先返回，后者先++再返回。

```

```
	<?php
	$arr = array(0 =>1,'aa' => 2,3,4);
	foreach($arr as $key => $val){
	    print($key == 'aa' ? 5 : $val);
	}
	?>

	因遍历数组第一次的时候，key和aa的比较实际就是0和aa的比较，一个是int一个是string，这个时候会转换类型，将字符串转换为数字再与数字比较。所以0=='aa'就是0==0，所以为true，也就是输出5。
```

```
	<?php
		echo count (false); //1
		$a = count ("567")  + count(null) + count(false); //2 
		echo $a;
		echo count(null) 0
	?>


	因count()的官方解释“If the parameter is not an array or not an object with implemented Countable interface, 1 will be returned.”.意思是说，如果不是数组或者对象的其他类型，返回1.那么这个值应该就是1+0+1了（boolen人家也是一个类型，虽然是讨厌的false）。NULL的意思是没有值
```

```
	<?php
		$arr = array(1,2,3);
		foreach($arr as &$val) {
		    $val += $val % 2 ? $val++ : $val--;
		}
		$val = 0;
		print(join('',$arr));  //330
	?>

	因foreach结束后的数组应该是array(3,3,7);最后给第三个元素赋值为0，所以就是330了。其中注意的是&，如果有&则是对原变量操作，如果没有，则是先生成一个新变量，然后给这个变量复制，最后操作的是这个新变量。

```

```
	<?php
		echo intval((0.1+0.7)*10);
	?>

	因0.1+0.7=0.8    0.810=8  所以转换成整数后还是8？错！因为0.1+0.7=0.8是浮点数，0.810在数学计算中是正整数8，可是在计算机中它仍然是浮点数8，什么叫浮点数8？每一个看起来像整数的浮点数，其实都不是整数，比如这个8，它其实只是7.9999循环，无限接近于8，转换成整数会舍弃小数部分，就是7

	解决办法: 采用bc系列函数
```

```
	ini_set('display_errors',0);
	$arr = array(1=>1,3=>3);
	$i = 2;
	$a = 'test' . isset($arr[$i]) ? $arr[$i] : $i;  //"你"

	因“."的优先级高于三元运算符"?:"
	真正的执行流程是这样的:
		$x = 2;//将2赋值给变量x
		echo $x == 2 ? '我' : $x == 1 ? '你' : '它'; //你
		//因为 == 的优先级比三元运算符高 所以转换成如下代码
		#echo true ? '我' : false ? '你' : '它';
		//由于三元运算符左结合的特性 所以如上代码等效于
		#echo (true ? '我' : false) ? '你' : '它';
		//先计算左边括号里的
		#echo '我' ? '你' : '它'; 
```