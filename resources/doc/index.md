Getting started
===============

This extension is made for the Contao Open Source CMS and tested for versions 3.2+. The extension provides multiple variants of having full background images for a Contao Open Source CMS based website.

## Installation

* Install it via the Contao Extension Repository Client (search for `full-background-images`)
* Install it via the CCA Composer Client (search for `oneup/contao-full-background-images`)

## Requirements

* jQuery has to be enabled in the page layout

## Usage

All settings are placed in the page tree. Subpages will inherit the settings from parent page by default.

You can optionally define different settings for each subpage. It's also possible to disable the background image(s) on certain pages.


### Regular page / 403 / 404
#### Operation mode
* `Inherit from parent` - Inherits settings from a parent page
* `Selection of background images` - Choose the background images for this page
* `Disabled` - No background images are shown

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

#### Enable navigation
If checked, a navigation element is displayed (default lower left corner)

#### Enable navigation click
If checked, you can click on the navigation items to change the background

#### Enable prev/next navigation
If checked, a prev and a next button (default style: arrow) is added to the navigation items

#### Center image horizontally
If checked, the background image will be horizontally centered, else left aligned.

#### Center image vertically
If checked, the background image will be vertically centered, else top aligned.

#### Custom template ####
You can choose a specific template for the current page.
