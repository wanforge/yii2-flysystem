<?php

namespace diecoding\flysystem\traits;

use Yii;
use yii\web\UploadedFile;

/**
 * Trait ModelTrait for Model
 * 
 * @link      https://sugengsulistiyawan.my.id/
 * @author    Sugeng Sulistiyawan <sugeng.sulistiyawan@gmail.com>
 * @copyright Copyright (c) 2023
 */
trait ModelTrait
{
    /**
     * @return \diecoding\flysystem\AbstractComponent|mixed
     */
    public function getFsComponent()
    {
        return Yii::$app->get('fs');
    }

    /**
     * Save UploadedFile.
     * ! Important: This function uploads this model filename to keep consistency of the model.
     * 
     * @param UploadedFile $file Uploaded file to save
     * @param string $attribute Attribute name where the uploaded filename name will be saved
     * @param string $fileName Name which file will be saved. If empty will use the name from $file
     * @param bool $autoExtension `true` to automatically append or replace the extension to the file name. Default is `true`
     * 
     * @return void
     */
    public function saveUploadedFile(UploadedFile $file, $attribute, $fileName = '', $autoExtension = true)
    {
        if ($this->hasErrors()) {
            return;
        }
        if (empty($fileName)) {
            $fileName = $file->name;
        }
        if ($autoExtension) {
            $_file = (string) pathinfo($fileName, PATHINFO_FILENAME);
            $fileName = $_file . '.' . $file->extension;
        }

        $this->{$attribute} = $fileName;
        if (!$this->validate($attribute)) {
            return;
        }
        $filePath = $this->getAttributePath($attribute) . '/' . $fileName;
        $localPath = $file->tempName;
        $contents = @file_get_contents($localPath);
        if ($contents === false) {
            return;
        }

        $filesystem = $this->getFsComponent();
        $filesystem->write($filesystem->normalizePath($filePath), $contents);
    }

    /**
     * Delete model file attribute.
     * 
     * @param string $attribute Attribute name which holds the filename
     * 
     * @return void
     */
    public function removeFile($attribute)
    {
        if (empty($this->{$attribute})) {
            return;
        }
        $filePath = $this->getAttributePath($attribute) . '/' . $this->{$attribute};
        $filesystem = $this->getFsComponent();
        $filesystem->delete($filesystem->normalizePath($filePath));

        $this->{$attribute} = null;
    }

    /**
     * Retrieves the URL for a given model file attribute.
     * 
     * @param string $attribute Attribute name which holds the filename
     * 
     * @return string URL to access file
     */
    public function getFileUrl($attribute)
    {
        if (empty($this->{$attribute})) {
            return '';
        }
        $filePath = $this->getAttributePath($attribute) . '/' . $this->{$attribute};
        $filesystem = $this->getFsComponent();

        return $filesystem->publicUrl($filesystem->normalizePath($filePath));
    }

    /**
     * Retrieves the presigned URL for a given model file attribute.
     * 
     * @param string $attribute Attribute name which holds the filename
     * 
     * @return string Presigned URL to access file
     */
    public function getFilePresignedUrl($attribute)
    {
        if (empty($this->{$attribute})) {
            return '';
        }
        $filePath = $this->getAttributePath($attribute) . '/' . $this->{$attribute};
        $filesystem = $this->getFsComponent();

        return $filesystem->temporaryUrl($filesystem->normalizePath($filePath), $this->getPresignedUrlDuration($attribute));
    }

    /**
     * List the paths on filesystem to each model file attribute.
     * It must be a Key-Value array, where Key is the attribute name and Value is the base path for the file in filesystem.
     * Override this method for saving each attribute in its own "folder".
     * 
     * @return array Key-Value of attributes and its paths.
     */
    protected function attributePaths()
    {
        return [];
    }

    /**
     * Retrieves the URL signature expiration.
     * 
     * @param string $attribute Attribute name which holds the duration
     * 
     * @return \DateTimeInterface URL expiration
     */
    protected function getPresignedUrlDuration($attribute)
    {
        $filesystem = $this->getFsComponent();
        $dateValue = '+5 Minutes';

        if (empty($this->{$attribute})) {
            $dateValue = 'now';
        }

        return $filesystem->convertToDateTime($dateValue);
    }

    /**
     * Retrieves the base path on filesystem for a given attribute.
     * see `attributePaths()`
     * 
     * @param string $attribute Attribute to get its path
     * 
     * @return string The path where all file of that attribute should be stored. Returns empty string if the attribute isn't in the list.
     */
    protected function getAttributePath($attribute)
    {
        $paths = $this->attributePaths();
        if (array_key_exists($attribute, $paths)) {
            return (string) $paths[$attribute];
        }

        return '';
    }

    /**
     * Returns a value indicating whether there is any validation error.
     * @param string|null $attribute attribute name. Use null to check all attributes.
     * @return bool whether there is any error.
     */
    abstract public function hasErrors($attribute = null);

    /**
     * Performs the data validation.
     *
     * This method executes the validation rules applicable to the current [[scenario]].
     * The following criteria are used to determine whether a rule is currently applicable:
     *
     * - the rule must be associated with the attributes relevant to the current scenario;
     * - the rules must be effective for the current scenario.
     *
     * This method will call [[beforeValidate()]] and [[afterValidate()]] before and
     * after the actual validation, respectively. If [[beforeValidate()]] returns false,
     * the validation will be cancelled and [[afterValidate()]] will not be called.
     *
     * Errors found during the validation can be retrieved via [[getErrors()]],
     * [[getFirstErrors()]] and [[getFirstError()]].
     *
     * @param string[]|string|null $attributeNames attribute name or list of attribute names
     * that should be validated. If this parameter is empty, it means any attribute listed in
     * the applicable validation rules should be validated.
     * @param bool $clearErrors whether to call [[clearErrors()]] before performing validation
     * @return bool whether the validation is successful without any error.
     * @throws \InvalidArgumentException if the current scenario is unknown.
     */
    abstract public function validate($attributeNames = null, $clearErrors = true);
}
