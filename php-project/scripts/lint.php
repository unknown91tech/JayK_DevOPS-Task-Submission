<?php
echo "Running PHP_CodeSniffer for linting...\n";

$phpcs = shell_exec('vendor/bin/phpcs --standard=PSR12 ./app');
if (strpos($phpcs, 'ERROR') !== false) {
    echo "Linting failed:\n$phpcs";
    exit(1);
}

echo "Linting passed successfully.\n";
exit(0);
?>