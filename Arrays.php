<?php
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



