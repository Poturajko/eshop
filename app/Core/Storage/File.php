<?php

namespace App\Core\Storage;

use App\Core\Exception\FileUploadException;
use App\Core\Storage\FileSystem\FileSystem;
use App\Core\Storage\FileSystem\FileSystemFactory;
use App\Core\Storage\FileSystem\IFileSystem;
use finfo;
use SplFileInfo;

class File extends SplFileInfo
{
    private IFileSystem $fs;

    private $originalName = null;

    private $name = null;

    private $extension = null;

    private $mimetype = null;

    private $errorMessages = [
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload'
    ];

    private $blackistedExtensions = [
        'php([0-9])?',
        'pht',
        'phar',
        'phpt',
        'pgif',
        'phtml',
        'phtm',
        'phps',
        'cgi',
        'inc',
        'env',
        'htaccess',
        'htpasswd',
        'config',
        'conf',
        'bat',
        'exe',
        'msi',
        'cmd',
        'dll',
        'sh',
        'com',
        'app',
        'sys',
        'drv',
        'pl',
        'jar',
        'jsp',
        'js',
        'vb',
        'vbscript',
        'wsf',
        'asp',
        'py',
        'cer',
        'csr',
        'crt',
    ];

    private $errorCode;

    public function __construct(array $file = [])
    {
        $this->fs = (new FileSystemFactory())->create(FileSystem::class);

        if (!empty($file)) {
            $this->originalName = $file['name'];
            $this->errorCode = $file['error'];
            parent::__construct($file['tmp_name']);
        }
    }

    public function getName(): string
    {
        if (!$this->name) {
            $this->name = $this->fs->fileName($this->originalName);
        }

        return $this->name;
    }

    public function setName(string $name): File
    {
        $this->name = $name;

        return $this;
    }

    public function getExtension(): string
    {
        if (!$this->extension) {
            $this->extension = strtolower($this->fs->extension($this->originalName));
        }

        return $this->extension;
    }

    public function getNameWithExtension(): string
    {
        return $this->getName() . '.' . $this->getExtension();
    }


    public function getMimeType(): string
    {
        if (!$this->mimetype) {
            $finfo = new finfo(FILEINFO_MIME);
            $mimetype = $finfo->file($this->getPathname());
            $mimetypeParts = preg_split('/\s*[;,]\s*/', $mimetype);
            $this->mimetype = strtolower($mimetypeParts[0]);
            unset($finfo);
        }

        return $this->mimetype;
    }

    public function getMd5(): string
    {
        return md5_file($this->getPathname());
    }

    public function save(string $dest, bool $overwrite = false): ?string
    {
        $filePath = uploads_dir() . DS . $dest . DS;
        if ($this->errorCode !== UPLOAD_ERR_OK) {
            throw new FileUploadException($this->getErrorMessage());
        }

        if (!$this->whitelisted($this->getExtension())) {
            throw FileUploadException::fileTypeNotAllowed($this->getExtension());
        }

        if (!$this->fs->isDirectory($filePath)) {
            throw FileUploadException::directoryNotExists($dest);
        }

        if (!$this->fs->isWritable($filePath)) {
            throw FileUploadException::directoryNotWritable($dest);
        }

        $filePath .= $this->getNameWithExtension();

        if ($overwrite === false && $this->fs->exists($filePath)) {
            throw FileUploadException::fileAlreadyExists();
        }

        if (!$this->moveUploadedFile($filePath)) {
            return null;
        }

        return $dest . DS . $this->getNameWithExtension();
    }

    public function delete(string $dest): bool
    {
        $filePath = uploads_dir() . DS . $dest;
        if (!$this->fs->exists($filePath)) {
            throw FileUploadException::fileNotExists();
        }

        if ($this->fs->remove($filePath)) {
            return true;
        }

        return false;
    }

    public function url(?string $path): string
    {
        $filePath = uploads_dir() . DS . $path;
        if (!$this->fs->exists($filePath)){
            throw FileUploadException::fileNotExists();
        }

        return 'storage/' . $path;
    }

    private function getErrorMessage(): string
    {
        return $this->errorMessages[$this->errorCode];
    }

    private function isUploaded(): bool
    {
        return is_uploaded_file($this->getPathname());
    }

    private function moveUploadedFile(string $filePath): bool
    {
        if ($this->isUploaded()) {
            return move_uploaded_file($this->getPathname(), $filePath);
        }

        return $this->fs->isFile($this->getPathname()) && $this->fs->copy($this->getPathname(), $filePath);
    }

    private function whitelisted(string $extension): bool
    {
        if (!preg_match('/(' . implode('|', $this->blackistedExtensions) . ')$/i', $extension)) {
            return true;
        }

        return false;
    }
}