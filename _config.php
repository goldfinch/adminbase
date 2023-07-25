<?php

use SilverStripe\Core\Environment;

if (Environment::hasEnv('APP_TIMEZONE'))
{
    date_default_timezone_set(Environment::getEnv('APP_TIMEZONE'));
}
