# Magento 2 - Hreflang Tag

## Overview

The hreflang attribute (also referred to as rel="alternate") tells Google which language you are using on a specific page, so the search engine can serve that result to users searching in that language.

#### The module
Considering the Magento multi store structure, this module will add the hreflang tag to the pages.

##### Code Sample
`<link rel="alternate" href="http://example.com/en" hreflang="en-us" />` <br>
`<link rel="alternate" href="http://example.com/pt" hreflang="pt-br" />`


## Installation details
1. Run `composer require vandersonramos/magento2-hreflangtag`
2. Run `php bin/magento setup:upgrade`
3. Run `php bin/magento cache:clean`


## Uninstall
1. Run `composer remove vandersonramos/magento2-hreflangtag`
