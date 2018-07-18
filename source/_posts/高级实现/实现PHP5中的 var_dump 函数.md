# 实现PHP5中的 var_dump 函数

```
    function mydump() {
    $args   = func_num_args();
    for($i = 0;$i < $args; $i ++) {
        $param = func_get_arg($i);
        switch(gettype($param)) {
            case 'NULL' :
                echo 'NULL';
                break;
            case 'boolean' :
                echo ($param ? 'bool(true)' : 'bool(false)');
                break;
            case 'integer' :
                echo "int($param)";
                break;
            case 'double' :
                echo "float($param)";
                break;
            case 'string' :
                dumpString($param);
                break;
            case 'array' :
                dumpArr($param);
                break;
            case 'object' :
                dumpObj($param);
                break;
            case 'resource' :
                echo 'resource';
                break;
            default :
                echo 'UNKNOWN TYPE';
                break;
        }
    }
}


function dumpString($param) {
    $str = sprintf("string(%d) %s",strlen($param),$param);
    echo $str;
}

function dumpArr($param) {
    $len = count($param);
    echo "array($len) {\r\n";
    foreach($param as $key=>$val) {
        if(is_array($val)) {
            dumpArr($val);
        } else {
            echo sprintf('["%s"] => %s(%s)',$key,gettype($val),$val);
        }
    }
    echo "}\r\n";
}

function dumpObj($param) {
    $className = get_class($param);
    $reflect = new ReflectionClass($param);
    $prop = $reflect->getDefaultProperties();
    echo sprintf("Object %s #1(%d) {\r\n",$className,count($prop));
    foreach($prop as $key=>$val) {
        echo "[\"$key\"] => ";
        mydump($val);
    }
    echo "}";
}

class MyClass
{
    protected $_name;
    function test()
    {
        echo "hello";
    }
}

$str    = "test";
mydump(new MyClass(),$str);
echo "\r\n";
$arr2   = array(
    "1"     => "Ddaddad",
    "one"   => array("two" => "Dddd" ),
    "three" => 1
);
mydump($arr2);
mydump(1,true,null);
```




