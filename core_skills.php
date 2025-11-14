<?php

# 1.
# Create an array of 10 random numbers between 1 and 20;
$a = [];

for($i = 0; $i <= 10; $i++) {
    $a[] = rand(1, 20);
}

# Filter numbers below 10 (using either a loop or array_filter);

function is_less_than_10($num) {
    return $num < 10;
}

$below_10 = array_filter($a, "is_less_than_10");

# Output both the original array and the filtered array.

print($a);
print($below_10);

# 2.
$f = [];

# 3.
// ...
