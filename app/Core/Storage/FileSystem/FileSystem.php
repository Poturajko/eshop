<?php

namespace App\Core\Storage\FileSystem;

class FileSystem implements IFileSystem
{
    public function isFile(string $filename): bool
    {
        return is_file($filename);
    }

    public function isDirectory(string $dirname): bool
    {
        return is_dir($dirname);
    }

    public function isReadable(string $filename): bool
    {
        return is_readable($filename);
    }

    public function isWritable(string $filename): bool
    {
        return is_writable($filename);
    }

    public function exists(string $filename): bool
    {
        return file_exists($filename) && is_file($filename);
    }

    public function get(string $filename)
    {
        return file_get_contents($filename);
    }

    public function getLines(string $filename, int $offset, ?int $length, int $flags = 0): array
    {
        return array_slice(file($filename, $flags), $offset, $length, true);
    }

    public function size(string $filename)
    {
        return filesize($filename);
    }

    public function baseName(string $path): string
    {
        return (string)pathinfo($path, PATHINFO_BASENAME);
    }

    public function fileName(string $path): string
    {
        return (string)pathinfo($path, PATHINFO_FILENAME);
    }

    public function extension(string $path): string
    {
        return (string)pathinfo($path, PATHINFO_EXTENSION);
    }

    public function put(string $filename, string $data, bool $lock = false)
    {
        return file_put_contents($filename, $data, $lock ? LOCK_EX : 0);
    }

    public function append(string $filename, string $data, bool $lock = false)
    {
        return file_put_contents($filename, $data, $lock ? FILE_APPEND | LOCK_EX : FILE_APPEND);
    }

    public function rename(string $oldName, string $newName): bool
    {
        return rename($oldName, $newName);
    }

    public function copy(string $source, string $dest): bool
    {
        return copy($source, $dest);
    }

    public function create(string $filename): bool
    {
        return touch($filename);
    }

    public function makeDirectory(string $path, int $mode = 0777, bool $recursive = false): bool
    {
        return mkdir($path, $mode, $recursive);
    }

    public function glob(string $pattern, int $flags = 0)
    {
        return glob($pattern, $flags);
    }

    public function remove(string $filename): bool
    {
        return unlink($filename);
    }

}