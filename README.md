# Magento 2 - Enhanced template hints

# Introduction

<img class="alignnone size-full wp-image-531" src="http://www.martin-kramer.com/wp-content/uploads/2015/12/templatehings-magento2-settings.png" alt="templatehings-magento2-settings" width="816" height="583" />

The creation, modification and debugging of Magento 2 frontend templates can be supported by enabling the build in "Template Path Hints for Storefront" in Admin configuration of Magento 2.

  Store > Configuration > Advanced > Developer > Debug > Enabled Template Path Hints for Storefront > Yes

The result are graphical enhanced frontend blocks with basic information about template pathes.


<img class="alignnone size-full wp-image-529" src="http://www.martin-kramer.com/wp-content/uploads/2015/12/magento2-templatehints.png" alt="magento2-templatehints" width="668" height="115" />



This information is useful for template-designer and developers to create or modify Magento 2 features, but the information is provided is kept at a very low level:

## Default Template-file behavior


# Features

This Magento 2 "Templatehints" module addresses that point. New features are added to Template Path Hints functionality of Magento 2:

 * General information is displayed for each block

   * Template file
   * Block type
   * Name of block in layout
   * Module for block
   * Information about parent block



 *  Cache information

   *  Cache key of current block
   *  Cache Tags of current block
   *  Configured lifetime for current block
   *  Cache key info string
   *  Current status of current block in cache (found, disabled, not found)


# Installation of the magento2 template path hints extension

Add the module to your composer file:

  {

  ...

  "require": {
  ...

  "shockwavemk/magento2-module-templatehints": "dev-master",

  ...
  },


Add my packages server to your composer file:

  {
      "repositories": [
          {
              "type": "composer",
              "url": "https://packages.martin-kramer.com"
          }
      ]
  }