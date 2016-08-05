# magento2-module-templatehints

A template hints overview for magento2 backend

![](./docs/visual_cronjob_schedule.png)

The creation, modification and debugging of Magento 2 frontend templates can be supported by enabling the build in "Template Path Hints for Storefront" in Admin configuration of Magento 2.

Store > Configuration > Advanced > Developer > Debug > Enabled Template Path Hints for Storefront > Yes


The result are graphical enhanced frontend blocks with basic information about template pathes. This information is useful for template-designer and developers to create or modify Magento 2 features, but the information is provided is kept at a very low level:

# magento2-templatehints

## Template-file

```html
<div class="debugging-hints" style="position: relative; border: 1px dotted red; margin: 6px 2px; padding: 18px 2px 2px 2px;">
<div class="debugging-hint-template-file" style="position: absolute; top: 0; padding: 2px 5px; font: normal 11px Arial; background: red; left: 0; color: white; white-space: nowrap;" onmouseover="this.style.zIndex = 999;" onmouseout="this.style.zIndex = 'auto';" title="{$templateFile}">{$templateFile}</div>
{$blockHtml}
</div>
```

## Block Class

```html
<div class="debugging-hint-block-class" style="position: absolute; top: 0; padding: 2px 5px; font: normal 11px Arial; background: red; right: 0; color: blue; white-space: nowrap;" onmouseover="this.style.zIndex = 999;" onmouseout="this.style.zIndex = 'auto';" title="{$blockClass}">{$blockClass}</div>
{$blockHtml}
```

## Extension

My new Magento 2 "Templatehints" module addresses that point. New features are added to Template Path Hints functionality of Magento 2:

https://github.com/shockwavemk/magento2-module-templatehints



General information is displayed for each block:

Template file
    - Block type
    - Name of block in layout
    - Module for block
    - Information about parent block

Cache information
    - Cache key of current block
    - Cache Tags of current block
    - Configured lifetime for current block
    - Cache key info string
    - Current status of current block in cache (found, disabled, not found)


Installation of the magento2 template path hints extension

Add the module to your composer file:

```json

{
  "require": {
  "shockwavemk/magento2-module-templatehints": "dev-master",
  }
}

```



Add my packages server to your composer file:

```json

    {
        "repositories": [
            {
                "type": "composer",
                "url": "https://packages.martin-kramer.com"
            }
        ]
    }

```

Install the module with composer:



composer update


On succeed, install the module via bin/magento console:

```bash

bin/magento cache:clean

bin/magento module:enable Shockwavemk_Templatehints

bin/magento setup:upgrade

```


After successful installation you should find the module in the list of activated modules:

```bash

bin/magento module:status
installed-magento2-module-templatehints-extended-shockwavemk

```


Usage

Enable Templatehints in admin

Enable template path hints for storefront in store configuration of your Magento 2 installation.

Store > Configuration > Advanced > Developer > Debug > Enabled Template Path Hints for Storefront > Yes



enable-templatehints-magento2


Clear cache of Magento 2



Clear Magento 2 cache via Magento 2 console or via Admin panel.

```bash
bin/magento cache:clean
```


# Result in frontend

Each block is surrounded by a dashed line in specific colors. If block is in cache a green line, if cache state is disabled a yellow line and if block can not be found in cache or no cache-time is configured a red line will appear.

Example: A green line - block found in cache.

templatehints-extended-magento2-shockwavemk-block-cached

Example: A red line - block can not be found in cache.

templatehints-extended-magento2-shockwavemk-block-not-cached



On mouseover a tooltip (i use opentip library for that to keep it simple, without need for external libraries) appears for each block rendered on each page.

Example: A green tooltip - block found in cache.

templatehints-extended-magento2-shockwavemk-opentip-cached

Example: A red tooltip - block can not be found in cache.

templatehints-extended-magento2-shockwavemk-opentip-not-cached
