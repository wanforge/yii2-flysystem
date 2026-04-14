<?php

namespace diecoding\flysystem\tests\traits;

use DateTimeImmutable;
use diecoding\flysystem\traits\ModelTrait;
use PHPUnit\Framework\TestCase;
use yii\web\UploadedFile;

class ModelTraitTest extends TestCase
{
    public function testSaveUploadedFileWritesToFilesystemAndUpdatesAttribute(): void
    {
        $filesystem = new DummyFilesystem();
        $model = new DummyModel($filesystem);

        $tmp = tempnam(sys_get_temp_dir(), 'flysystem-test-');
        file_put_contents($tmp, 'binary-content');

        $file = new UploadedFile([
            'name' => 'avatar.jpg',
            'tempName' => $tmp,
            'type' => 'image/jpeg',
            'size' => filesize($tmp),
            'error' => UPLOAD_ERR_OK,
        ]);

        $model->saveUploadedFile($file, 'avatar');

        $this->assertSame('avatar.jpg', $model->avatar);
        $this->assertSame('images/avatar.jpg', $filesystem->lastWritePath);
        $this->assertSame('binary-content', $filesystem->lastWriteContents);

        @unlink($tmp);
    }

    public function testSaveUploadedFileReturnsEarlyWhenModelHasErrors(): void
    {
        $filesystem = new DummyFilesystem();
        $model = new DummyModel($filesystem);
        $model->shouldHaveErrors = true;

        $tmp = tempnam(sys_get_temp_dir(), 'flysystem-test-');
        file_put_contents($tmp, 'data');

        $file = new UploadedFile([
            'name' => 'avatar.jpg',
            'tempName' => $tmp,
            'type' => 'image/jpeg',
            'size' => filesize($tmp),
            'error' => UPLOAD_ERR_OK,
        ]);

        $model->saveUploadedFile($file, 'avatar');

        $this->assertNull($filesystem->lastWritePath);

        @unlink($tmp);
    }

    public function testRemoveFileDeletesAndResetsAttribute(): void
    {
        $filesystem = new DummyFilesystem();
        $model = new DummyModel($filesystem);
        $model->avatar = 'avatar.jpg';

        $model->removeFile('avatar');

        $this->assertSame('images/avatar.jpg', $filesystem->lastDeletePath);
        $this->assertNull($model->avatar);
    }

    public function testGetFileUrlReturnsEmptyWhenAttributeNotSet(): void
    {
        $model = new DummyModel(new DummyFilesystem());

        $this->assertSame('', $model->getFileUrl('avatar'));
    }

    public function testGetFileUrlReturnsPublicUrlWhenAttributeExists(): void
    {
        $filesystem = new DummyFilesystem();
        $model = new DummyModel($filesystem);
        $model->avatar = 'avatar.jpg';

        $url = $model->getFileUrl('avatar');

        $this->assertSame('https://example.test/public/images/avatar.jpg', $url);
    }

    public function testGetFilePresignedUrlReturnsTemporaryUrlWhenAttributeExists(): void
    {
        $filesystem = new DummyFilesystem();
        $model = new DummyModel($filesystem);
        $model->avatar = 'avatar.jpg';

        $url = $model->getFilePresignedUrl('avatar');

        $this->assertStringContainsString('https://example.test/temp/images/avatar.jpg?expires=', $url);
    }
}

class DummyModel
{
    use ModelTrait;

    public $avatar;
    public $shouldHaveErrors = false;

    private $filesystem;

    public function __construct(DummyFilesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getFsComponent()
    {
        return $this->filesystem;
    }

    protected function attributePaths()
    {
        return [
            'avatar' => 'images',
        ];
    }

    public function hasErrors($attribute = null)
    {
        return $this->shouldHaveErrors;
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        return true;
    }
}

class DummyFilesystem
{
    public $lastWritePath;
    public $lastWriteContents;
    public $lastDeletePath;

    public function normalizePath($path)
    {
        return trim(str_replace('//', '/', $path), '/');
    }

    public function write($path, $contents)
    {
        $this->lastWritePath = $path;
        $this->lastWriteContents = $contents;
    }

    public function delete($path)
    {
        $this->lastDeletePath = $path;
    }

    public function publicUrl($path)
    {
        return 'https://example.test/public/' . $path;
    }

    public function temporaryUrl($path, $expiresAt)
    {
        return 'https://example.test/temp/' . $path . '?expires=' . $expiresAt->getTimestamp();
    }

    public function convertToDateTime($dateValue)
    {
        return new DateTimeImmutable($dateValue);
    }
}
