<?php

use SilverStripe\Control\Director;
use SilverStripe\Core\Environment;

if (Environment::hasEnv('APP_TIMEZONE'))
{
    date_default_timezone_set(Environment::getEnv('APP_TIMEZONE'));
}

// for reinstall.sh (to make sake cli work)
if (Director::isDev() && Environment::hasEnv('SS_DATABASE_SOCKET'))
{
    ini_set('mysqli.default_socket', Environment::getEnv('SS_DATABASE_SOCKET'));
}
