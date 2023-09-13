<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class DataObjectLinkExtension extends DataExtension
{
    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName([
            'Sort',
            'SortOrder',
        ]);
    }
    // public function Link($full = false)
    // {
    //     $return = '';

    //     $page = DataObject::get()->first();

    //     if ($page) {

    //         $return = $page->Link() . $this->URLSegment;
    //     }

    //     if ($full && $full !== 'false') {
    //         $return = str_replace('://', '/--/', Director::absoluteBaseURL() . $return);
    //         $return = str_replace('//', '/', $return);
    //         $return = str_replace('/--/', '://', $return);
    //     }

    //     return $return;
    // }

    // public function AbsoluteLink()
    // {
    //     return $this->Link(true);
    // }
}
