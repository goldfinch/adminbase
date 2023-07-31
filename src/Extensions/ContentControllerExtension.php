<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Core\Extension;
use Goldfinch\Basement\Helpers\BaseHelper;

class ContentControllerExtension extends Extension
{
    public function onBeforeInit()
    {
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
    }
}
