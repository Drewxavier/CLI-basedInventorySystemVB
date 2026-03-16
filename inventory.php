<?php
declare(strict_types=1);
// start your PHP CLI inventory system code here

/**
 * Simple CLI-based Inventory System
 *
 * This script can be executed from the command line (php inventory.php) and
 * supports basic inventory operations using pure PHP classes.
 *
 * Commands:
 *   add <name> <qty>     - add a new item or increase quantity
 *   remove <name>        - remove an item entirely
 *   update <name> <qty>  - set quantity for an existing item
 *   list                 - show all items in inventory
 *   help                 - display help text
 *   exit                 - quit the CLI
 *   demo                 - show a short demo of modern PHP 8 features
 *
 * Items are stored in memory for the lifetime of the process; there is no
 * persistence to disk or database in this simple example.
 */

// make sure the script is run on CLI
//Code to run from attachment: C:\xampp\php\php.exe inventory.php

if (php_sapi_name() !== 'cli') {
	fwrite(STDERR, "This script is intended to be run from the command line.\n");
	exit(1);
}

/**
 * Class Item
 * Represents a single inventory item. Demonstrates typed properties, visibility,
 * and basic constructor logic. The property types (`string`, `int`) are
 * enforced thanks to the `declare(strict_types=1);` directive at the top of the
 * file.
 */
class Item
{
    // properties with type declarations and visibility
    private string $name;
	private int $quantity;
    // constructor with type hints and default value for quantity
	public function __construct(string $name, int $quantity = 0)
	{
		$this->name = $name;
		$this->quantity = max(0, $quantity);
	}

    // getter and setter methods for properties. How data inside the class is accessed and modified is controlled by these methods, demonstrating encapsulation.
	public function getName(): string
	{
		return $this->name;
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

    // setter method that ensures quantity cannot be negative
	public function setQuantity(int $qty): void
	{
		$this->quantity = max(0, $qty);
	}
    // method to increase quantity by a given amount, ensuring it cannot go negative, demonstrating a simple business logic method inside the class. using a loop 
	public function increase(int $qty): void
	{
		$this->quantity += max(0, $qty);
	}
}

/**
 * Class Inventory
 * Manages a collection of Item objects.
 */
interface InventoryInterface
{
	public function add(string $name, int $qty): void;
	public function update(string $name, int $qty): bool;
	public function remove(string $name): bool;
	/**
	 * @return Item[]
	 */
	public function list(): array;
}

/**
 * Class Inventory
 * Manages a collection of Item objects. Implements `InventoryInterface` to
 * demonstrate interfaces and inheritence-like contracts. Internally it uses a
 * private associative array keyed by lowercase item names so lookups are case
 * insensitive.
 */
class Inventory implements InventoryInterface
{
	/** @var Item[] indexed by lowercase item name */
	private array $items = [];

    // add a new item or increase quantity if it already exists. The item name is case-insensitive, so "Apple" and "apple" refer to the same item. This method demonstrates how to handle both creation and updating logic in one method.
	public function add(string $name, int $qty): void
	{
		$key = strtolower($name);
		if (isset($this->items[$key])) {
			$this->items[$key]->increase($qty);
		} else {
			$this->items[$key] = new Item($name, $qty);
		}
	}

    // update the quantity of an existing item. Returns true if the item was found and updated, false if the item does not exist. This method demonstrates how to handle updates and return status.
	public function update(string $name, int $qty): bool
	{
		$key = strtolower($name);
		if (!isset($this->items[$key])) {
			return false;
		}
		$this->items[$key]->setQuantity($qty);
		return true;
	}

    // remove an item from inventory. Returns true if the item was found and removed, false if the item does not exist. This method demonstrates how to handle deletions and return status.
	public function remove(string $name): bool
	{
		$key = strtolower($name);
		if (!isset($this->items[$key])) {
			return false;
		}
		unset($this->items[$key]);
		return true;
	}

	/**
	 * @return Item[]
	 */
	public function list(): array
	{
		// sort by name for consistent output using an arrow function (PHP 7.4+)
		uasort($this->items, fn(Item $a, Item $b) => strcasecmp($a->getName(), $b->getName()));//this is like creating your own form of sorting, you can sort by any criteria you want by changing the logic inside the arrow function. In this case, we are sorting items alphabetically by their name in a case-insensitive manner using `strcasecmp()`.
		return $this->items;
	}
	public function get(string $name): ?Item
	{
		$key = strtolower($name);
		return $this->items[$key] ?? null;
	}
	public function decrease(string $name, int $qty): bool
	{
		$key = strtolower($name);
		if (!isset($this->items[$key])) {
			return false;
		}
		$current = $this->items[$key]->getQuantity();
		$newQty = max(0, $current - $qty);
		$this->items[$key]->setQuantity($newQty);

		if ($newQty === 0) {
			unset($this->items[$key]);
		}
		return true;

	}
}

/**
 * Show a short demonstration of some PHP 8 / modern language features and
 * basic syntax topics as required
 */
function demo(): void
{
	echo "=== PHP 8+ Feature Demo ===\n";//

	// data types and strict typing
	$intVar = 5;
	$floatVar = 3.14;
	$stringVar = "hello";
	$boolVar = true;
	echo "Types: ".gettype($intVar).", ".gettype($floatVar)."\n";

	// arrays (indexed vs. associative)
	$indexed = [1, 2, 3];
	$assoc = ['one' => 1, 'two' => 2];
	echo "Indexed: ".implode(',', $indexed)."\n";
	echo "Assoc 'two': ".$assoc['two']."\n";

	// control structures & loops
	for ($i = 0; $i < 2; $i++) {
		echo "for loop $i\n";
	}
	foreach ($assoc as $key => $val) {
		echo "foreach $key=$val\n";
	}

	// anonymous functions & arrow functions
	$squares = array_map(fn($n) => $n * $n, $indexed);
	echo "Squares using arrow fn: ".implode(',', $squares)."\n";

	// null coalescing operator
	$maybe = null;
	$value = $maybe ?? 'default';
	echo "Null coalescing: $value\n";

	// match expression
	$status = match ($intVar) {
		1 => 'one',
		5 => 'five',
		default => 'other',
	};
	echo "Match result: $status\n";

	echo "=== End Demo ===\n";
}


// CLI helper functions
function printHelp(): void
{
	echo "Available commands:\n";
	echo "  add <name> <qty>           - add item or increase quantity\n";
	echo "  update <name> <qty>        - set quantity\n";
	echo "  decrease <name> <qty>      - decrease quantity\n";
	echo "  remove <name>              - delete item\n";
	echo "  get <name> <action> [qty]  - show item quantity\n";
	echo "          actions: view, update <qty>, decrease <qty>, remove\n";
	echo "  list                       - show all items\n";
	echo "  help                       - display this message\n";
	echo "  exit                       - leave the program\n";
}

// instantiate inventory
$inventory = new Inventory();

// main loop
echo "PHP Inventory CLI\n";
echo "Type 'help' for a list of commands.\n";

while (true) {
	echo "> ";
	$line = trim(fgets(STDIN));
	if ($line === false) {
		// end of input
		break;
	}
	if ($line === '') {
		continue;
	}
	$parts = preg_split('/\s+/', $line);
	$cmd = strtolower(array_shift($parts));

	switch ($cmd) {
		case 'help':
			printHelp();
			break;
		case 'add':
			if (count($parts) < 2) {
				echo "Usage: add <name> <qty>\n";
				break;
			}
			[$name, $qty] = $parts;
			if (!is_numeric($qty) || (int)$qty < 0) {
				echo "Quantity must be a non-negative number.\n";
				break;
			}
			$inventory->add($name, (int)$qty);
			echo "Added {$qty} of {$name}.\n";
			break;
		case 'update':
			if (count($parts) < 2) {
				echo "Usage: update <name> <qty>\n";
				break;
			}
			[$name, $qty] = $parts;
			if (!is_numeric($qty) || (int)$qty < 0) {
				echo "Quantity must be a non-negative number.\n";
				break;
			}
			if ($inventory->update($name, (int)$qty)) {
				echo "Set {$name} quantity to {$qty}.\n";
			} else {
				echo "Item '{$name}' not found.\n";
			}
			break;
		case 'remove':
			if (count($parts) < 1) {
				echo "Usage: remove <name>\n";
				break;
			}
			$name = $parts[0];
			if ($inventory->remove($name)) {
				echo "Removed '{$name}' from inventory.\n";
			} else {
				echo "Item '{$name}' not found.\n";
			}
			break;
		case 'list':
			$items = $inventory->list();
			if (empty($items)) {
				echo "Inventory is empty.\n";
			} else {
				foreach ($items as $item) {
					printf("%s: %d\n", $item->getName(), $item->getQuantity());
				}
			}
			break;
		case 'exit':
			echo "Bye!\n";
			exit(0);
		case 'get':
            if (count($parts) < 2) {
				echo "Usage: get <name> <action> [qty]\n";
				echo "Actions: view, update <qty>, decrease <qty>, remove\n";
                break;
		     }
			 $name = $parts[0];
			 $action = strtolower($parts[1]);
			 $item = $inventory->get($name);
			 
			 if (!$item) {
				echo "Item '{$name}' not found.\n";
                break;
		    }
			switch ($action) {
				case 'view':
					echo $item->getName() . ": " . $item->getQuantity() . "\n";
				break;

                case 'update':
				    if (count($parts) < 3 || !is_numeric($parts[2])) {
						echo "Usage: get {$name} update <qty>\n";
                break;
                    }
                    $qty = (int)$parts[2];
                    $inventory->update($name, $qty);
                    echo "Updated {$name} to {$qty}.\n";
                    break;

                case 'decrease':
                    if (count($parts) < 3 || !is_numeric($parts[2])) {
                        echo "Usage: get {$name} decrease <qty>\n";
                        break;
                    }
                    $qty = (int)$parts[2];
                    $inventory->decrease($name, $qty);
                    echo "Decreased {$name} by {$qty}.\n";
                    break;

                case 'remove':
                    $inventory->remove($name);
                    echo "Removed {$name}.\n";
                    break;

                default:
                    echo "Unknown action '{$action}'. Use view, update, decrease, or remove.\n";
                break;
    }
    break;

		case 'decrease':
			if (count($parts) < 2) {
				echo "Usage: decrease <name> <gty>\n";
				break;
			}
			[$name, $qty] = $parts;
			if (!is_numeric($qty) || (int)$qty < 0) {
				echo "Quantity must be a non-negative number.\n";
				break;
			}
			if ($inventory->decrease($name, (int)$qty)) {
				echo "Decreased {$name} quantity by {$qty}.\n";
			} else {
				echo "Item '{$name}' not found.\n";
			}
			break;

		case 'demo':
			demo();
			break;
		default:
			echo "Unknown command '{$cmd}'. Type 'help' for commands.\n";
			break;
	}
}



