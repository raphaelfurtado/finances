<?php
$v = [39.99, 272.88, 67.5, -9.9, 23.9, 956.68, 48, 17.94, 255.37, 18.93, 72.6, 264.36, 9.9, -4032.53, 139.9, 196.02, 85.97, 58.3, 35.7, 49.99, 46.33, 121.93, 59.99, 324.89, 72.49, 92.5, 189.37, 37.23, 109.87, 49.44, 40.99, 122.11, 149.95, 97.33, 73.31, 39.99];
$pos = 0;
$neg = 0;
foreach ($v as $n) {
    if ($n < 0)
        $neg += abs($n);
    else
        $pos += $n;
}
echo 'Positive (Expenses presumed): ' . number_format($pos, 2) . PHP_EOL;
echo 'Negative (Income presumed): ' . number_format($neg, 2) . PHP_EOL;

// What if the 9.9 in array was actually negative? 
// Item 4 is -9.9. Item 13 is 9.9.
// User list has -9,9 and 9,9.
// Maybe the positive 9.9 is a typo in user list or actual transaction?

// Also, the Bill says "Pagamento recebido -4032.53".
// "Outros lançamentos 76.07".
// "Total compras 4155.68".
// Total bill = 4155.68 + 76.07 = 4231.75.

// Let's see if Positive sum matches 4155.68 + 76.07 or something.
?>