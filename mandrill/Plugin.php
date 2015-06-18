<?php
/**
 * @copyright Copyright (c) 2015 Pixel & Tonic, Inc.
 */
namespace craft\plugins\mandrill;

use Craft;

/**
 * Plugin represents the Mandrill plugin.
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since  3.0
 */
class Plugin extends \craft\app\base\Plugin
{
	// Public Methods
	// =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        require __DIR__.'/vendor/autoload.php';
    }

    /**
     * Returns the mailer transport adaptors provided by this plugin.
     *
     * @return string[]
     */
    public function getMailTransportAdaptors()
    {
        return [
            MandrillAdaptor::className(),
        ];
    }
}
