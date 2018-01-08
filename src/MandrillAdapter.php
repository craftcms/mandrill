<?php
/**
 * @link      https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license   MIT
 */

namespace craft\mandrill;

use Accord\MandrillSwiftMailer\SwiftMailer\MandrillTransport;
use Craft;
use craft\mail\transportadapters\BaseTransportAdapter;
use Swift_Events_SimpleEventDispatcher;

/**
 * MandrillAdapter implements a Mandrill transport adapter into Craft’s mailer.
 *
 * @property mixed $settingsHtml
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  3.0
 */
class MandrillAdapter extends BaseTransportAdapter
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        /** @noinspection ClassConstantCanBeUsedInspection */
        return 'Mandrill';
    }

    // Properties
    // =========================================================================

    /**
     * @var string The API key that should be used
     */
    public $apiKey;

    /**
     * @var string The subaccount that should be used
     */
    public $subaccount;

    // Public Methods
    // =========================================================================

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
            'apiKey' => $this->apiKey,
            'subAccount' => $this->subaccount ?: null,
        ];
    }
}
