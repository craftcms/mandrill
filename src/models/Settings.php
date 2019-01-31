<?php

namespace craft\mandrill\models;

use craft\base\Model;

class Settings extends Model
{
    /** @var string */
    public $apiKey;

    /** @var string */
    public $subaccount;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['apiKey', 'subaccount'], 'required'],
        ];
    }
}
