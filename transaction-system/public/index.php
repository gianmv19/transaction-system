<?php

declare(strict_types = 1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

require APP_PATH  . 'App.php';
require APP_PATH . 'helpers.php';

$files = getTransactionFiles(FILES_PATH);

$transactions = [];
foreach ($files as $file) {
	$transactions = array_merge($transactions, getTransactions($file, 'extractTransaction')); /*were getting transactions and in this case, using extraTransaction function*/
}


// $files = getTransactionFiles(FILES_OTHER_PATH);

// $transactions = [];
// foreach ($files as $file) {
// 	$transactions = array_merge($transactions, getTransactions($file, 'extractTransactionFromBankY')); /*this is an example of how another function could look lik*e/
// }

$totals = calculateTotals($transactions);

require VIEWS_PATH . 'transactions.php';