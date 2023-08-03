<?php

namespace App\Extensions;

use SilverStripe\ORM\DataExtension;

// TODO
class CustomFunctions extends DataExtension
{
    public function URLEncode()
    {
        return urlencode($this->owner->value);
    }

    public function Carbon()
    {
        return;
    }
}
