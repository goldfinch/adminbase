<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\AssetAdmin\Forms\UploadField;

class SiteConfigExtension extends DataExtension
{
    private static $db = [
        'GeneralEmail' => 'Varchar',
        'GeneralPhone' => 'Phone',
        'GeneralAddress' => 'Place',
        'GeneralCopyright' => 'Varchar',
    ];

    private static $has_one = [
        'PlaceholderImage' => Image::class,
    ];

    private static $owns = ['PlaceholderImage'];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->initFielder($this->owner);

        $fielder = $fields->getFielder();

        $fields->addFieldsToTab('Root.Main', [
            $fielder->email('GeneralEmail', 'Email'),
            $fielder->phone('GeneralPhone', 'Phone'),
            $fielder->place('GeneralAddress', 'Address'),
            ...$fielder->media('PlaceholderImage', 'Placeholder image'),
            TextField::create('GeneralCopyright', 'Copyright'),
        ]);

        $fields->addFieldsToTab('Root.Configurations', []);

        $fields->dataFieldByName('PlaceholderImage')->setFolderName('basement');

        // Tabs Reorder
        $fields = $this->orderTabs(
            ['Configurations', 'Forms', 'CookieConsent'],
            $fields,
        );

        // Rename Main tab at the very end
        $fields->fieldByName('Root.Main')->setTitle('General');
    }

    protected function orderTabs($tabs, $fields)
    {
        foreach (array_reverse($tabs) as $tabName) {
            $tab = $fields->fieldByName('Root.' . $tabName);

            if ($tab) {
                $fields->removeFieldFromTab('Root', $tabName);
                $fields->insertAfter('Main', $tab);
            }
        }

        return $fields;
    }
}
