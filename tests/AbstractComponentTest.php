<?php

namespace diecoding\flysystem\tests;

use DateTimeImmutable;
use diecoding\flysystem\AbstractComponent;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;

class AbstractComponentTest extends TestCase
{
    public function testNormalizePathRemovesDuplicateSeparators(): void
    {
        $component = $this->createComponent();

        $normalized = $component->normalizePath('foo//bar///baz.txt');

        $this->assertSame('foo/bar/baz.txt', $normalized);
    }

    public function testConvertToDateTimeFromString(): void
    {
        $component = $this->createComponent();

        $value = $component->convertToDateTime('+5 minutes');

        $this->assertInstanceOf(DateTimeImmutable::class, $value);
    }

    public function testConvertToDateTimeFromDateTimeInterface(): void
    {
        $component = $this->createComponent();
        $input = new DateTimeImmutable('2026-01-01 10:00:00');

        $value = $component->convertToDateTime($input);

        $this->assertInstanceOf(DateTimeImmutable::class, $value);
        $this->assertSame($input->getTimestamp(), $value->getTimestamp());
    }

    public function testValidatePropertiesThrowsWhenRequiredPropertyIsMissing(): void
    {
        $component = $this->createComponent();

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('The "bucket" property must be set.');

        $component->validateProperties(['bucket']);
    }

    public function testValidatePropertiesAcceptsSetProperty(): void
    {
        $component = $this->createComponent();
        $component->bucket = 'my-bucket';

        $component->validateProperties(['bucket']);

        $this->assertTrue(true);
    }

    private function createComponent(): AbstractComponent
    {
        return new class () extends AbstractComponent {
            public $bucket;

            protected function initAdapter(): FilesystemAdapter
            {
                return new LocalFilesystemAdapter(sys_get_temp_dir());
            }
        };
    }
}
