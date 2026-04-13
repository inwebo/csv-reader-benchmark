#!/usr/bin/env php
<?php

declare(strict_types=1);

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '0');

const DATA_DIR = __DIR__ . '/data';
const SIZES    = [
    'small'  => 1_000,
    'medium' => 10_000,
    'large'  => 100_000,
];

function generateCsv(string $path, int $rows): void
{
    $fp       = fopen($path, 'w');
    $statuses = ['active', 'inactive', 'pending'];

    fputcsv($fp, ['id', 'name', 'email', 'age', 'status', 'score']);

    for ($i = 1; $i <= $rows; $i++) {
        fputcsv($fp, [
            $i,
            '  User ' . $i . '  ',
            'user' . $i . '@example.com',
            rand(18, 75),
            $statuses[array_rand($statuses)],
            round(mt_rand(0, 10000) / 100, 2),
        ]);
    }

    fclose($fp);
}

if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

echo "Generating CSV fixtures...\n\n";

foreach (SIZES as $label => $count) {
    $path = DATA_DIR . "/{$label}.csv";
    echo "  {$label} ({$count} rows)... ";
    generateCsv($path, $count);
    echo "done (" . round(filesize($path) / 1024, 1) . " KB)\n";
}

echo "\nAll fixtures ready in " . DATA_DIR . "\n";
