<?php
//Time comlexity 


//Example of 0(n) time complexity for finding two numbers that add up to a target in an array
$nums = [2, 7, 11,15];
$target = 9;
$result = [];
for ($i = 0; $i < count($nums); $i++) {
        $complement = $target - $nums[$i];
    if (isset($result[$complement])){
        echo "Found at index: " . $result[$complement] . " and " . $i;
    }
    $result[$nums[$i]] = $i;
    }
//Example of 0(1) time complexity
echo "\n";
echo "Value for O(1) time complexity: " . $nums[1];
//They are often just direct lookups, assignments or arithmetic

//Example of 0( log n) time complexity
function binarySearch($arr, $target) {
    $left = 0;
    $right = count($arr) - 1;
    
    while ($left <= $right) {
        $mid = floor(($left + $right) / 2);
        
        if ($arr[$mid] == $target) {
            return $mid; // Target found
        } elseif ($arr[$mid] < $target) {
            $left = $mid + 1; // Search in the right half
        } else {
            $right = $mid - 1; // Search in the left half
        }
    }
    
    return -1; // Target not found
} 
$sortedArray = [1, 2, 3, 4, 5, 6, 7, 8, 9];
$targetValue = 5;
$resultIndex = binarySearch($sortedArray, $targetValue);
if ($resultIndex != -1) {
    echo "\nTarget found at index: " . $resultIndex;
} else {
    echo "\nTarget not found in the array.";
}

//Example of 0(n log n) time complexity for  array
function mergeSort($arr) {
    if (count($arr) <= 1) {
        return $arr;
    }
    
    $mid = floor(count($arr) / 2);
    $left = array_slice($arr, 0, $mid);
    $right = array_slice($arr, $mid);
    
    return merge(mergeSort($left), mergeSort($right));
}
function merge($left, $right) {
    $result = [];
    $i = $j = 0;
    
    while ($i < count($left) && $j < count($right)) {
        if ($left[$i] < $right[$j]) {
            $result[] = $left[$i];
            $i++;
        } else {
            $result[] = $right[$j];
            $j++;
        }
    }
    
    return array_merge($result, array_slice($left, $i), array_slice($right, $j));
}
$unsortedArray = [38, 27, 43, 3, 9, 82, 10];
$sortedArray = mergeSort($unsortedArray);
echo "\nSorted Array: " . implode(", ", $sortedArray);

//Example of 0(n^2) time complexity for finding pairs of numbers that add up to a target in an array
$nums = [2, 7, 11, 15];
$target = 9;
for ($i = 0; $i < count($nums); $i++) {
    for ($j = $i + 1; $j < count($nums); $j++) {
        if ($nums[$i] + $nums[$j] == $target) {
            echo "\nFound at index: " . $i . " and " . $j;
        }
    }
}
//Example of 0(2^n) time complexity for generating all permutations of an array
function permute($arr) {
    if (count($arr) <= 1) {
        return [$arr];
    }
    
    $permutations = [];
    for ($i = 0; $i < count($arr); $i++) {
        $current = $arr[$i];
        $remaining = array_merge(array_slice($arr, 0, $i), array_slice($arr, $i + 1));
        foreach (permute($remaining) as $perm) {
            array_unshift($perm, $current);
            $permutations[] = $perm;
        }
    }
    
    return $permutations;
}
$array = [1, 2, 3];
$permutations = permute($array);
echo "\nPermutations: \n";
foreach ($permutations as $perm) {
    echo implode(", ", $perm) . "\n";
}

//Space complexity

//Example of 0(1) space complexity
function swap(&$a, &$b) {
    $temp = $a;
    $a = $b;
    $b = $temp;//This function uses a constant amount of space regardless of the input size
}
$x = 5;
$y = 10;
swap($x, $y);
echo "\nAfter swapping: x = " . $x . ", y = " . $y;

//Example of 0(n) space complexity 
function fibonacci($n) {
    if ($n <= 1) {
        return $n;
    }
    
    $fib = [0, 1];
    for ($i = 2; $i <= $n; $i++) {
        $fib[$i] = $fib[$i - 1] + $fib[$i - 2];
    }
    
    return $fib[$n]; // This function uses O(n) space to store the Fibonacci sequence up to n
}
$n = 10;
echo "\nFibonacci of " . $n . " is: " . fibonacci($n);

//Example of 0(n^2) space complexity for binary search
function binarySearchWithSpace($arr, $target) {
    $left = 0;
    $right = count($arr) - 1;
    $spaceUsed = [];
    
    while ($left <= $right) {
        $mid = floor(($left + $right) / 2);
        $spaceUsed[] = $arr[$mid]; // Storing the mid element in spaceUsed array
        
        if ($arr[$mid] == $target) {
            return $mid; // Target found
        } elseif ($arr[$mid] < $target) {
            $left = $mid + 1; // Search in the right half
        } else {
            $right = $mid - 1; // Search in the left half
        }
    }
    
    return -1; // Target not found
}
$sortedArray = [1, 2, 3, 4, 5, 6, 7, 8, 9];
$targetValue = 5;   
$resultIndex = binarySearchWithSpace($sortedArray, $targetValue);
if ($resultIndex != -1) {
    echo "\nTarget found at index: " . $resultIndex;
} else {
    echo "\nTarget not found in the array.";
}

//Example of 0(log n) space complexity 
function binarySearchRecursive($arr, $target, $left, $right) {
    if ($left > $right) {
        return -1; // Target not found
    }
    
    $mid = floor(($left + $right) / 2);
    
    if ($arr[$mid] == $target) {
        return $mid; // Target found
    } elseif ($arr[$mid] < $target) {
        return binarySearchRecursive($arr, $target, $mid + 1, $right); // Search in the right half
    } else {
        return binarySearchRecursive($arr, $target, $left, $mid - 1); // Search in the left half
    }
}
$sortedArray = [1, 2, 3, 4, 5, 6, 7, 8, 9];
$targetValue = 5;   
$resultIndex = binarySearchRecursive($sortedArray, $targetValue, 0, count($sortedArray) - 1);
if ($resultIndex != -1) {
    echo "\nTarget found at index: " . $resultIndex;
} else {
    echo "\nTarget not found in the array.";
}