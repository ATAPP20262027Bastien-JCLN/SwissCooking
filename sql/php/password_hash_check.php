<?php

if ($argc < 2) {
    echo "Usage: php password_check.php '<hash>'\n";
    exit(1);
}

$hash = $argv[1];

echo "Password: ";
$password = trim(fgets(STDIN));

if (password_verify($password, $hash)) {
    echo "✓ Password is correct!\n";
} else {
    echo "✗ Password is incorrect!\n";
}