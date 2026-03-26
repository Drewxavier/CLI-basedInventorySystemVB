<?php
function product($a = 0,$b= 0)
{
    $prod = 0;
    for ($i = 0;$i<$b;$i++){
        $prod += $a;// the number adds itself like accumulating to $b
    }
    return $prod;
}
$a = 5;
$b = 6;
$result =product($a,$b);
echo "Product for the value " .$a. " and ".$b . " is: ".$result;
//time complexity is 0(n) 

function power($a, $b){
    if ($b< 0) {
        return 0; 
    }else if ($b== 0){
        return 1;
    }else{
        return $a * power($a, $b-1);
    }
}

$a_1 = 2;
$b_1= 4;
$result_1= power($a_1, $b_1);
echo "\nThe number ".$a_1." to the power of ".$b_1." is: " .$result_1;
//time complexity is 0(2^n) 

function mod($a,$b){
    if ($b <= 0){
        return -1;
    }
    $div = floor($a/$b);
    return $a - $div * $b;
}
$a_2 = 40;
$b_2 = 7;
$result_3 = mod($a_2, $b_2);
echo "\nMod for the numbers ".$a_2. " , " .$b_2. " is " .$result_3;
//time complexity is 0(1)

function div($a, $b){
    $count = 0;
    $sum = $b;
    while ($sum <= $a){
        $sum += $b;
        $count++;
    }return $count;
}
$a_3 = 60;
$b_3 = 7;
$result_4 = div($a_3, $b_3);
echo "\nDivision of the number: ".$a_3 ." to " .$b_3. " is: " .$result_4;
//time complexity is 0(n)

function sqareroot($n = 0){
    return sqrt_helper($n, 1, $n);
}
function sqrt_helper($n, $min, $max){
    if ($max < $min) return -1;
    $guess = ($min + $max) / 2;
    if ($guess * $guess == $n) {
        return $guess;
    }elseif ($guess * $guess < $n){
        return sqrt_helper($n, $guess +1, $max);
    }else {
        return sqrt_helper($n, $min, $max - 1);
    }
}
$value = 625;
$result_5= sqareroot($value);
echo "\nSquareroot of the number: ". $value . " is: " .$result_5;
//time complexity is 0(log n)
function square($n){
    for ( $guess = 1; $guess * $guess <= $n;){
        if ($guess ($guess) == $n){
            return $guess;
        }
    }
    return -1;
}
$value_2 = 441;
$result_6= sqareroot($value_2);
echo "\nSquareroot of the number: ". $value_2 . " is: " .$result_6;
//time complexity is 0(2^n)

//question 7
//The binary search tree while take much longer, at the complexity of 0(log n)(recursive meaning increasing in depth) as the height of for example the rightside of the tree becomes larger

//question 8
//0(log n)

//question 9
//To append is to attach or add a value to the end. It uses the time complexity of 0(2^n) since
//whenever a new value is added, it has to generate a new array WITH now the value, taking it longer to generate since it is calling itself again, this time with 
//a new value

function sum($n){
    $sum = 0;
    while ($n > 0){
        $sum += $n % 10;//the remainder when dividing by 10
        $n /=10;//process a number digit by digit
    }
    return $sum;
}
$a_4 = 1000;
$result_7 = sum($a_4);
echo "\nSum of the numbers: ".$result_7;

//time complexity is 0(n)

//question 11
//time complexity is 0(n^2)

