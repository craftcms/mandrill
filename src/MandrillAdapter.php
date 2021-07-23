<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license MIT
 */

namespace craft\mandrill;

use Accord\MandrillSwiftMailer\SwiftMailer\MandrillTransport;
use Craft;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\mail\transportadapters\BaseTransportAdapter;
use Swift_Events_SimpleEventDispatcher;

/**
 * MandrillAdapter implements a Mandrill transport adapter into Craft’s mailer.
 *
 * @property mixed $settingsHtml
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class MandrillAdapter extends BaseTransportAdapter
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        /** @noinspection ClassConstantCanBeUsedInspection */
        return 'Mandrill';
    }

    /**
     * @var string The API key that should be used
     */
    public $apiKey;

    /**
     * @var string The subaccount that should be used
     */
    public $subaccount;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['parser'] = [
            'class' => EnvAttributeParserBehavior::class,
            'attributes' => [
                'apiKey',
                'subaccount',
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'apiKey' => Craft::t('mandrill', 'API Key'),
            'subaccount' => Craft::t('mandrill', 'Subaccount'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apiKey'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('mandrill/settings', [
            'adapter' => $this
        ]);
    }

    /**
     * @inheritdoc
     */
    public function defineTransport()
    {
        return [
            'class' => MandrillTransport::class,
            'constructArgs' => [
                [
                    'class' => Swift_Events_SimpleEventDispatcher::class
                ]
            ],
            'apiKey' => Craft::parseEnv($this->apiKey),
            'subAccount' => Craft::parseEnv($this->subaccount) ?: null,
        ];
    }
}
