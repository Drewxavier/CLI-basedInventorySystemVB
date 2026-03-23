<?php
//Binary Search Algorithm
function binarysearch($arr, $target)
{
    $left = 0;
    $right = count($arr) - 1;
    while($left <= $right)
        {$mid = floor(($left + $right)/2);

    //check to see whether the value is found at the middle index
        
        if ($arr[$mid]===$target){
            return $mid;
        }
        if ($arr[$mid]> $target){
            $right = $mid + 1;
        }
        else{
            $left = $mid - 1;
        }    
    }
    return -1;
}
$nums= [1, 4, 5, 8, 24, 53, 65, 70];
$targetvalue = 53;
$result = binarysearch($nums, $targetvalue);
echo "The Value index is: ".$result ;

//Bubble sort
function  bubbleSort($arr){
    $n = count($arr);

    //Traverse through all array elements
    for ( $i=0; $i < $n - 1; $i++ ){
        for ($j = 0; $j < $n - $i -1; $j++){

        //swap if the element found is
        //greater than the next element
        if ($arr[$j] > $arr[$j + 1]){
            $temp = $arr[$j];
            $arr[$j]= $arr[$j + 1];
            $arr[$j + 1] = $temp;
        }
    }
  }
  return $arr;
}
$arr= [64, 34, 25, 12, 22, 11, 90];
$sortedArray = bubbleSort($arr);
echo "\nSorted array bubble: ". implode(",", $sortedArray);

//selection sort

function selectionSort($arr){
    $n = count($arr);
    for($i = 0; $i < $n; $i++){
        $low = $i;
        for($j = $i + 1; $j < $n ; $j++){
            if ($arr[$j]< $arr[$low]){
                $low = $j;
            }
        }
        //swap the minimum value to $ith node
        if($arr[$i] > $arr[$low]){
            $tmp = $arr[$i];
            $arr[$i] = $arr[$low];
            $arr[$low] = $tmp;
        }
    }

    return $arr;
}

$sortedArr = selectionSort($arr);
echo "\nSorted Array selection: " . implode(" ", $sortedArr);

//Insertion sort
function insertSort(array &$arr){
    $n = count($arr);
    for ($i = 1; $i < $n; $i++){
        $key = $arr[$i];
        $j = $i - 1;
        while ($j >= 0 && $arr[$j] > $key){
            $arr[$j + 1] = $arr[$j];
            $j--;
    
        }
        $arr[$j + 1] = $key;
    }
}
function printArray(array $arr){
    foreach ($arr as $value){
        echo "\n " .$value . " ";
    }
    echo "\n";
}
$arr = [12, 11, 13, 5, 6];

insertSort($arr);
printArray($arr);

//Quicksort
function quickSort($array){
    $length = count($array);

    if ($length <= 2){
        return $array;
    } else {
        $pivot = $array[0];
        $left = $right = array();

        for ($i = 1; $i < $length; $i++){
            if ($array[$i]< $pivot){
                $left[] = $array[$i];
            } else {
                $right[] = $array[$i];
            }
        }
        return array_merge(
            quickSort($left),
            array($pivot),
            quickSort($right)
        );
    }
}

$array = array(3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5);
$sortedArray = quickSort($array);

echo "Original Array: ". implode(", ", $array) . "\n";
echo "Sorted Array: " . implode(", ", $sortedArray);
// linked lists
//
class ListNode {
    public $data = NULL;
    public $next = NULL;

    public function __construct($data= NULL){
        $this ->data = $data;
    }
}
class LinkedList {
    private $firstNode = NULL;

    //Add a new node to the end of the list
    public function insert($data){
        $newNode =new ListNode($data);
        if ($this ->firstNode === NULL){
            $this ->firstNode = $newNode;
        } else {
            $currentNode = $this ->firstNode;
            while ($currentNode ->next !==NULL){
                $currentNode = $currentNode->next;
            }
            $currentNode->next = $newNode;
        }
    }
    // Traverse the list
    public function traverse(){
        $currentNode = $this->firstNode;
        while ($currentNode !==NULL){
            echo $currentNode->data. "\n";
            $currentNode = $currentNode->next;
        }
    }
    // Reverse the list
    public function reverse (){
        $prev = NULL;
        $current = $this ->firstNode;
        while ($current !== NULL){
            $next = $current ->next;
            $current->next =$prev;
            $prev = $current;
            $current = $next;
        }
        $this ->firstNode = $prev;
    }
    // Delete a node from the list
    public function delete($data){
        $current = $this ->firstNode;
        $prev = NULL;
        while ($current !== NULL){
            if ($current ->data ===$data){
                if ($prev === NULL){
                    $this ->firstNode = $current ->next;
                } else {
                    $prev->next = $current->next;
                }
                return;
            }
            $prev = $current;
            $current = $current ->next;

        }
    }
}
$list = new LinkedList();
$list -> insert(1);
$list -> insert(2);
$list -> insert(3);

echo "Lined List: \n";
$list ->traverse();

$list ->reverse();
echo "Reversed Linked List: \n";
$list ->traverse();

$list ->delete(2);
echo "Linked List after deleting 2: \n";
$list -> traverse();

function factorial ($n){
    if ($n == 1){
        echo $n . PHP_EOL;
        return 1;
    } else {
        echo "$n * ";
        return $n*factorial($n-1);
    }
}
echo "\n Factorial of 5 = " . factorial(5);
