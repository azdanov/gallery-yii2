<?php

declare(strict_types=1);

namespace app\components;

use yii\web\UploadedFile;

/**
 * Interface StorageInterface.
 */
interface StorageInterface
{
    /**
     * Save an uploaded file.
     *
     * @param UploadedFile $file
     *
     * @return string|null
     */
    public function saveUploadedFile(UploadedFile $file): ?string;

    /**
     * Get a file by its filename.
     *
     * @param string $fileName
     *
     * @return string
     */
    public function getFile(string $fileName): string;

    /**
     * Delete a file by its name.
     *
     * @param string $fileName
     *
     * @return bool
     */
    public function deleteFile(string $fileName): bool;
}
