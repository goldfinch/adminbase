<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\CMS\Model\SiteTreeExtension as BaseSiteTreeExtension;

class SiteTreeExtension extends BaseSiteTreeExtension
{
    public function updateCMSActions(FieldList $actions)
    {
        $actions->insertBefore('ActionMenus', LiteralField::create('test', '<a target="_blank" href="'.$this->owner->Link().'?stage=Stage" class="btn btn-primary bi bi-binoculars-fill me-1" title="Review page on the website"></a>'));
    }
}
