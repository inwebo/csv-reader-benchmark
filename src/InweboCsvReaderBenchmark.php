<?php

declare(strict_types=1);

namespace Inwebo\BenchmarkCsvReader;

use Inwebo\Csv\Reader;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks for inwebo/csv-reader.
 *
 * Each bench method declares its own #[Bench\ParamProviders] explicitly.
 * Mixing class-level and method-level ParamProviders causes PHPBench to
 * merge both provider sets instead of overriding — so we avoid class-level
 * ParamProviders entirely.
 */
#[Bench\BeforeMethods('setUp')]
#[Bench\Warmup(1)]
#[Bench\OutputTimeUnit('milliseconds')]
#[Bench\Groups(['config_linters'])]
class InweboCsvReaderBenchmark extends AbstractCsvBench
{
    // ── S1 — Basic read ───────────────────────────────────────────────────────

    #[Bench\ParamProviders('provideFiles')]
    public function benchBasicRead(): void
    {
        $reader = new Reader($this->file);
        foreach ($reader->rows() as $ignored) {
        }
    }

    // ── S2 — Filtering: status === 'active' AND age > 30 ─────────────────────

    #[Bench\ParamProviders('provideFiles')]
    public function benchFiltering(): void
    {
        $reader = new Reader($this->file);
        $reader
            ->pushFilter(fn (array $row): bool => ($row['status'] ?? '') === 'active')
            ->pushFilter(fn (array $row): bool => (int) ($row['age'] ?? 0) > 30);

        foreach ($reader->rows() as $ignored) {
        }
    }

    // ── S3 — Normalization: trim + cast age(int) + score(float) ──────────────

    #[Bench\ParamProviders('provideFiles')]
    public function benchNormalization(): void
    {
        $reader = new Reader($this->file);
        $reader
            ->pushNormalizer(function (array &$row): void {
                $row = array_map('trim', $row);
            })
            ->pushNormalizer(function (array &$row): void {
                if (isset($row['age']))   $row['age']   = (int) $row['age'];
                if (isset($row['score'])) $row['score'] = (float) $row['score'];
            });

        foreach ($reader->rows() as $ignored) {
        }
    }

    // ── S4 — Large file: memory stress, 100 000 rows only ────────────────────

    #[Bench\ParamProviders('provideLargeFile')]
    public function benchLargeFile(): void
    {
        $reader = new Reader($this->file);
        foreach ($reader->rows() as $ignored) {
        }
    }
}
