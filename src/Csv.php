<?php

namespace Vozdjn\Csv;

class Csv
{
    public array $data;
    public array $header;
    public array $body;
    public string $path;

    public static function load($path): self
    {
        $instance = new static;
        $instance->path = $path;
        $instance->data = array_map('str_getcsv', file($path));
        $instance->header = $instance->data[0];
        $instance->body = array_slice($instance->data, 1);
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
    {;
        return file_get_contents($this->path);
    }

}