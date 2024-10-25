<?php

namespace diecoding\flysystem\actions;

use DateTimeImmutable;
use Yii;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * FileAction for handle LocalComponent.
 *
 * To use FileAction, you need to do the following steps:
 *
 * First, declare an action of FileAction type in the `actions()` method of your `SiteController`
 * class (or whatever controller you prefer), like the following:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'file' => [
 *             'class' => \diecoding\flysystem\actions\FileAction::class,
 *             'component' => 'fs',
 *         ],
 *     ];
 * }
 * ```
 * 
 * @link      https://sugengsulistiyawan.my.id/
 * @author    Sugeng Sulistiyawan <sugeng.sulistiyawan@gmail.com>
 * @copyright Copyright (c) 2023
 */
class FileAction extends Action
{
    /**
     * @var string filesystem config component (fs)
     */
    public $component = 'fs';

    /**
     * Runs the action.
     *
     * @param string|null $data
     * @return mixed result content
     * @throws NotFoundHttpException if data not valid
     */
    public function run($data = null)
    {
        /** @var \diecoding\flysystem\AbstractComponent|mixed $filesystem */
        $filesystem = Yii::$app->get($this->component);

        try {
            $params = Json::decode($filesystem->/** @scrutinizer ignore-call */decrypt($data));

            $now = (int) (new DateTimeImmutable())->getTimestamp();
            $expires = (int) $params['expires'];

            if (!$filesystem->fileExists($params['path']) || ($expires > 0 && $expires < $now)) {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            }

            $attachmentName = (string) pathinfo($params['path'], PATHINFO_BASENAME);
            $content = $filesystem->read($params['path']);
            $mimeType = $filesystem->mimeType($params['path']);
        } catch (\Throwable $th) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        return Yii::$app->getResponse()->sendContentAsFile($content, $attachmentName, [
            'mimeType' => $mimeType,
            'inline' => true,
        ]);
    }
}
