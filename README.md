# Mandrill for Craft CMS

This plugin provides a [Mandrill](http://mandrill.com/) integration for [Craft CMS](https://craftcms.com/).

## Requirements

This plugin requires Craft CMS 3.0.0-beta.1 or later.

## Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “Mandrill”. Then click on the “Install” button in its modal window.

#### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project.test

# tell Composer to load the plugin
composer require craftcms/mandrill

# tell Craft to install the plugin
./craft install/plugin mandrill
```

## Setup

Once Mandrill is installed, go to Settings → Email, and change the “Transport Type” setting to “Mandrill”. Enter your Mandrill API Key (which you can get from [mandrillapp.com/settings](https://mandrillapp.com/settings)) and click Save.

## Overriding Plugin Settings

If you create a [config file](https://docs.craftcms.com/v3/extend/plugin-settings.html#overriding-setting-values) in your `config` folder called `mandrill.php`, you can override the plugin’s settings in the Control Panel. Since that config file is fully [multi-environment](https://docs.craftcms.com/v3/config/environments.html#multi-environment-configs) aware, this is a handy way to have different settings across multiple environments.

Here’s what that config file might look like along with a list of all of the possible values you can override.

```php
<?php

return [
    'subaccount' => 'Clientname',
    'apiKey' => getenv('MANDRILL_API_KEY'),
];
```
