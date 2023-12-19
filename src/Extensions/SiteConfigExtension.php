<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\AssetAdmin\Forms\UploadField;

class SiteConfigExtension extends DataExtension
{
    private static $db = [
        'GeneralEmail' => 'Varchar',
        'GeneralPhone' => 'Varchar',
        'GeneralAddress' => 'Varchar',
        'GeneralCopyright' => 'Varchar',
    ];

    private static $has_one = [
        'PlaceholderImage' => Image::class,
    ];

    private static $owns = [
        'PlaceholderImage',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Main', [

            EmailField::create('GeneralEmail', 'Email'),
            TextField::create('GeneralPhone', 'Phone'),
            TextField::create('GeneralAddress', 'Address'),
            UploadField::create('PlaceholderImage', 'Placeholder image'),
            TextField::create('GeneralCopyright', 'Copyright'),

        ]);

        $fields->addFieldsToTab('Root.Configurations', []);

        $fields->dataFieldByName('PlaceholderImage')->setFolderName('basement');

        // Tabs Reorder
        $fields = $this->orderTabs([
            'Configurations',
            'Forms',
            'CookieConsent',
        ], $fields);

        // Rename Main tab at the very end
        $fields->fieldByName('Root.Main')->setTitle('General');
    }

    public function validate(ValidationResult $validationResult)
    {
        // $validationResult->addError('Error message');
    }

    protected function orderTabs($tabs, $fields)
    {
        foreach(array_reverse($tabs) as $tabName)
        {
            $tab = $fields->fieldByName('Root.' . $tabName);

            if ($tab)
            {
                $fields->removeFieldFromTab('Root', $tabName);
                $fields->insertAfter('Main', $tab);
            }
        }

        return $fields;
    }
}
