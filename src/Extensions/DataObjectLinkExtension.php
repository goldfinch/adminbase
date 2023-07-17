<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\ORM\DataExtension;

// TODO
class DataObjectLinkExtension extends DataExtension
{
    public function Link($full = false)
    {
        $return = '';

        $page = DataObject::get()->first();

        if ($page) {

            $return = $page->Link() . $this->URLSegment;
        }

        if ($full && $full !== 'false') {
            $return = str_replace('://', '/--/', Director::absoluteBaseURL() . $return);
            $return = str_replace('//', '/', $return);
            $return = str_replace('/--/', '://', $return);
        }

        return $return;
    }

    public function AbsoluteLink()
    {
        return $this->Link(true);
    }
}
