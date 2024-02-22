# Webwirkung Property Merger
With this plugin, you can merge duplicate property groups in Shopware 6.

## Blog post
Please find more details in our [blog post](https://webwirkung.ch/blog/duplikate-von-eigenschaften-in-shopware-6-zusammenfuehren/).

## Installation
Add this repository to your composer.json file:

```
"repositories": {
   ...
   
    "webwirkung-property-merger": {
        "type": "vcs",
        "url":  "git@github.com:Webwirkung/webwirkung-property-merger.git"
    }
},
```

Require the plugin via composer:
```
composer req webwirkung/property-merger
```

Or download the ZIP file of this repository and install it via the Shopware 6 administration or use composer.

## Usage of the plugin
The plugin adds a new command to the shopware console. You can execute it with the following command:
```shell
bin/console webwirkung:property-merge -h
``` 
which shows you a help text with all available options.

```shell
bin/console webwirkung:property-merge -s [property ID] -d [property ID]
```
Let's you merge the property from the source ID to the destination ID. The source ID will be deleted after the merge.

```shell
bin/console webwirkung:property-merge -s [property ID] -d [property ID] --dry-run
```

There is a dry run mode available, which does not execute the merge, but shows you the changes which would be done.

## Support
Please note that we do not offer free support for the plugin.
