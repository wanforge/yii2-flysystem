<?php

namespace diecoding\flysystem\tests\traits;

use diecoding\flysystem\traits\EncrypterTrait;
use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;
use yii\console\Application;

class EncrypterTraitTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        if (\Yii::$app === null) {
            new Application([
                'id' => 'flysystem-test',
                'basePath' => dirname(__DIR__, 2),
            ]);
        }
    }

    public static function tearDownAfterClass(): void
    {
        \Yii::$app = null;
    }

    public function testEncryptAndDecryptRoundTrip(): void
    {
        $subject = new class () {
            use EncrypterTrait;
        };

        $subject->initEncrypter('my-secret-passphrase');

        $encrypted = $subject->encrypt('hello-world');

        $this->assertIsString($encrypted);
        $this->assertNotSame('hello-world', $encrypted);
        $this->assertSame('hello-world', $subject->decrypt($encrypted));
    }

    public function testInitEncrypterAcceptsStringZero(): void
    {
        $subject = new class () {
            use EncrypterTrait;
        };

        $subject->initEncrypter('0');

        $encrypted = $subject->encrypt('safe');
        $this->assertSame('safe', $subject->decrypt($encrypted));
    }

    public function testInitEncrypterThrowsOnEmptyPassphrase(): void
    {
        $subject = new class () {
            use EncrypterTrait;
        };

        $this->expectException(InvalidConfigException::class);
        $subject->initEncrypter('');
    }
}
