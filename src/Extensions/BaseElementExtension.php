<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

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

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->owner->Title) {
          $this->owner->Title = $this->owner->getType();
        }
    }
}
