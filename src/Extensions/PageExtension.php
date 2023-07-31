<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\CMS\Controllers\CMSPageEditController;

class PageExtension extends Extension
{
    private static $db = [
        'MetaTitle' => 'Varchar',
        'ShowInFooter' => 'Boolean',
    ];

    private static $icon_class = 'bi-file-earmark-text-fill';

    public function updateSettingsFields(&$fields)
    {
        // ->addExtraClass('stacked')

        $mainFields = $this->owner->getCMSFields();

        $visibilityTab = $fields->findTab('Root.Settings')->getChildren()->findTab('Visibility');
        $googleSitemapTab = $fields->findTab('Root.Settings')->getChildren()->findTab('GoogleSitemap');

        $fields->findTab('Root.Settings')->getChildren()->removeByName([
          'GoogleSitemap',
          'GoogleSitemapIntro',
          'Priority',
          'Visibility',
          'ShowInMenus',
          'ShowInSearch',
        ]);

        $fields->addFieldsToTab(
          'Root.General',
          [
              $mainFields->dataFieldByName('Title'),
              $mainFields->dataFieldByName('URLSegment')->setTitle('URL'),
              $mainFields->dataFieldByName('MenuTitle'),

              FieldGroup::create(

                  CheckboxField::create(
                    'ShowInMenus',
                    'Show in menus'
                  ),
                  CheckboxField::create(
                    'ShowInFooter',
                    'Show in footer'
                  )

              )->setTitle('Visibility')

              // $visibilityTab,
              // $googleSitemapTab,
          ]
        );

        $fields->addFieldsToTab(
          'Root.SEO',
          [
              TextField::create(
                'MetaTitle',
                'Meta title'
              ),

              TextareaField::create(
                'MetaDescription',
                'Meta description'
              ),

              $googleSitemapTab,

              CheckboxField::create(
                'ShowInSearch',
                'Show in search'
              ),
          ]
        );

        // tab reorder
        $settingFields = $fields->findTab('Root.Settings');
        $fields->removeByName('Settings');
        $fields->addFieldsToTab('Root.Advanced', $settingFields->getChildren());
    }

    public function updateCMSFields(&$fields)
    {
        $fields->removeByName(['ExtraMeta', 'Metadata']);

        if (get_class(Controller::curr()) === CMSPageEditController::class)
        {
            $fields->removeByName(['Title', 'URLSegment', 'MenuTitle']);
        }
    }

    public function updateCMSActions(&$fields)
    {
        $fields->insertBefore('ActionMenus', LiteralField::create('test', '<a target="_blank" href="'.$this->owner->Link().'?stage=Stage" class="btn btn-primary bi bi-binoculars-fill me-1" title="Review page on the website"></a>'));
    }
}
