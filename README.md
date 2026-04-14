# Yii2 Flysystem

The League Flysystem for local and remote filesystems library for Yii2.

This extension provides [Flysystem 3](https://flysystem.thephpleague.com) integration for the Yii framework.
[Flysystem](https://flysystem.thephpleague.com) is a filesystem abstraction which allows you to easily swap out a local filesystem for a remote one.

[![Latest Stable Version](https://img.shields.io/packagist/v/diecoding/yii2-flysystem?label=stable)](https://packagist.org/packages/diecoding/yii2-flysystem)
[![Total Downloads](https://img.shields.io/packagist/dt/diecoding/yii2-flysystem)](https://packagist.org/packages/diecoding/yii2-flysystem)
[![Latest Stable Release Date](https://img.shields.io/github/release-date/wanforge/yii2-flysystem)](https://github.com/wanforge/yii2-flysystem)
[![CI](https://github.com/wanforge/yii2-flysystem/actions/workflows/php.yml/badge.svg)](https://github.com/wanforge/yii2-flysystem/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/wanforge/yii2-flysystem/branch/main/graph/badge.svg)](https://app.codecov.io/gh/wanforge/yii2-flysystem)
[![License](https://img.shields.io/github/license/wanforge/yii2-flysystem)](https://github.com/wanforge/yii2-flysystem)
[![PHP Version Require](https://img.shields.io/packagist/dependency-v/diecoding/yii2-flysystem/php?color=6f73a6)](https://packagist.org/packages/diecoding/yii2-flysystem)

> Yii2 Flysystem uses [league/flysystem](https://github.com/thephpleague/flysystem)

## Table of Contents

- [Yii2 Flysystem](#yii2-flysystem)
  - [Table of Contents](#table-of-contents)
  - [Instalation](#instalation)
  - [Dependencies](#dependencies)
  - [Dev. Dependencies](#dev-dependencies)
  - [Configuration](#configuration)
    - [Local Filesystem](#local-filesystem)
    - [AsyncAws S3 Filesystem](#asyncaws-s3-filesystem)
    - [AWS S3 Filesystem](#aws-s3-filesystem)
    - [Google Cloud Storage Filesystem](#google-cloud-storage-filesystem)
    - [FTP Filesystem](#ftp-filesystem)
    - [SFTP Filesystem](#sftp-filesystem)
    - [WebDAV Filesystem](#webdav-filesystem)
    - [ZipArchive Filesystem](#ziparchive-filesystem)
    - [Google Drive Filesystem](#google-drive-filesystem)
  - [Additional Configuration](#additional-configuration)
    - [URL File Action Settings](#url-file-action-settings)
    - [Global Visibility Settings](#global-visibility-settings)
  - [Usage](#usage)
    - [Writing or Updating Files](#writing-or-updating-files)
    - [Reading Files](#reading-files)
    - [Checking if a File Exists](#checking-if-a-file-exists)
    - [Deleting Files](#deleting-files)
    - [Getting Files Mime Type](#getting-files-mime-type)
    - [Getting Files Timestamp / Last Modified](#getting-files-timestamp--last-modified)
    - [Getting Files Size](#getting-files-size)
    - [Creating Directories](#creating-directories)
    - [Checking if a Directory Exists](#checking-if-a-directory-exists)
    - [Deleting Directories](#deleting-directories)
    - [Checking if a File or Directory Exists](#checking-if-a-file-or-directory-exists)
    - [Managing Visibility](#managing-visibility)
    - [Listing contents](#listing-contents)
    - [Copy Files or Directories](#copy-files-or-directories)
    - [Move Files or Directories](#move-files-or-directories)
    - [Get URL Files](#get-url-files)
    - [Get URL Temporary Files / Presigned URL](#get-url-temporary-files--presigned-url)
    - [Get MD5 Hash File Contents](#get-md5-hash-file-contents)
  - [Using Traits](#using-traits)
    - [Model Trait](#model-trait)
      - [Using Trait Methods](#using-trait-methods)
      - [Overriding Trait Methods](#overriding-trait-methods)
        - [getFsComponent](#getfscomponent)
        - [attributePaths](#attributepaths)
        - [getPresignedUrlDuration](#getpresignedurlduration)

## Instalation

Package is available on [Packagist](https://packagist.org/packages/diecoding/yii2-flysystem), you can install it using [Composer](https://getcomposer.org).

```shell
composer require diecoding/yii2-flysystem "^1.0"
```

or add to the require section of your `composer.json` file.

```shell
"diecoding/yii2-flysystem": "^1.0"
```

## Dependencies

- PHP 8.0+
- [yiisoft/yii2](https://github.com/yiisoft/yii2)
- [league/flysystem](https://github.com/thephpleague/flysystem)
- [league/flysystem-path-prefixing](https://github.com/thephpleague/flysystem-path-prefixing)

## Dev. Dependencies

- [league/flysystem-async-aws-s3](https://github.com/thephpleague/flysystem-async-aws-s3)
- [league/flysystem-aws-s3-v3](https://github.com/thephpleague/flysystem-aws-s3-v3)
- [league/flysystem-google-cloud-storage](https://github.com/thephpleague/flysystem-google-cloud-storage)
- [league/flysystem-ftp](https://github.com/thephpleague/flysystem-ftp)
- [league/flysystem-sftp-v3](https://github.com/thephpleague/flysystem-sftp-v3)
- [league/flysystem-webdav](https://github.com/thephpleague/flysystem-webdav)
- [league/flysystem-ziparchive](https://github.com/thephpleague/flysystem-ziparchive)
- [masbug/flysystem-google-drive-ext](https://github.com/masbug/flysystem-google-drive-ext)
- [phpunit/phpunit](https://github.com/sebastianbergmann/phpunit)

## Testing

Run unit tests:

```shell
composer test
```

Run tests with coverage report (`coverage.xml`):

```shell
composer test-coverage
```

## Configuration

### Local Filesystem

Configure application `components` as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            'class' => \diecoding\flysystem\LocalComponent::class,
            'path' => dirname(dirname(__DIR__)) . '/storage', // or you can use @alias
            'secret' => 'my-secret', // for secure route url
            // 'action' => '/site/file', // action route
            // 'prefix' => '',
        ],
    ],
];
```

### AsyncAws S3 Filesystem

See: [league/flysystem-async-aws-s3](https://github.com/thephpleague/flysystem-async-aws-s3)

Either run

```shell
composer require league/flysystem-async-aws-s3:^3.0
```

or add

```shell
"league/flysystem-async-aws-s3": "^3.0"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            'class' => \diecoding\flysystem\AsyncAwsS3Component::class,
            'endpoint' => 'http://your-endpoint',
            'bucket' => 'my-bucket',
            'accessKeyId' => 'my-key',
            'accessKeySecret' => 'my-secret',
            // 'sharedCredentialsFile' => '~/.aws/credentials',
            // 'sharedConfigFile' => '~/.aws/config',
            // 'region' => 'us-east-1',
            // 'endpointDiscoveryEnabled' => false,
            // 'pathStyleEndpoint' => false,
            // 'sendChunkedBody' => false,
            // 'debug' => false,
            // 'prefix' => '',
        ],
    ],
];
```

### AWS S3 Filesystem

See: [league/flysystem-aws-s3-v3](https://github.com/thephpleague/flysystem-aws-s3-v3)

Either run

```shell
composer require league/flysystem-aws-s3-v3:^3.0
```

or add

```shell
"league/flysystem-aws-s3-v3": "^3.0"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            'class' => \diecoding\flysystem\AwsS3Component::class,
            'endpoint' => 'http://your-endpoint',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            // 'region' => 'us-east-1',
            // 'version' => 'latest',
            // 'usePathStyleEndpoint' => false,
            // 'streamReads' => false,
            // 'options' => [],
            // 'credentials' => [],
            // 'debug' => false,
            // 'prefix' => '',
        ],
    ],
];
```

### Google Cloud Storage Filesystem

See: [league/flysystem-google-cloud-storage](https://github.com/thephpleague/flysystem-google-cloud-storage)

Either run

```shell
composer require league/flysystem-google-cloud-storage:^3.0
```

or add

```shell
"league/flysystem-google-cloud-storage": "^3.0"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            'class' => \diecoding\flysystem\GoogleCloudStorageComponent::class,
            'bucket' => 'your-bucket',
            // 'apiEndpoint' => '',
            // 'projectId' => '',
            // 'authCache' => null,
            // 'authCacheOptions' => [],
            // 'authHttpHandler' => function () {},
            // 'credentialsFetcher' => null,
            // 'httpHandler' => function () {},
            // 'keyFile' => '',
            'keyFilePath' => __DIR__ . '/gcs_credentials.json',
            // 'requestTimeout' => 0,
            // 'retries' => 0,
            // 'scopes' => [],
            // 'quotaProject' => '',
            // 'userProject' => false,
            // 'prefix' => '',
        ],
    ],
];
```

### FTP Filesystem

See: [league/flysystem-ftp](https://github.com/thephpleague/flysystem-ftp)

Either run

```shell
composer require league/flysystem-ftp:^3.0
```

or add

```shell
"league/flysystem-ftp": "^3.0"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            'class' => \diecoding\flysystem\FtpComponent::class,
            'host' => 'hostname',
            'root' => '/root/path/', // or you can use @alias
            'username' => 'username',
            'password' => 'password',
            // 'port' => 21,
            // 'ssl' => false,
            // 'timeout' => 90,
            // 'utf8' => false,
            // 'passive' => true,
            // 'transferMode' => FTP_BINARY,
            // 'systemType' => null, // 'windows' or 'unix'
            // 'ignorePassiveAddress' => null, // true or false
            // 'timestampsOnUnixListingsEnabled' => false,
            // 'recurseManually' => true,
            // 'useRawListOptions' => null, // true or false
            // 'passphrase' => 'secret', // for secure route url
            // 'action' => '/site/file', // action route
            // 'prefix' => '',
        ],
    ],
];
```

### SFTP Filesystem

See: [league/flysystem-sftp-v3](https://github.com/thephpleague/flysystem-sftp-v3)

Either run

```shell
composer require league/flysystem-sftp-v3:^3.0
```

or add

```shell
"league/flysystem-sftp-v3": "^3.0"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    // ...
    'components' => [
        'fs' => [
            'class' => \diecoding\flysystem\SftpComponent::class,
            'host' => 'hostname',
            'username' => 'username',
            'password' => null, // password (optional, default: null) set to null if privateKey is used
            // 'privateKey' => '/path/to/my/private_key', // private key (optional, default: null) can be used instead of password, set to null if password is set
            // 'passphrase' => 'super-secret-password', // passphrase (optional, default: null), set to null if privateKey is not used or has no passphrase
            // 'port' => 22,
            // 'useAgent' => true,
            // 'timeout' => 10,
            // 'maxTries' => 4,
            // 'hostFingerprint' => null,
            // 'connectivityChecker' => null, // connectivity checker (must be an implementation of `League\Flysystem\PhpseclibV2\ConnectivityChecker` to check if a connection can be established (optional, omit if you don't need some special handling for setting reliable connections)
            // 'preferredAlgorithms' => [],
            // 'root' => '/root/path/', // or you can use @alias
            // 'action' => '/site/file', // action route
            // 'prefix' => '',
        ],
    ],
];
```

### WebDAV Filesystem

See: [league/flysystem-webdav](https://github.com/thephpleague/flysystem-webdav)

Either run

```shell
composer require league/flysystem-webdav:^3.0
```

or add

```shell
"league/flysystem-webdav": "^3.0"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            'class' => \diecoding\flysystem\WebDavComponent::class,
            'baseUri' => 'http://your-webdav-server.org/',
            'userName' => 'your_user',
            'password' => 'superSecret1234',
            // 'proxy' => '',
            // 'authType' => \Sabre\DAV\Client::AUTH_BASIC,
            // 'encoding' => \Sabre\DAV\Client::ENCODING_IDENTITY,
            // 'prefix' => '',
        ],
    ],
];
```

### ZipArchive Filesystem

See: [league/flysystem-ziparchive](https://github.com/thephpleague/flysystem-ziparchive)

Either run

```shell
composer require league/flysystem-ziparchive:^3.0
```

or add

```shell
"league/flysystem-ziparchive": "^3.0"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            'class' => \diecoding\flysystem\ZipArchiveComponent::class,
            'pathToZip' => dirname(dirname(__DIR__)) . '/storage.zip', // or you can use @alias
            'secret' => 'my-secret', // for secure route url
            // 'action' => '/site/file', // action route
            // 'prefix' => '', // root directory inside zip file
        ],
    ],
];
```

### Google Drive Filesystem

See: [masbug/flysystem-google-drive-ext](https://github.com/masbug/flysystem-google-drive-ext)

Either run

```shell
composer require masbug/flysystem-google-drive-ext:^2.0
```

or add

```shell
"masbug/flysystem-google-drive-ext": "^2.0"
```

to the `require` section of your `composer.json` file and configure application `components` as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            'class' => \diecoding\flysystem\GoogleDriveComponent::class,
            'applicationName' => 'My Google Drive App',
            'clientId' => '',
            'clientSecret' => '',
            'refreshToken' => '',
            // 'teamDriveId' => '',
            // 'sharedFolderId' => '',
            // 'options' => [],
            'secret' => 'my-secret', // for secure route url
            // 'action' => '/site/file', // action route
            // 'prefix' => '',
        ],
    ],
];
```

## Additional Configuration

### URL File Action Settings

The following adapters have URL File Action generation capabilities:

- Local Component
- FTP Component
- SFTP Component
- Google Drive Component

Configure `action` in `controller` as follows

> This example at `SiteController` for `/site/file`

```php
class SiteController extends Controller
{
    //...
    public function actions()
    {
        return [
            // ...
            'file' => [
                'class' => \diecoding\flysystem\actions\FileAction::class,
                // 'component' => 'fs',
            ],
        ];
    }
}
```

> Remember to configure `action` key in `fs` application components as follows

```php
return [
    // ...
    'components' => [
        // ...
        'fs' => [
            // ...
            'action' => '/site/file', // action for get url file
        ],
    ],
];
```

### Global Visibility Settings

Configure `fs` application component as follows

```php
return [
    //...
    'components' => [
        //...
        'fs' => [
            //...
            'config' => [
                'visibility' => \League\Flysystem\Visibility::PRIVATE,
            ],
        ],
    ],
];
```

## Usage

### Writing or Updating Files

To write or update file

```php
Yii::$app->fs->write('filename.ext', 'contents');
```

To write or update file using stream contents

```php
$stream = fopen('/path/to/somefile.ext', 'r+');
Yii::$app->fs->writeStream('filename.ext', $stream);
```

### Reading Files

To read file

```php
$contents = Yii::$app->fs->read('filename.ext');
```

To retrieve a read-stream

```php
$stream = Yii::$app->fs->readStream('filename.ext');
$contents = stream_get_contents($stream);
fclose($stream);
```

### Checking if a File Exists

To check if a file exists

```php
$exists = Yii::$app->fs->fileExists('filename.ext');
```

### Deleting Files

To delete file

```php
Yii::$app->fs->delete('filename.ext');
```

### Getting Files Mime Type

To get file mime type

```php
$mimeType = Yii::$app->fs->mimeType('filename.ext');
```

### Getting Files Timestamp / Last Modified

To get file timestamp

```php
$timestamp = Yii::$app->fs->lastModified('filename.ext');
```

### Getting Files Size

To get file size

```php
$byte = Yii::$app->fs->fileSize('filename.ext');
```

### Creating Directories

To create directory

```php
Yii::$app->fs->createDirectory('path/to/directory');
```

Directories are also made implicitly when writing to a deeper path

```php
Yii::$app->fs->write('path/to/filename.ext');
```

### Checking if a Directory Exists

To check if a directory exists

```php
$exists = Yii::$app->fs->directoryExists('path/to/directory');
```

### Deleting Directories

To delete directory

```php
Yii::$app->fs->deleteDirectory('path/to/directory');
```

### Checking if a File or Directory Exists

To check if a file or directory exists

```php
$exists = Yii::$app->fs->has('path/to/directory/filename.ext');
```

### Managing Visibility

Visibility is the abstraction of file permissions across multiple platforms. Visibility can be either public or private.

```php
Yii::$app->fs->write('filename.ext', 'contents', [
    'visibility' => \League\Flysystem\Visibility::PRIVATE
]);
```

You can also change and check visibility of existing files

```php
if (Yii::$app->fs->visibility('filename.ext') === \League\Flysystem\Visibility::PRIVATE) {
    Yii::$app->fs->setVisibility('filename.ext', \League\Flysystem\Visibility::PUBLIC);
}
```

### Listing contents

To list contents

```php
$contents = Yii::$app->fs->listContents();

foreach ($contents as $object) {
    echo $object['basename']
        . ' is located at' . $object['path']
        . ' and is a ' . $object['type'];
}
```

By default Flysystem lists the top directory non-recursively. You can supply a directory name and recursive boolean to get more precise results

```php
$contents = Yii::$app->fs->listContents('path/to/directory', true);
```

### Copy Files or Directories

To copy contents

```php
Yii::$app->fs->copy('path/from/directory/filename.ext', 'path/to/directory/filename.ext', [
    'visibility' => \League\Flysystem\Visibility::PRIVATE
]);
```

### Move Files or Directories

To move contents

```php
Yii::$app->fs->move('path/from/directory/filename.ext', 'path/to/directory/filename.ext', [
    'visibility' => \League\Flysystem\Visibility::PRIVATE
]);
```

### Get URL Files

To get url contents

```php
Yii::$app->fs->publicUrl('path/to/directory/filename.ext');
```

### Get URL Temporary Files / Presigned URL

To get temporary url contents

```php
$expiresAt = new \DateTimeImmutable('+10 Minutes');

Yii::$app->fs->temporaryUrl('path/to/directory/filename.ext', $expiresAt);
```

The `$expiresAt` should be a valid and instance of `PHP DateTimeInterface`. Read [PHP documentation](https://www.php.net/manual/en/class.datetimeinterface.php) for details.

### Get MD5 Hash File Contents

To get MD5 hash of the file contents

```php
Yii::$app->fs->checksum('path/to/directory/filename.ext');
```

## Using Traits

### Model Trait

Attach the Trait to the `Model/ActiveRecord` with some media attribute that will be saved in Flysystem (fs):

```php
/**
 * @property string|null $file
 */
class Model extends \yii\db\ActiveRecord
{
    use \diecoding\flysystem\traits\ModelTrait;

    // ...

    public function rules()
    {
        return [
            ['image', 'string'], // Stores the filename
        ];
    }

    /**
     * @inheritdoc
     */
    protected function attributePaths()
    {
        return [
            'image' => 'images/'
        ];
    }

    // ...
}
```

Override the `attributePaths()` method to change the base path where the files will be saved on Flysystem (fs).

- You can map a different path to each file attribute of your `Model/ActiveRecord`.

#### Using Trait Methods

```php
$image = \yii\web\UploadedFile::getInstance($model, 'image');

// Save image_thumb.* to Flysystem (fs) on //my_bucket/images/ path
// The extension of the file will be determined by the submitted file type
// This allows multiple file types upload (png,jpg,gif,...)
// $model->image will hold "image_thumb.png" after this call finish with success
$model->saveUploadedFile($image, 'image', 'image_thumb');
$model->save();

// Save image_thumb.png to Flysystem (fs) on //my_bucket/images/ path
// The extension of the file will be determined by the submitted file type
// This force the extension to *.png
$model->saveUploadedFile($image, 'image', 'image_thumb.png', false);
$model->save();

// Remove the file with named saved on the image attribute
// Continuing the example, here "//my_bucket/images/my_image.png" will be deleted from Flysystem (fs)
$model->removeFile('image');
$model->save();

// Get the URL to the image on Flysystem (fs)
$model->getFileUrl('image');

// Get the presigned URL to the image on Flysystem (fs)
// The default duration is "+5 Minutes"
$model->getFilePresignedUrl('image');
```

#### Overriding Trait Methods

##### getFsComponent

The Flysystem (fs) MediaTrait depends on this component to be configured. The default configuration is to use this component on index `'fs'`, but you may use another value. For this cases, override the `getFsComponent()` method:

```php
public function getFsComponent()
{
    return Yii::$app->get('my_fs_component');
}
```

##### attributePaths

The main method to override is `attributePaths()`, which defines a path in Flysystem (fs) for each attribute of yout model. Allowing you to save each attribute in a different Flysystem (fs) folder.

Here an example:

```php
protected function attributePaths()
{
    return [
        'logo' => 'logos/',
        'badge' => 'images/badges/'
    ];
}

// or use another attribute, example: id
// ! Note: id must contain a value first if you don't want it to be empty

protected function attributePaths()
{
    return [
        'logo' => 'thumbnail/' . $this->id . '/logos/',
        'badge' => 'thumbnail/' . $this->id . '/images/badges/'
    ];
}
```

##### getPresignedUrlDuration

The default pressigned URL duration is set to "+5 Minutes", override this method and use your own expiration.
Return must instance of `DateTimeInterface`

```php
protected function getPresignedUrlDuration($attribute)
{
    return new \DateTimeImmutable('+2 Hours');
}

// or if you want to set the attribute differently

protected function getPresignedUrlDuration($attribute)
{
    switch ($attribute) {
        case 'badge':
            return new \DateTimeImmutable('+2 Hours');
            break;
        
        default:
            return new \DateTimeImmutable('+1 Days');
            break;
    }
}

```

The value should be a valid and instance of `PHP DateTimeInterface`. Read [PHP documentation](https://www.php.net/manual/en/class.datetimeinterface.php) for details.

---

Read more docs: <https://sugengsulistiyawan.my.id/docs/opensource/yii2/flysystem/>
