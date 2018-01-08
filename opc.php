<?php
//echo phpinfo();die;
//opcache_compile_file('opc.php'); //不需要用户访问，对opc.php预先生成缓存
//var_dump(opcache_is_script_cached('opc.php')); //检查文件opc.php是否已经缓存到文件中
//die();
//echo 89;die;

//opcache_reset(); //清空opcache缓存
//opcache_invalidate('test2.php', true) //清空某个文件的opcache缓存
//opcache_get_status 获取缓存的状态信息
// opcache_get_configuration 获取缓存配置信息


// 查找字符串中 第一个不重复的字符 ascii 思路
$str = "abakbdjfkw";
function getFirstStr($str){
    $arr = [];
    $hashTable = [];
    $len = strlen($str);
    for($i=0;$i<256;$i++) {
        $hashTable[$i] = 0;
    }

    for($i=0;$i<$len;$i++){
        $ascii = ord($str{$i});
//        $arr[];
        echo $ascii;
        echo "<br/>";
    }
}

getFirstStr($str);die;


//算法  1 1 2 3 5 8 13 21 求第N个数的值
function feibonaqie($n){
    if($n==0) {
        return 0;
    }
    if($n==1) {
        return 1;
    }
    return feibonaqie($n-1)+feibonaqie($n-2);
}

//动态规划思路
function feibonaqie2($n){
    $arr[0] = 0;
    $arr[1] = 1;
    for ($i=2;$i<=$n;$i++) {
        $arr[$i] = $arr[$i-1] + $arr[$i-2];
    }
    return $arr[$n];
}
//echo feibonaqie2(20);die;

$start_time = microtime(true);
//echo feibonaqie(20);
//echo "<br/>";
$end_time = microtime(true);
//echo  $end_time-$start_time;die;


function arr_foreach ($arr)
{
    if (!is_array ($arr))
    {
        return false;
    }

    foreach ($arr as $key => $val )
    {
        if (is_array ($val))
        {
            arr_foreach ($val);
        }
        else
        {
            echo $key.'=>'.$val.'<br/>';
        }
    }
}

$arr1=array(
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
//echo '<pre>';
//print_r($arr1);
//echo '<pre>';
//arr_foreach ($arr1);die;



//快速排序
//待排序数组
$arr=array(6,3,8,6,4,2,9,5,1);
//函数实现快速排序
function quick_sort($arr)
{
    //判断参数是否是一个数组
    if(!is_array($arr)) return false;
    //递归出口:数组长度为1，直接返回数组
    $length=count($arr);
    if($length<=1) return $arr;
    //数组元素有多个,则定义两个空数组
    $left=$right=array();
    //使用for循环进行遍历，把第一个元素当做比较的对象
    for($i=1;$i<$length;$i++)
    {
        //判断当前元素的大小
        if($arr[$i]<$arr[0]){
            $left[]=$arr[$i];
        }else{
            $right[]=$arr[$i];
        }
    }
    //递归调用
    $left=quick_sort($left);
    $right=quick_sort($right);
    //将所有的结果合并
    return array_merge($left,array($arr[0]),$right);


}
//调用
//echo "<pre>";
//var_dump(quick_sort($arr));die;



//冒泡排序
function arr_sort($arr){
    $len = count($arr);
    for($i = 0;$i<=$len;$i++){
        for($j=1;$j<$len-$i;$j++){
            if($arr[$j-1] < $arr[$j]){
                $tmp = $arr[$j];
                $arr[$j] = $arr[$j-1];
                $arr[$j-1] = $tmp;
            }
        }
    }
    return $arr;
}
$arr = array(10,2,8,28,3,121);
//var_dump(arr_sort($arr));die;

//
//function bubble_sort(&$arr){
//    for ($i=0,$len=count($arr); $i < $len; $i++) {
//        for ($j=1; $j < $len-$i; $j++) {
//            if ($arr[$j-1] > $arr[$j]) {
//                $temp = $arr[$j-1];
//                $arr[$j-1] = $arr[$j];
//                $arr[$j] = $temp;
//            }
//        }
//    }
//}
//


//二分查找
// 假设 数组是递增排序
function zheban($arr,$num){
    $start = 0;
    $end = count($arr)-1;
    while ($start<=$end) {
        $mid = floor(($start+$end)/2);
        if ($arr[$mid] == $num) {
            return $mid;
        }
        elseif ($arr[$mid]>$num) {
           $end = $mid-1;
        }else{
            $start = $mid+1;
        }
    }
    return false;
}
function binSearch($arr,$search){
    $height=count($arr)-1;
    $low=0;
    while($low<=$height){
        $mid=floor(($low+$height)/2);//获取中间数
        if($arr[$mid]==$search){
            return $mid;//返回
        }elseif($arr[$mid]<$search){//当中间值小于所查值时，则$mid左边的值都小于$search，此时要将$mid赋值给$low
            $low=$mid+1;
        }elseif($arr[$mid]>$search){//中间值大于所查值,则$mid右边的所有值都大于$search,此时要将$mid赋值给$height
            $height=$mid-1;
        }
    }
    return false;
}

//二分查找递归实现
function test($arr,$low,$height,$num) {
    if($low<=$height) {
        $mid = floor(($low+$height)/2);
        if($arr[$mid] == $num) {
            return $mid;
        }elseif($arr[$mid] > $num) {
            return test($arr,$low,$mid-1,$num);
        }else{
            return test($arr,$mid+1,$height,$num);
        }
    }
    return false;
}
function binSearch2($arr,$low,$height,$k){
    if($low<=$height){
        $mid=floor(($low+$height)/2);//获取中间数
        if($arr[$mid]==$k){
            return $mid;
        }elseif($arr[$mid]<$k){
            return binSearch2($arr,$mid+1,$height,$k);
        }elseif($arr[$mid]>$k){
            return binSearch2($arr,$low,$mid-1,$k);
        }
    }
    return -1;
}


$arr = [3,6,23,45,65,67,99,293];
//var_dump(test($arr,0,7,67));
//echo $_SERVER['PHP_SELF'];
//echo $_SERVER['HTTP_REFERER'];
//实现字符串翻转
function getRev($str,$encoding='utf-8'){
    $result = '';
    $len = mb_strlen($str);
    for($i=$len-1; $i>=0; $i--){
        $result .= mb_substr($str,$i,1,$encoding);
    }
    return $result;
}
//$string = 'OK你是正确的Ole';
//echo strlen($string);die;
//echo getRev($string);



//获取文件扩展名
$str = "https://www.baidu.com/dir/upload/aa.jpg";
function getExt($str){
    return strrchr($str,'.');
}
function getExt1($str) {
    $path = pathinfo($str);
//    var_dump($path);
    return $path['extension'];
}
function getExt2($str) {
    $arr = explode('.', $str);
    return array_pop($arr);
}
function getExt3($str) {
    $i = strrpos($str,'.');
    return substr($str,$i);
}
//echo getExt3($str);

function uc_test($str){
    $str = str_replace('_',' ',$str);
    $str = ucwords($str);
    return $str;
}
Function tests($str){
    $arr1=explode('_',$str);
    $str = implode(' ',$arr1);
    return ucwords($str);
}

//echo tests('open_door');

//echo $_SERVER['REQUEST_URI'];
//echo $_SERVER['REMOTE_ADDR'];

$card_num = 54;//牌数
function wash_card($card_num){
    $cards = $tmp = array();
    for($i = 0;$i < $card_num;$i++){
        $tmp[$i] = $i;
    }

    for($i = 0;$i < $card_num;$i++){
        $index = rand(0,$card_num-$i-1);
        $cards[$i] = $tmp[$index];
        unset($tmp[$index]);
        $tmp = array_values($tmp);
    }
    return $cards;
}
// 测试：
//print_r(wash_card($card_num));
class BaseClass {
    function __construct() {
        print "In BaseClass constructor\n";
    }
}

class SubClass extends BaseClass {
    function __construct() {
        parent::__construct();
        print "In SubClass constructor\n";
    }
}

class OtherSubClass extends BaseClass {
    // inherits BaseClass's constructor
}

// In BaseClass constructor
//$obj = new BaseClass();

// In BaseClass constructor
// In SubClass constructor
//$obj = new SubClass();

// In BaseClass constructor
//$obj = new OtherSubClass();

class MyDestructableClass {
    function __construct() {
        print "In constructor\n";
        $this->name = "MyDestructableClass";
    }

    function __destruct() {
        print "Destroying " . $this->name . "\n";
    }
}

//$obj = new MyDestructableClass();
function myfunction($v)
{
    return $v*=3;
}

$a=array(1,2,3,4,5);
//print_r(array_map("myfunction",$a));

//print_r(array_walk($a,"myfunction"));

//选择排序
function select_sort($arr) {
//实现思路 双重循环完成，外层控制轮数，当前的最小值。内层 控制的比较次数
    //$i 当前最小值的位置， 需要参与比较的元素
    for($i=0, $len=count($arr); $i<$len-1; $i++) {
        //先假设最小的值的位置
        $p = $i;
        //$j 当前都需要和哪些元素比较，$i 后边的。
        for($j=$i+1; $j<$len; $j++) {
            //$arr[$p] 是 当前已知的最小值
            if($arr[$p] > $arr[$j]) {
                //比较，发现更小的,记录下最小值的位置；并且在下次比较时，
                // 应该采用已知的最小值进行比较。
                $p = $j;
            }
        }
        //已经确定了当前的最小值的位置，保存到$p中。
        //如果发现 最小值的位置与当前假设的位置$i不同，则位置互换即可
        if($p != $i) {
            $tmp = $arr[$p];
            $arr[$p] = $arr[$i];
            $arr[$i] = $tmp;
        }
    }
    //返回最终结果
    return $arr;
}

function swap(array &$arr,$a,$b){
    $temp = $arr[$a];
    $arr[$a] = $arr[$b];
    $arr[$b] = $temp;
}

function InsertSort(array &$arr){
    $count = count($arr);
    //数组中第一个元素作为一个已经存在的有序表
    for($i = 1;$i < $count;$i ++){
        $temp = $arr[$i];      //设置哨兵
        for($j = $i - 1;$j >= 0 && $arr[$j] > $temp;$j --){
            $arr[$j + 1] = $arr[$j];       //记录后移
        }
        $arr[$j + 1] = $temp;      //插入到正确的位置
    }
}

$arr = array(9,1,5,8,3,7,4,6,2);
//InsertSort($arr);
//var_dump($arr);

//球从100米高度掉落，每次落地反跳原高度的一半，实现计算第N次落地 球经过的总距离

function totalDis($n){
    $height = 100;
    $sum = 0;
    $sum += $height;
    for($i=2;$i<=$n;$i++){
        $height = $height/2;
        $sum += $height*2;
    }
    return $sum;
}

//echo totalDis(2);

$str = 'test a is b';
//echo $str{4};

function revStr($str) {
    $newStr = implode(' ', array_map("strrev",explode(' ',$str)));
    $len = strlen($newStr);
    for ($i=0;$i<$len;$i++){
        $newStr{$i} = ord($str{$i})<95? strtoupper($newStr{$i}) : strtolower($newStr{$i});
    }
    return $newStr;
}

$str = "this is an Apple eBay";
//print_r(revStr($str));

//100元 兑换为 10 5 1元 要求每种对法必须包含 10 5 1 ，算出总共有多少种兑法

function duiHuan(){
    $sum = 0;
    //最少包含一个10 最多包含9个10
    for($i=1;$i<=9;$i++){
        //当有$i个10元的时候 可以有不超过$k个5元
        $k = (100-10*$i)/5;
        for ($j=1;$j<=$k;$j++){
            $sum++;
        }
    }
    return $sum;
}

//echo duiHuan();

$keywords = preg_split("/[\s,]+/", "hypertext language, programming");
//print_r($keywords);

$str = 'hypertext language programming';
$chars = preg_split('/ /', $str, -1, PREG_SPLIT_OFFSET_CAPTURE);
//print_r($chars);


function test_odd($var)
{
    return($var & 1);
}

$a1=array("a","b",2,3,4);
//print_r(array_filter($a1,"test_odd"));

$a = ['a','b','color'=>'red',0,1];
$b = ['b','c','color'=>'bbb',3,2];
//print_r(array_merge($a,$b));


$a = array(
    array(
        'id' => 5698,
        'first_name' => 'Bill',
        'last_name' => 'Gates',
    ),
    array(
        'id' => 4767,
        'first_name' => 'Steve',
        'last_name' => 'Jobs',
    ),
    array(
        'id' => 3809,
        'first_name' => 'Mark',
        'last_name' => 'Zuckerberg',
    )
);

$last_names = array_column($a, 'last_name','id');
//print_r($last_names);






//function haxi($arr)
// {
//     hash[7]={0};
//    for($i=0;$i<7;$i++){
//     if(++hash[$arr[i]]==2){
//         cout<<"result:"<<a[i];
//         break;
//     }
//}


//print_r(hash_algos());


function getMax($arr){
    $len = count($arr);
    for($i=0;$i<$len;$i++) {
        for($j=1;$j<$len-$i;$j++){
            if(($arr[$j-1].$arr[$j]) < ($arr[$j].$arr[$j-1]))
            {
                $tmp= $arr[$j];
                $arr[$j] = $arr[$j-1];
                $arr[$j-1] = $tmp;
            }
        }
    }
    return $arr;
}
$arr = [3,78,31,9,54,65];
//echo $arr[0].$arr[3];
//print_r(getMax($arr));



//数组层级缩进转换
function array2level($array, $pid = 0, $level = 1) {
    static $list = [];
    foreach ($array as $v) {
        if ($v['pid'] == $pid) {
            $v['level'] = $level;
            $list[] = $v;
            array2level($array, $v['id'], $level + 1);
        }
    }

    return $list;
}

//形成树状格式
function arr2tree($tree, $rootId = 0,$level=1) {
    $return = array();
    foreach($tree as $leaf) {
        if($leaf['pid'] == $rootId) {
            $leaf["level"] = $level;
            foreach($tree as $subleaf) {
                if($subleaf['pid'] == $leaf['id']) {
                    $leaf['children'] = arr2tree($tree, $leaf['id'],$level+1);
                    break;
                }
            }
            $return[] = $leaf;
        }
    }
    return $return;
}

$arrCate = array(  //待排序数组
    array( 'id'=>1, 'name' =>'顶级栏目一', 'pid'=>0),
    array( 'id'=>2, 'name' =>'顶级栏目二', 'pid'=>0),
    array( 'id'=>3, 'name' =>'栏目三', 'pid'=>1),
    array( 'id'=>4, 'name' =>'栏目四', 'pid'=>3),
    array( 'id'=>5, 'name' =>'栏目五', 'pid'=>4),
    array( 'id'=>6, 'name' =>'栏目六', 'pid'=>2),
    array( 'id'=>7, 'name' =>'栏目七', 'pid'=>6),
    array( 'id'=>8, 'name' =>'栏目八', 'pid'=>6),
    array( 'id'=>9, 'name' =>'栏目九', 'pid'=>7),
);

//$tree = arr2tree($arrCate);
//print_r($tree);

//$result=array2level($arrCate);
//print_r($result);

$arrList = [
    ['id'=>1,'type'=>'0','pid'=>0,'name'=>'A1'],
    ['id'=>2,'type'=>'1','pid'=>1,'name'=>'A2'],
    ['id'=>3,'type'=>'1','pid'=>1,'name'=>'A3'],
    ['id'=>4,'type'=>'2','pid'=>2,'name'=>'A4'],
    ['id'=>5,'type'=>'2','pid'=>2,'name'=>'A5'],
    ['id'=>6,'type'=>'2','pid'=>3,'name'=>'A6'],
    ['id'=>7,'type'=>'2','pid'=>3,'name'=>'A7'],
    ['id'=>8,'type'=>'2','pid'=>3,'name'=>'A8'],
];

function arrToTree($arr,$rootId=0,$type=0)
{
    $return = [];
    foreach ($arr as $tree) {
        echo $tree['parent'];
        if(!isset($tree['parent'])) {
            continue;
        }
        if($tree['parent']== $rootId) {
            foreach ($tree as $item) {
                if(!isset($item['parent'])) {
                    continue;
                }
                if($item['parent'] == $tree['id']) {
                    $tree['chilren'] = arrToTree($item,$tree['id'],$item['type']);
                    break;
                }
            }
            $return[] = $tree;
        }
    }
    return $return;
}

function genTree($items){
    $tree = array(); //格式化的树
    $tmpMap = array();  //临时扁平数据
    foreach ($items as $item) {
        $tmpMap[$item['id']] = $item;
    }
    foreach ($items as $item) {
        if (isset($tmpMap[$item['pid']])) {
            $tmpMap[$item['pid']]['children'][] = &$tmpMap[$item['id']];
        } else {
            $tree[] = &$tmpMap[$item['id']];
        }

    }
    unset($tmpMap);
    return $tree;
}

//print_r(genTree($arrList));
//print_r(arrToTree($arrList));

$x = 21;
$y = 7;

$z = &$x;
unset($z);
$z = &$y;

var_dump($x,$y,$z);
?>
