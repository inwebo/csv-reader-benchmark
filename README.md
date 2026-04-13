# CSV Benchmark — inwebo/csv-reader vs league/csv

Powered by [PHPBench](https://phpbench.readthedocs.io/).

## Installation

```bash
composer install
```

## Step 1 — Generate CSV fixtures

```bash
php generate.php
# or
composer generate
```

Produces three files in `data/`:

| File         | Rows    | Approx. size |
|--------------|---------|--------------|
| small.csv    | 1,000   | ~65 KB       |
| medium.csv   | 10,000  | ~650 KB      |
| large.csv    | 100,000 | ~6.5 MB      |


## Step 2 — Run the benchmarks

```bash
# Both libraries, all scenarios
composer bench

# inwebo/csv-reader only
composer bench:inwebo

# league/csv only
composer bench:league
```

Or directly with PHPBench for more control:

```bash
# Run + store results for later comparison
vendor/bin/phpbench run --report=csv_report --store

# Compare two stored runs
vendor/bin/phpbench report --report=csv_report --uuid=<uuid1> --uuid=<uuid2>

# Export to markdown
vendor/bin/phpbench run --report=csv_report --output=html

# Filter by scenario name
vendor/bin/phpbench run --filter=benchBasicRead --report=csv_report
```


## Scenarios

| Class method          | Scenario      | Description                                            |
|-----------------------|---------------|--------------------------------------------------------|
| `benchBasicRead`      | Basic read    | Iterate all rows with automatic header mapping         |
| `benchFiltering`      | Filtering     | Keep only `status=active` AND `age > 30`               |
| `benchNormalization`  | Normalization | Trim whitespace + cast `age` (int) and `score` (float) |
| `benchLargeFile`      | Large file    | Full read of 100,000 rows — memory stress test         |

## Configuration

PHPBench settings are in `phpbench.json`. Key defaults:

| Setting    | Value          | Description                        |
|------------|----------------|------------------------------------|
| `Revs`     | 10             | Executions per iteration           |
| `Iterations` | 5            | Number of measurement iterations   |
| `Warmup`   | 1              | Warmup iteration (discarded)       |
| `TimeUnit` | milliseconds   | Output unit for time columns       |

Increase `Revs` and `Iterations` in the benchmark classes for more stable results on fast machines.