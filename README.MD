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


### Basic subscribe form
`````html
 <form method="post">
    {{ actionInput('flexmail/contacts/add') }}
    {{ csrfInput() }}
    <div>
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email">
    </div>
    <div>
        <label for="firstName">First name</label>
        <input type="text" name="firstName" id="firstName">
    </div>
    <div>
        <label for="lastName">Last name</label>
        <input type="text" name="lastName" id="lastName">
    </div>
    <input type="submit" value="Subscribe">
</form>

`````

### Subscribe form with custom fields
Flexmail custom fields can be added in the ``fields`` namespace. 
For example ``fields[city]``, where "city" is the handle of the custom field in Flexmail.

`````html
 <form method="post">
        {{ actionInput('flexmail/contacts/add') }}
        {{ csrfInput() }}
        <div>
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email">
        </div>

        <div>
            <label for="custom">Your organisation</label>
            <input type="text" name="fields[organisation]" id="custom">
        </div>
        <input type="submit" value="Subscribe">
    </form>
`````

### Subscribe form with preferences and/or interest labels
Flexmail is 2 ways to segment or add metadata to contacts: interest labels & preferences.
These can be added by using ``labels[]`` and ``preferencs[]`` respectivly. 

It's important to note that the values for these need to be **the ID of that item in Flexmail**.

`````html
 <form method="post">
        {{ actionInput('flexmail/contacts/add') }}
        {{ csrfInput() }}
        <div>
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email">
        </div>

        // Interests
        <input type="checkbox" name="interests[]" value="interestId">
        // Interest label
        <input type="checkbox" name="labels[]" value="labelId">
        // Preference
        <input type="checkbox" name="preferences[]" value="preferenceId">
    
        <input type="submit" value="Subscribe">
    </form>
`````

## Fields

The plugin includes fields for the labels and for the preferences that are defined in your Flexmail account.

The idea here is that you can create a label or a preference for contacts that subscribe from a specific page/product/etc.

## Helpers

Flexmail uses ID's for most things (sources, labels, preferences) but those ID's are not exposed in the webpages.

You can use these console command to list each of these, they will return all items with their ID and label

    flexmail/debug/get-interest-labels
    flexmail/debug/get-preferences
    flexmail/debug/get-sources