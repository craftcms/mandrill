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
