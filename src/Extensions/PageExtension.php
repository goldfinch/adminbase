<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\CMS\Controllers\CMSPageEditController;

class PageExtension extends Extension
{
    private static $allowed_actions = [];

    private static $db = [
        'MetaTitle' => 'Varchar',
        'ShowInFooter' => 'Boolean',
    ];

    private static $icon_class = 'bi-file-earmark-text-fill';

    public function onAfterInit()
    {
        //
    }

    public function updateSettingsFields(&$fields)
    {
        $mainFields = $this->owner->getCMSFields();

        $googleSitemapTab = $fields->findTab('Root.Settings')->getChildren()->findTab('GoogleSitemap');
        $visibilityTab = $fields->findTab('Root.Settings')->getChildren()->findTab('Visibility');

        $fields->findTab('Root.Settings')->getChildren()->removeByName([
          'GoogleSitemap',
          'GoogleSitemapIntro',
          'Priority',
          'Visibility',
          'ShowInMenus',
          'ShowInSearch',
        ]);

        // $visibilityTab->insertAfter('ShowInMenus', CheckboxField::create(
        //         'ShowInFooter',
        //         'Show in Footer?'
        // ));

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
                  // $titleField->addExtraClass('fcol-6'),
                  // TextField::create('Title', 'Title')->addExtraClass('fcol-6'),
                  // TextField::create('MenuTitle', 'Menu Tilte')->addExtraClass('fcol-6 fcol-6-mr0'),

              )->setTitle('Visibility')
              // $fields->dataFieldByName('MenuTitle'),
              // $mainFields->dataFieldByName('URLSegment'),

              // $visibilityTab,
              // $googleSitemapTab,
          ]
        );

        $fields->addFieldsToTab(
          'Root.SEO',
          [
              // $fields->dataFieldByName('MenuTitle'),

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
              // $visibilityTab,
          ]
        );

        $settingFields = $fields->findTab('Root.Settings');
        $fields->removeByName([
          'Settings'
        ]);
        $fields->addFieldsToTab(
          'Root.Advanced',
            $settingFields->getChildren()
        );
    }

    public function updateCMSFields(&$fields)
    {
        $fields->findTab('Root.Main')->setTitle('General');

        $fields->removeByName(['ExtraMeta', 'Metadata']);

        if (get_class(Controller::curr()) === CMSPageEditController::class)
        {
            $fields->removeByName(['Title', 'URLSegment', 'MenuTitle']);
        }

        $fields->addFieldsToTab(
          'Root.Main',
          [
              // FieldGroup::create(

              //     $titleField->addExtraClass('fcol-6'),
              //     // TextField::create('Title', 'Title')->addExtraClass('fcol-6'),
              //     TextField::create('MenuTitle', 'Menu Tilte')->addExtraClass('fcol-6 fcol-6-mr0'),

              // )
          ]
        );

        // $fields->removeByName('Content');

        // $fields->insertAfter('Title', \SilverStripe\Forms\HTMLEditor\HTMLEditorField::create('expert', 'expert')->setEditorConfig('expert'));
        $fields->insertAfter('Content', \SilverStripe\Forms\HTMLEditor\HTMLEditorField::create('AdvancedText', 'Advanced')->addExtraClass('stacked'));
        // $fields->insertAfter('Title', \SilverStripe\Forms\HTMLEditor\HTMLEditorField::create('intermediate', 'intermediate')->setEditorConfig('intermediate'));
        // $fields->insertAfter('Title', \SilverStripe\Forms\HTMLEditor\HTMLEditorField::create('basic', 'basic')->setEditorConfig('basic'));
        // $fields->insertAfter('Title', \SilverStripe\Forms\HTMLEditor\HTMLEditorField::create('dev', 'dev')->setEditorConfig('dev'));
    }
}
