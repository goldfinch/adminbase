<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use Goldfinch\Enchantment\Helpers\BuildHelper;
use SilverStripe\CMS\Model\SiteTreeExtension as BaseSiteTreeExtension;

class SiteTreeExtension extends BaseSiteTreeExtension
{
    public function updateCMSActions(FieldList $actions)
    {
        if ($this->owner->hasMethod('Link'))
        {
            $icon = 'font-icon-eye';

            if(class_exists(BuildHelper::class))
            {
                $icon = 'bi bi-binoculars-fill';
            }

            $actions->insertBefore('ActionMenus', LiteralField::create('pagereview', '<a target="_blank" href="'.$this->owner->Link().'?stage=Stage" class="btn btn-primary '.$icon.' me-1" title="Review page on the website"></a>'));
        }
    }

    // public function onBeforeWrite()
    // {
    //     parent::onBeforeWrite();

    //     // TODO
    //     // if ($this->owner->ShowOnlyToRobots)
    //     // {
    //     //     $this->owner->ShowInMenus = 0;
    //     //     $this->owner->ShowInFooter = 0;
    //     // }
    // }
}
