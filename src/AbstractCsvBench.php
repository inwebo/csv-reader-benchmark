<?php

namespace Inwebo\BenchmarkCsvReader;

class AbstractCsvBench
{
    protected string $file = '';

    public function setUp(array $params): void
    {
        $this->file = $params['file'];
    }

    /**
     * @return \Generator<string, array{file: string}>
     */
    public function provideFiles(): \Generator
    {
        yield 'small'  => ['file' => __DIR__ . '/../data/small.csv'];
        yield 'medium' => ['file' => __DIR__ . '/../data/medium.csv'];
        yield 'large'  => ['file' => __DIR__ . '/../data/large.csv'];
    }

    /**
     * @return \Generator<string, array{file: string}>
     */
    public function provideLargeFile(): \Generator
    {
        yield 'large' => ['file' => __DIR__ . '/../data/large.csv'];
    }
}