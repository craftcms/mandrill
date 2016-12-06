Mandrill for Craft CMS
======================

This plugin provides a [Mandrill](http://mandrill.com/) integration for [Craft CMS](https://craftcms.com/).


## Requirements

This plugin requires Craft CMS 3.0.0-beta.1 or later.


## Installation

### For Composer-based Craft installs

If you installed Craft via [Composer](https://getcomposer.org/), follow these instructions:

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to install the plugin:

        php composer.phar require craftcms/mandrill

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Mandrill.


### For manual Craft installs

If you installed Craft manually, you will need to install this plugin manually as well.

1. [Download the zip](https://github.com/craftcms/mandrill/archive/master.zip), and extract it to your craft/plugins/ folder, renamed to “mandrill” (no “-master”).
2. Open your terminal and go to your craft/plugins/mandrill/ folder:

        cd /path/to/project/craft/plugins/mandrill 

3. Install Composer into the folder by running the commands listed at [getcomposer.org/download](https://getcomposer.org/download/).
    - **Note:** If you get an error running the first line, you may need to change `https` to `http`.

4. Once Composer is installed, tell it to install the plugin’s dependencies:

        php composer.phar install

5. In the Control Panel, go to Settings → Plugins and click the “Install” button for Mandrill.

## Setup

Once Mandrill is installed, go to Settings → Email, and change the “Transport Type” setting to “Mandrill”. Enter your Mandrill API Key (which you can get from [mandrillapp.com/settings](https://mandrillapp.com/settings)) and click Save.
