<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use Goldfinch\Basement\Helpers\BaseHelper;
use SilverStripe\CMS\Model\SiteTreeExtension as BaseSiteTreeExtension;

class SiteTreeExtension extends BaseSiteTreeExtension
{
    public function updateCMSActions(FieldList $actions)
    {
        $actions->insertBefore('ActionMenus', LiteralField::create('test', '<a target="_blank" href="'.$this->owner->Link().'?stage=Stage" class="btn btn-primary bi bi-binoculars-fill me-1" title="Review page on the website"></a>'));
    }

    public function canView($member = null)
    {
        // if (!parent::canView($member)) {
        //     return false;
        // }

        if (!BaseHelper::is_bot($_SERVER['HTTP_USER_AGENT']) && $this->owner->ShowOnlyToRobots)
        {
            if ($this->owner->ShowOnlyToRobots_BacklinkID)
            {
                $this->redirect('/', 301);
            }
            else
            {
                // TODO
                // $this->redirect($this->owner->ShowOnlyToRobots_Backlink->Link(), 301);
            }
        }

        return false;
    }
}
