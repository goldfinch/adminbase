<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class BaseElementExtension extends DataExtension
{
    public function updateCMSFields(FieldList $fields)
    {
        $f = $fields->dataFieldByName('Title')->setTitle('Block Title');

        $fields->removeByName('Title');

        $fields->insertBefore('ExtraClass', $f);
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->owner->Title) {
          $this->owner->Title = $this->owner->getType();
        }
    }
}
