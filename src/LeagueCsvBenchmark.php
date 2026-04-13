<?php

declare(strict_types=1);

namespace Inwebo\BenchmarkCsvReader;

use League\Csv\Reader as LeagueCsvReader;
use League\Csv\Statement;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks for league/csv.
 *
 * Mirror of InweboCsvReaderBenchmark — identical scenarios, same attributes,
 * so results are directly comparable.
 */
#[Bench\BeforeMethods('setUp')]
#[Bench\Revs(10)]
#[Bench\Iterations(5)]
#[Bench\Warmup(1)]
#[Bench\OutputTimeUnit('milliseconds')]
class LeagueCsvBenchmark extends AbstractCsvBench
{
    // ── S1 — Basic read ───────────────────────────────────────────────────────

    #[Bench\ParamProviders('provideFiles')]
    public function benchBasicRead(): void
    {
        $csv = LeagueCsvReader::from($this->file);
        $csv->setHeaderOffset(0);
        foreach ($csv->getRecords() as $ignored) {
        }
    }

    // ── S2 — Filtering: status === 'active' AND age > 30 ─────────────────────

    #[Bench\ParamProviders('provideFiles')]
    public function benchFiltering(): void
    {
        $csv  = LeagueCsvReader::from($this->file);
        $csv->setHeaderOffset(0);
        $stmt = Statement::create()
            ->where(fn (array $row): bool => ($row['status'] ?? '') === 'active')
            ->where(fn (array $row): bool => (int) ($row['age'] ?? 0) > 30);

        foreach ($stmt->process($csv)->getRecords() as $ignored) {
        }
    }

    // ── S3 — Normalization: trim + cast age(int) + score(float) ──────────────

    #[Bench\ParamProviders('provideFiles')]
    public function benchNormalization(): void
    {
        $csv = LeagueCsvReader::from($this->file);
        $csv->setHeaderOffset(0);

        foreach ($csv->getRecords() as $record) {
            $record = array_map('trim', $record);
            if (isset($record['age']))   $record['age']   = (int) $record['age'];
            if (isset($record['score'])) $record['score'] = (float) $record['score'];
        }
    }

    // ── S4 — Large file: memory stress, 100 000 rows only ────────────────────

    #[Bench\ParamProviders('provideLargeFile')]
    public function benchLargeFile(): void
    {
        $csv = LeagueCsvReader::from($this->file);
        $csv->setHeaderOffset(0);
        foreach ($csv->getRecords() as $ignored) {
        }
    }
}
