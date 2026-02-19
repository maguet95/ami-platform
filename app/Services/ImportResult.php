<?php

namespace App\Services;

class ImportResult
{
    public function __construct(
        public int $created = 0,
        public int $duplicates = 0,
        public array $errors = [],
    ) {}

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function total(): int
    {
        return $this->created + $this->duplicates + count($this->errors);
    }
}
