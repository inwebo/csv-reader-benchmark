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
        yield 'Small C.S.V. file (~55 ko)'  => ['file' => __DIR__ . '/../data/small.csv'];
        yield 'Medium C.S.V. file (~584 ko)' => ['file' => __DIR__ . '/../data/medium.csv'];
        yield 'Large C.S.V. file (~6.1 Mo)'  => ['file' => __DIR__ . '/../data/large.csv'];
    }

    /**
     * @return \Generator<string, array{file: string}>
     */
    public function provideLargeFile(): \Generator
    {
        yield 'Large C.S.V. file (~6.1 Mo)' => ['file' => __DIR__ . '/../data/large.csv'];
    }
}