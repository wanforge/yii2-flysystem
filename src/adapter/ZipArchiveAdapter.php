<?php

declare(strict_types=1);

namespace diecoding\flysystem\adapter;

use diecoding\flysystem\traits\ChecksumAdapterTrait;
use diecoding\flysystem\traits\UrlGeneratorAdapterTrait;
use League\Flysystem\ChecksumProvider;
use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnixVisibility\VisibilityConverter;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;
use League\Flysystem\UrlGeneration\TemporaryUrlGenerator;
use League\Flysystem\ZipArchive\ZipArchiveAdapter as LeagueZipArchiveAdapter;
use League\Flysystem\ZipArchive\ZipArchiveProvider;
use League\MimeTypeDetection\MimeTypeDetector;

/**
 * This properties for fixed issue https://scrutinizer-ci.com/g/sugeng-sulistiyawan/yii2-flysystem/inspections/22f77797-37d0-419a-9447-ab6111572e99/issues/?status=all
 * @property string $action
 * @property string $prefix
 */
final class ZipArchiveAdapter implements FilesystemAdapter, ChecksumProvider, PublicUrlGenerator, TemporaryUrlGenerator
{
    use UrlGeneratorAdapterTrait, ChecksumAdapterTrait;

    /**
     * @var bool
     */
    public $skipPrefixer = true;

    /**
     * @var LeagueZipArchiveAdapter
     */
    private $_adapter;

    public function __construct(
        private ZipArchiveProvider $zipArchiveProvider,
        string $root = '',
        ?MimeTypeDetector $mimeTypeDetector = null,
        ?VisibilityConverter $visibility = null,
        private bool $detectMimeTypeUsingPath = false,
    ) {
        $this->setAdapter(new LeagueZipArchiveAdapter($zipArchiveProvider, $root, $mimeTypeDetector, $visibility, $detectMimeTypeUsingPath));
    }

    /**
     * @return LeagueZipArchiveAdapter
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * @param LeagueZipArchiveAdapter $value
     * @return void
     */
    public function setAdapter(LeagueZipArchiveAdapter $value)
    {
        $this->_adapter = $value;
    }

    public function fileExists(string $path): bool
    {
        return $this->getAdapter()->fileExists($path);
    }

    public function write(string $path, string $contents, Config $config): void
    {
        $this->getAdapter()->write($path, $contents, $config);
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $this->getAdapter()->writeStream($path, $contents, $config);
    }

    public function read(string $path): string
    {
        return $this->getAdapter()->read($path);
    }

    public function readStream(string $path)
    {
        return $this->getAdapter()->readStream($path);
    }

    public function delete(string $path): void
    {
        $this->getAdapter()->delete($path);
    }

    public function deleteDirectory(string $path): void
    {
        $this->getAdapter()->deleteDirectory($path);
    }

    public function createDirectory(string $path, Config $config): void
    {
        $this->getAdapter()->createDirectory($path, $config);
    }

    public function directoryExists(string $path): bool
    {
        return $this->getAdapter()->directoryExists($path);
    }

    public function setVisibility(string $path, string $visibility): void
    {
        $this->getAdapter()->setVisibility($path, $visibility);
    }

    public function visibility(string $path): FileAttributes
    {
        return $this->getAdapter()->visibility($path);
    }

    public function mimeType(string $path): FileAttributes
    {
        return $this->getAdapter()->mimeType($path);
    }

    public function lastModified(string $path): FileAttributes
    {
        return $this->getAdapter()->lastModified($path);
    }

    public function fileSize(string $path): FileAttributes
    {
        return $this->getAdapter()->fileSize($path);
    }

    public function listContents(string $path, bool $deep): iterable
    {
        return $this->getAdapter()->listContents($path, $deep);
    }

    public function move(string $source, string $destination, Config $config): void
    {
        $this->getAdapter()->move($source, $destination, $config);
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        $this->getAdapter()->copy($source, $destination, $config);
    }
}
