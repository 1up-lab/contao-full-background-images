Getting started
===============

This extension is made for the Contao Open Source CMS and tested for versions 3.2+. The extension provides multiple variants of having full background images for a Contao Open Source CMS based website.

## Installation

* Install it via the Contao Extension Repository Client (search for `contao-full-background-images`)
* Install it via the CCA Composer Client (search for `bytehead/contao-full-background-images`)

## Usage

All settings are placed in the page tree. Settings made on a root page are mandatory. That's because subpages will inherit the settings from the root page by default.

You can optionally define different settings for each subpage. It's also possible to disable the background image(s) on certain pages.

### Website root
#### Operation mode
* `Disabled` - No background images are shown
* `Selection of background images` - Choose the background images

#### Sort
* Supports all known Contao sort modes

#### Image mode
* `Single image` - Show only one image (the first from the selection)
* `Random single image` - Show only one image (random from the selection)
* `Fade multiple images` - Show all images from the selection with fading

#### Timeout time
* Time between image changes in ms

#### Animation speed
* Animation speed in ms

### Regular page / Internal redirect / 403 / 404
#### Operation mode
* `Inherit from parent` - Inherits settings from a parent page
* `Selection of background images` - Choose the background images for this page
* `Disabled` - No background images are shown

#### Overwrite parent settings
If checked, the following settings from the parent page can be overwritten

#### Sort
* All known Contao sort modes

#### Image mode
* `Single image` - Show only one image (the first from the selection)
* `Random single image` - Show only one image (random from the selection)
* `Fade multiple images` - Show all images from the selection with fading

#### Timeout time
* Time between image changes in ms

#### Animation speed
* Animation speed in ms
