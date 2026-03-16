<?php

class item
{
    private string $name;
    private int $quantity;

    public function __construct(string $name, int $quantity)
    {
        $this->name = $name;
        $this->quantity = $quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
    public function increase(int $quantity): void
    {
        $this->quantity += $quantity;
    }
}





function test()
{
    echo "This is a test function.";
    //index array
$fruits = array("apple", "banana", "cherry");
echo $fruits[1]; // Outputs: banana
//assigns a key automatically from index 0 to 2
$myArr = array("Volvo",15,["apples", "bananas"]);
echo "\n";
echo $myArr[1]; //array can be of any type and can output it
$fruits_2 = array(1 => "apple", "banana", "cherry");
echo "\n";
echo $fruits_2[1]; // Outputs: apple
//associative array
$ages = array("Peter" => 35, "Ben" => 37, "Joe" => 43);
echo "\n";
echo $ages["Peter"]; // Outputs: 35
echo "\n";
echo $ages["Ben"]; // Outputs: 37
echo "\n";
echo $ages["Joe"]; // Outputs: 43
//multidimensional array
$contacts = array(
    "John" => array(
        "email" => "john@example.com",
        "phone" => "123-456-7890"
    ),
    "Jane" => array(
        "email" => "jane@example.com",
        "phone" => "098-765-4321"
    )
);
echo "\n";
echo $contacts["John"]["phone"]; // Outputs: 123-456-7890
}
