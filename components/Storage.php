<?php

declare(strict_types=1);

namespace app\components;

use Exception;
use Intervention\Image\ImageManager;
use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * File storage component.
 *
 * @property string $storagePath
 */
class Storage extends Component implements StorageInterface
{
    private $fileName;

    /**
     * Save given UploadedFile instance to disk.
     *
     * @param UploadedFile $file
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\base\Exception
     *
     * @return string|null
     */
    public function saveUploadedFile(UploadedFile $file): ?string
    {
        $manager = new ImageManager(['driver' => 'imagick']);

        $path = $this->preparePath($file);

        $isFileSaved = $path && $file->saveAs($path);

        if ($isFileSaved) {
            $manager
                ->make($path)
                ->widen(1024)
                ->save($path, 75)
                ->destroy();

            return $this->fileName;
        }

        return null;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getFile(string $fileName): string
    {
        return Yii::$app->params['storageUri'].$fileName;
    }

    /**
     * @param string $fileName
     *
     * @throws \yii\base\InvalidArgumentException
     *
     * @return bool
     */
    public function deleteFile(string $fileName): bool
    {
        $filePath = $this->getStoragePath().$fileName;

        try {
            $result = \unlink($filePath);
        } catch (Exception $exception) {
            Yii::error($exception->getMessage());
            $result = false;
        }

        return $result;
    }

    /**
     * Prepare path to save uploaded file.
     *
     * @param UploadedFile $file
     *
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\base\Exception
     *
     * @return string|null
     */
    protected function preparePath(UploadedFile $file): ?string
    {
        $this->fileName = $this->getFileName($file);

        $path = $this->getStoragePath().$this->fileName;

        $path = FileHelper::normalizePath($path);
        $isDirectoryCreated = FileHelper::createDirectory(\dirname($path));

        if ($isDirectoryCreated) {
            return $path;
        }

        return null;
    }

    /**
     * Generate a new hash name for an uploaded file.
     * First two slashes indicate separate folders.
     * Example: c2/2b/5f9178342609428d6f51b2c5af4c0bde6a42.jpg.
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function getFileName(UploadedFile $file): string
    {
        $hash = \sha1_file($file->tempName);

        $name = \substr_replace($hash, '/', 2, 0);
        $name = \substr_replace($name, '/', 5, 0);

        return "{$name}.{$file->extension}";
    }

    /**
     * Resolve storage path location.
     *
     * @throws \yii\base\InvalidArgumentException
     *
     * @return string
     */
    protected function getStoragePath(): string
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }
}
