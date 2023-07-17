<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CompositeField;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\AssetAdmin\Forms\UploadField;

class SiteConfigExtension extends DataExtension
{
    private static $db = [
        'GeneralEmail' => 'Varchar',
        'GeneralPhone' => 'Varchar',
        'GeneralAddress' => 'Varchar',

        'GeneralFacebook' => 'Boolean',
        'GeneralFacebookURL' => 'Varchar',
        'GeneralInstagram' => 'Boolean',
        'GeneralInstagramURL' => 'Varchar',
        'GeneralTwitter' => 'Boolean',
        'GeneralTwitterURL' => 'Varchar',
        'GeneralYouTube' => 'Boolean',
        'GeneralYouTubeURL' => 'Varchar',
        'GeneralLinkedIn' => 'Boolean',
        'GeneralLinkedInURL' => 'Varchar',
    ];

    private static $has_one = [
        'PlaceholderImage' => Image::class,
    ];

    private static $owns = [
        'PlaceholderImage',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName([
            'Tagline',
        ]);

        $fields->addFieldsToTab('Root.Main', [

            EmailField::create('GeneralEmail', 'Email'),
            TextField::create('GeneralPhone', 'Phone'),
            TextField::create('GeneralAddress', 'Address'),
            UploadField::create('PlaceholderImage', 'Placeholder image'),

        ]);

        $fields->addFieldsToTab('Root.SocialMedia', [

            CompositeField::create(

                CheckboxField::create('GeneralFacebook', 'Facebook'),
                TextField::create('GeneralFacebookURL', '')->setAttribute('placeholder', 'https://facebook.com/...')->displayIf('GeneralFacebook')->isChecked()->end(),

            ),

            CompositeField::create(

                CheckboxField::create('GeneralInstagram', 'Instagram'),
                TextField::create('GeneralInstagramURL', '')->setAttribute('placeholder', 'https://www.instagram.com/...')->displayIf('GeneralInstagram')->isChecked()->end(),

            ),

            CompositeField::create(

                CheckboxField::create('GeneralTwitter', 'Twitter'),
                TextField::create('GeneralTwitterURL', '')->setAttribute('placeholder', 'https://twitter.com/...')->displayIf('GeneralTwitter')->isChecked()->end(),

            ),

            CompositeField::create(

                CheckboxField::create('GeneralYouTube', 'YouTube'),
                TextField::create('GeneralYouTubeURL', '')->setAttribute('placeholder', 'https://www.youtube.com/...')->displayIf('GeneralYouTube')->isChecked()->end(),

            ),

            CompositeField::create(

                CheckboxField::create('GeneralLinkedIn', 'LinkedIn'),
                TextField::create('GeneralLinkedInURL', '')->setAttribute('placeholder', 'https://www.linkedin.com/...')->displayIf('GeneralLinkedIn')->isChecked()->end(),

            ),

        ])->findTab('Root.SocialMedia')->setTitle('Social Media');

        // Tabs Reorder
        $fields = $this->orderTabs([
            'Forms',
            'Configurations',
            'SocialMedia',
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
