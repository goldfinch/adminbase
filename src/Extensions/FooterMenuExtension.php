<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\ORM\ArrayList;
use SilverStripe\Core\Extension;
use SilverStripe\CMS\Model\SiteTree;

class FooterMenuExtension extends Extension
{
    /**
     * based on getMenu() and Menu() of ContentController.php
     */

    public function getFooterMenu($level = 1)
    {
        if ($level == 1) {
            $result = SiteTree::get()->filter([
                "ShowInFooter" => 1,
                "ParentID" => 0,
            ]);
        } else {
            $parent = $this->data();
            $stack = [$parent];

            if ($parent) {
                while (($parent = $parent->Parent()) && $parent->exists()) {
                    array_unshift($stack, $parent);
                }
            }

            if (isset($stack[$level - 2])) {
                $result = $stack[$level - 2]->Children();
            }
        }

        $visible = [];

        // Remove all entries the can not be viewed by the current user
        // We might need to create a show in menu permission
        if (isset($result)) {
            foreach ($result as $page) {
                /** @var SiteTree $page */
                if ($page->canView()) {
                    $visible[] = $page;
                }
            }
        }

        return new ArrayList($visible);
    }

    public function FooterMenu($level)
    {
        return $this->getFooterMenu($level);
    }
}
