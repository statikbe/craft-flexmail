# Flexmail for Craft CMS

This plugin integrates the [Flexmail REST API](https://api.flexmail.eu/documentation/#overview) into Craft CMS. The first version will focus on adding contacts, and assigning preferences & interests.

## Installation

To install the plugin, follow these instructions:

```console
cd /path/to/project
composer require statikbe/craft-flexmail
./craft  plugin/install flexmail
```

## Settings
The plugin has the following settings:
- API username (`apiUsername`)
- API token (`apiToken`)
- Default contact source (`defaultSource`) (read more about Flexmail sources [here](https://en.support.flexmail.eu/article/291-about-sources))

The default contact source can be set in settings, or you can set in template using a hidden ``source`` field.

## Usage

By default, a contact in Flexmail has the following fields ([API docs](flexmail)):
- Email (required)
- First name
- Last name
- Language (required)



## Basic subscribe form

