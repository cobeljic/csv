<?php

namespace Vozdjn\Csv;

class Csv
{
    public array $data;
    public array $header;
    public array $body;
    public string $path;

    public static function load(string $path, string $separator = ','): self
    {
        $instance = new static;
        $instance->path = $path;

        $csv = array_map(function ($line) use ($separator) {
            return str_getcsv($line, $separator);
        }, file($instance->path));

        $instance->header = array_shift($csv);
        $instance->body = array_map(function ($row) use ($instance) {
            return array_combine($instance->header, $row);
        }, $csv);

        return $instance;
    }

    public function mapped(): array
    {
        return array_map(function ($body) {
            return array_combine($this->header, $body);
        }, $this->body);
    }

    public function validate(): bool
    {
        foreach ($this->body as $row) {
            if (count($row) !== count($this->header)) {
                return false;
            }
        }

        return true;
    }

    public function getPath(): string
    {
        return file_get_contents($this->path);
    }

    public function __toString()
    {
        return file_get_contents($this->path);
    }

    public function excludeHeader(array $keys): self
    {
        $this->header = array_filter($this->header, fn($key) => !in_array($key, $keys));
        $this->body = array_map(function ($row) use ($keys) {
            return array_filter($row, fn($key) => !in_array($key, $keys), ARRAY_FILTER_USE_KEY);
        }, $this->body);

        return $this;
    }

}