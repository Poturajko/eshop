<?php

namespace App\Core\Exception;

use Exception;

class FileUploadException extends Exception
{
    const FILE_ALREADY_EXISTS = 'File already exists';

    const FILE_NOT_EXISTS = 'File does not exists';

    const FILE_NOT_UPLOADED = 'The uploaded file was not sent with a POST request';

    const DIRECTORY_NOT_EXIST = 'Directory `{%1}` does not exists';

    const DIRECTORY_NOT_WRITABLE = 'Directory `{%1}` not writable';

    const UPLOADED_FILE_NOT_FOUND = 'Cannot find uploaded file identified by key `{%1}`';

    const DIRECTORY_CANT_BE_CREATED = 'Directory `{%1}` could not be created';

    const FILE_TYPE_NOT_ALLOWED = 'The file type `{%1}` is not allowed';

    public static function fileAlreadyExists(): FileUploadException
    {
        return new static(self::FILE_ALREADY_EXISTS, E_WARNING);
    }
    public static function fileNotExists(): FileUploadException
    {
        return new static(self::FILE_NOT_EXISTS, E_WARNING);
    }

    public static function fileNotUploaded(): FileUploadException
    {
        return new static(self::FILE_NOT_UPLOADED, E_WARNING);
    }

    public static function directoryNotExists(string $name): FileUploadException
    {
        return new static(_message(self::DIRECTORY_NOT_EXIST, $name), E_WARNING);
    }

    public static function directoryNotWritable(string $name): FileUploadException
    {
        return new static(_message(self::DIRECTORY_NOT_WRITABLE, $name), E_WARNING);
    }

    public static function fileNotFound(string $name): FileUploadException
    {
        return new static(_message(self::UPLOADED_FILE_NOT_FOUND, $name), E_WARNING);
    }

    public static function fileTypeNotAllowed(string $name): FileUploadException
    {
        return new static(_message(self::FILE_TYPE_NOT_ALLOWED, $name), E_WARNING);
    }
}