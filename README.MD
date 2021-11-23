# Flexmail for Craft CMS

This plugin integrates the [Flexmail REST API](https://api.flexmail.eu/documentation/#overview) into Craft CMS. The first version will focus on adding contacts, and assigning preferences & interests.

## Installation

To install the plugin, follow these instructions:

```console
cd /path/to/project
composer require statikbe/craft-flexmail
./craft  plugin/install flexmail
```

## Usage

By default, a contact in Flexmail has the following fields ([API docs](flexmail)):
- Email (required)
- First name
- Last name
- Language (required)



## Basic subscribe form

