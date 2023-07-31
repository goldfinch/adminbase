<?php

use SilverStripe\Core\Environment;
use Wilr\GoogleSitemaps\GoogleSitemap;
use Goldfinch\Basement\Models\PageDataObject;

if (Environment::hasEnv('APP_TIMEZONE'))
{
    date_default_timezone_set(Environment::getEnv('APP_TIMEZONE'));
}

GoogleSitemap::register_dataobject(PageDataObject::class); // , 'daily');
