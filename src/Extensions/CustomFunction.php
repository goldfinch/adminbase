<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\ORM\DataExtension;

class CustomFunctions extends DataExtension
{
    // TODO

    public function URLEncode()
    {
        return urlencode($this->owner->value);
    }

    public function Carbon()
    {

    }
}
