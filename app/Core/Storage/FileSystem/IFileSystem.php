<?php

namespace App\Core\Storage\FileSystem;

interface IFileSystem
{
    public function isFile(string $filename): bool;

    public function isDirectory(string $dirname): bool;

    public function isReadable(string $filename): bool;

    public function isWritable(string $filename): bool;

    public function exists(string $filename): bool;

    public function get(string $filename);

    public function getLines(string $filename, int $offset, ?int $length, int $flags = 0): array;

    public function size(string $filename);

    public function baseName(string $path): string;

    public function fileName(string $path): string;

    public function extension(string $path): string;

    public function put(string $filename, string $data, bool $lock = false);

    public function append(string $filename, string $data, bool $lock = false);

    public function rename(string $oldName, string $newName): bool;

    public function copy(string $source, string $dest): bool;

    public function create(string $filename): bool;

    public function makeDirectory(string $path, int $mode = 0777, bool $recursive = false): bool;

    public function glob(string $pattern, int $flags = 0);

    public function remove(string $filename): bool;
}