<?php
//setcookie('yyy','111');
//print_r($_COOKIE['yyy']);
//die;





//查找第一个不重复的字符
$a = 'mynamemyian';
function getFirst($str){
    $len  = strlen($str);
    for ($i=0;$i<$len;$i++) {
        for ($j = 1; $j<$len;$j++) {
            if ($str{$j}==$str{$i}) {
                break;
            }
        }
    }

}


/**
 *用123456随机排序，要求5不能在第3位，5和6不能相连
 *----------------------------------------
 * @param  string $str='123456';
 *----------------------------------------
 * @author 子衿阁
 * @return void
 */
function my_sort2($str) {
    while (true) {
        $i = str_shuffle($str);
        $a = stripos($i, '5');
        $b = stripos($i, '56');
        $c = stripos($i, '65');
        if ($a != 2 && $b === false && $c === false) {
            break;
        }
    }
    return $i;
}
$str = '123456';
//echo my_sort2($str);


// php实现var_dump功能  func_num_args   gettype
function reconstructDump() {
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
        reconstructDump($val);
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
reconstructDump(new MyClass(),$str);
//echo "\r\n";
$arr2   = array(
    "1"     => "Ddaddad",
    "one"   => array("two" => "Dddd" ),
    "three" => 1
);
//reconstructDump($arr2);
//reconstructDump(1,true,null);
//exit;

//两个int值 非常大， 相加之后超过了int值的范围 相加会输出错误的数据， 如何获得这个结果
//按位相加
function getAddNum($a,$b) {
    $stra = strrev($a);
    $strb = strrev($b);
    $alen = strlen($a);
    $blen = strlen($b);
    $maxlen = $alen > $blen ? $alen: $blen;
    $res = '';
    $next = 0;
    for ($i= 0; $i<$maxlen; $i++) {
        if (!isset($stra{$i})) {
            $stra{$i} = 0;
        }
        if (!isset($strb{$i})) {
            $strb{$i} = 0;
        }
        $j = $stra{$i} + $strb{$i} + $next;
        if ($j>=10)
        {
            $res .= $j%10;
            $next = 1;
        }
        else {
            $res .= $j;
            $next = 0;
        }
//        echo $res.'<br/>';
    }
    return strrev($res);
}
//echo getAddNum(5689,321);

$number = array(1,2,6,7,8,9,13,19,20,44);
function getSec($num) {
    $res = '';
    $len = count($num);
    $j = 0;
    for ($i=0;$i<count($num);$i++){
        $end = $num[$len-1];
        $n = ($num[$i]-1)*2+1;
        echo '$n的值为：'.$n.'<br/>';
        $j += $n;
        $k = $j-1;
        echo $j.'<br/>';
        if($j<=$len){
            $res .= $num[$k].',';
        }
        else{
            $res.=$end;
            break;
        }
    }
    return $res;
}
//echo getSec($number);
//echo 88;die;



//打印杨辉三角
function yanghui($len)
{
    for ($i=0;$i<$len;$i++){
        $array[$i][0] = 1;
        $array[$i][$i] = 1;
    }
    for ($i=2;$i<$len;$i++){
        for ($j=1;$j<$i;$j++){
            $array[$i][$j] = $array[$i-1][$j-1] + $array[$i-1][$j];
        }
    }
    //输出
    for ($i=0;$i<$len;$i++){
        for ($j=0;$j<=$i;$j++){
            echo $array[$i][$j].' ';
        }
        echo PHP_EOL;
    }
}
//echo yanghui(8);die;

//求斐波那契数列 第N列的值  1 2 3 5 8 13 N
function getFei($n){
    $arr[0] = 1;
    $arr[1] = 2;
    for ($i=2;$i<$n;$i++){
        $arr[$i] =  $arr[$i-1] + $arr[$i-2];
    }
    return $arr;
}
//print_r(getFei(8));

//写一个函数 实现字符串的翻转
function getRev($str){
    $len = mb_strlen($str,'utf-8');
    $res = '';
    for ($i=1;$i<=$len;$i++){
        $res .= mb_substr($str,-$i,1,'utf-8');
    }
    return $res;
}
//echo getRev('hell李雪');

$numbers = array(1,2,3);
foreach ($numbers as &$number){
    $number *= $number;
}
//var_dump($numbers);die;
$number = 0;
//echo $numbers[0],$numbers[1],$numbers[2];


//$tmp = 0 == 'a' ?1:2;
//echo $tmp;

$a3 = array(array(array(5, 55), 4, 444), 2, 7, 6, 8, array("w", "d", array(3, 2, "a"), "s")); //多维不规则数组
//var_dump($a3);
function hello($arr)
{
    foreach ($arr as $key => $value) {
        if (is_array($arr)) {
            hello($arr);
        } else {
            echo $key .'='.$value;
        }
    }
}



$arr = [1,2,3,4,5,6,7,8,9,10];
//$m 数猴子， 每次数到M只 剔除 ，求最后剔除的数值
//测试得到猴王、 可能会无限循环、不成功
function getMaxNum($arr,$m){
    $n = count($arr);
    while ($n>1){
        if ($m<=$n-1) {
            unset($arr[$m-1]);
        }
        else{
            $i = $m/$n;
            unset($arr[$i-1]);
        }
        $n = count($arr);
    }
    return $arr;

}

/**
 * @param $n
 * @return 自己测试函数。未能正确输出
 */
function getKing($n){
    $arr = range(1,5);
    $len = count($arr);
    $j = 0; // 记录猴子的报数 从1开始报数、 报道到数子n unset $j

    for($i = 0; $i< $len;$i++) {
        $j++;
        echo "j=".$j.'<br/>';
        echo "n=".$n.'<br/>';
        echo "i=".$i.'<br/>';

        if ($n == $j) {
            unset($arr[$i]);
            $j = 0;
            $len--; //数组长度减1
        }
    }

    return $arr;
}
//var_dump(getKing(3));


/**
 * @param int $n 开始时的猴子数量
 * @param int $m 报道的最后一个数
 *(报到这个数的猴子被淘汰,然后下一个猴子重新从①开始报数)
 * @return int 猴子的初始编号
 */

//测试 数组成员id不连续 函数是否成功
function monkeySelectKing($n,$m)
{
    //猴子的初始数量不能小于2
    if($n<2)
    {
        return false;
    }

    $arr=range(1,$n);
//将猴子分到一个数组里, 数组的值对应猴子的初始编号
    $unsetNum=0;
//定义一个变量,记录猴子的报数

    for($i = 2; $i <=$n*$m ; $i++)
//总的循环次数不知道怎么计算,
    {
//不过因为循环中设置了return,所以$m*$len效率还可以
        foreach ($arr as $k => $v)
        {
            $unsetNum++; //每到一个猴子, 猴子报数+1

        //当猴子的报数等于淘汰的数字时:淘汰猴子(删除数组元素)
        //报数归0(下一个猴子从1开始数)
            if ($unsetNum==$m)
            {
                echo "<pre>";//打开注释,可以看到具体的淘汰过程
                print_r($arr);
                unset($arr[$k]);
                //淘汰猴子 
                $unsetNum=0;
                //报数归零
                if (count($arr)==1)
                //判断数组的长度, 如果只剩一个猴子, 返回它的值
                {
                    return reset($arr);
                }
            }
        }
    }
}
//var_dump(monkeySelectKing(10, 5));die;

//另一种猴王算法。不理解
function yuesefu($n,$m) {
    $r=0;
    for($i=2; $i<=$n; $i++) {

        $r=($r+$m)%$i;
//        echo $r.'<br/>';
    }
    return $r+1;
}
//print_r(yuesefu(3,3));

//echo getMaxNum($arr,8);die;
//hello($a3);

//遍历多维数组
$a=array(
    'fruits'=>array(
        'a'=>'orange',
        'b'=>'grape',
        'c'=>'apple'
    ),
    'numbers'=>array(1,2,3,4,5,6),
    'holes'=>array('first',5=>'second','third'),
    'car'=> array(
        'BMW'=> array(
            'BMW1',
            'BMW2'
        ),
    ),
);

var_dump($a);die;

function MulitarraytoSingle($array,$str){
    if(is_array($array)){
        foreach ($array as $key=>$value )
        {
            if(is_array($value)){
                 return MulitarraytoSingle($value, '');
            }
            else{
                echo $str .=  $key.'=>'.$value.'<br/>';
            }
        }
    }
//    return $str;
}

//var_dump(MulitarraytoSingle($a, $str=''));



function number_alphabet($str)
{
    $number = preg_split('/[a-z]+/', $str, -1, PREG_SPLIT_NO_EMPTY);
    $alphabet = preg_split('/\d+/', $str, -1, PREG_SPLIT_NO_EMPTY);
    $n = count($number);
    for ($i = 0; $i < $n; $i++) {
        echo $number[$i] . ':' . $alphabet[$i] . '</br>';
    }
}
$str = '1a3bb44a2ac';
//number_alphabet($str);//1:a 3:bb 44:a 2:ac



/**
 * 求n内的质数
 * @param int $n
 * @return array
 */
function get_prime($n)
{
    $prime = array(2);//2为质数

    for ($i = 3; $i <= $n; $i += 2) {//偶数不是质数，步长可以加大
        $sqrt = intval(sqrt($i));//求根号n

        for ($j = 3; $j <= $sqrt; $j += 2) {//i是奇数，当然不能被偶数整除，步长也可以加大。
            if ($i % $j == 0) {
                break;
            }
        }

        if ($j > $sqrt) {
            array_push($prime, $i);
        }
    }

    return $prime;
}

//print_r(getPrime(1000));


/**
 * 获取最大的连续和
 * @param  array $arr
 * @return int
 */
function max_sum_array($arr)
{
    $currSum = 0;
    $maxSum = 0;//数组元素全为负的情况，返回最大数

    $n = count($arr);

    for ($i = 0; $i < $n; $i++) {
        if ($currSum >= 0) {
            $currSum += $arr[$i];
        } else {
            $currSum = $arr[$i];
        }
    }

    if ($currSum > $maxSum) {
        $maxSum = $currSum;
    }

    return $maxSum;
}

$arr = [-9,-7,-1,4,5,7,8];
echo max_sum_array($arr);
?>