<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\LiteralField;

class BaseElementExtension extends DataExtension
{
    public function updateCMSFields(FieldList $fields)
    {
        $title = $fields->dataFieldByName('Title');

        if ($title)
        {
            $title->setTitle('Block Title');
            $fields->removeByName('Title');
            $fields->insertBefore('ExtraClass', $title);
        }

        $global = $fields->dataFieldByName('AvailableGlobally');

        $fields->insertAfter('ExtraClass', $global);
    }

    public function updateCMSActions(&$fields)
    {
        $fields->insertBefore('ActionMenus', LiteralField::create('test', '<a target="_blank" href="'.$this->owner->Link().'?stage=Stage" class="btn btn-primary bi bi-binoculars-fill me-1" title="Review page on the website"></a>'));
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->owner->Title) {
          $this->owner->Title = $this->owner->getType();
        }
    }

    public function getIconClassName()
    {
        return $this->owner->config()->get('icon');
    }
}
