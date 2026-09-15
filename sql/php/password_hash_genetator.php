<?php

if (php_sapi_name() !== 'cli') {
    die("This script can only be executed from the command line.\n");
}

if ($argc !== 5) {
    echo "Usage: php user.php <name> <email> <password> <id_role>\n";
    exit(1);
}

$name = $argv[1];
$email = $argv[2];
$password = $argv[3];
$id_role = $argv[4];

if (!ctype_digit($id_role)) {
    echo "Error: id_role must be a number.\n";
    exit(1);
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

echo "('$name', '$email', '$password_hash', $id_role)" . PHP_EOL;