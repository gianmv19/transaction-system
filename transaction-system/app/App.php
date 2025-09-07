<?php

declare(strict_types = 1);

function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach(scandir($dirPath) as $file) {
        if (is_dir($file)) {
            continue;
        }

        $files[] = $dirPath . $file;
    }

    return $files;
}

function getTransactions(string $fileName, ?callable $transactionHandler = null): array /*return array*/
{
    if (! file_exists($fileName)) {
        trigger_error('File "' . $fileName . '" does not exist.' . E_USER_ERROR);
    }

    $file = fopen($fileName, 'r'/*for reading*/);

    fgetcsv($file);

    $transactions = [];

    while (($transaction = fgetcsv($file)) !== false) /*while theres files inside the csv file*/{
        if($transactionHandler !== null) {
            $transaction = $transactionHandler($transaction);
        }

        $transactions[] = $transaction; //add a line to the transaction array
    }

     fclose($file);

    return $transactions;
}

function extractTransaction(array $transactionRow): array 
{
    [$date, $checkNumber, $description, $amount] = $transactionRow;

    $amount = (float) str_replace(['$',','], '', $amount);

    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount,
    ];
}

function calculateTotals(array $transactions): array
{
    $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

   foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount'];

        if ($transaction['amount'] >= 0) {
            $totals['totalIncome'] += $transaction['amount'];
        } else {
            $totals['totalExpense'] += $transaction['amount'];
        }
    }
    return $totals;
}