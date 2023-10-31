<?php

namespace Goldfinch\Basement\Extensions;

use gorriecoe\Link\Models\Link;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use gorriecoe\LinkField\LinkField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\CMS\Model\SiteTreeExtension as BaseSiteTreeExtension;

class SiteTreeExtension extends BaseSiteTreeExtension
{
    private static $db = [
        'MetaTitle' => 'Varchar',
        'ShowInFooter' => 'Boolean',
        'ShowOnlyToRobots' => 'Boolean',
    ];

    private static $has_one = [
        'ShowOnlyToRobots_BackLink' => Link::class,
    ];

    // private static $many_many = [
    //     'FooterLinks' => Link::class
    // ];

    // private static $many_many_extraFields = [
    //     'FooterLinks' => [
    //         'Sort' => 'Int',
    //     ]
    // ];

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

                  CheckboxField::create('ShowInMenus', 'Show in menus'),
                  CheckboxField::create('ShowInFooter', 'Show in footer')

              )->setTitle('Visibility')

              // $visibilityTab,
              // $googleSitemapTab,
          ]
        );

        $fields->addFieldsToTab(
          'Root.SEO',
          [
              TextField::create('MetaTitle', 'Meta title'),

              TextareaField::create('MetaDescription', 'Meta description'),

              $googleSitemapTab,

              CheckboxField::create('ShowInSearch', 'Show in search'),

              CheckboxField::create('ShowOnlyToRobots', 'Show only to robots'),

              Wrapper::create(

                LinkField::create('ShowOnlyToRobots_BackLink', 'Back link for users', $this->owner),

              )->displayIf('ShowOnlyToRobots')->isChecked()->end(),

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

    // protected function onBeforeWrite()
    // {
    //     parent::onBeforeWrite();

    //     // TODO
    //     // if ($this->owner->ShowOnlyToRobots)
    //     // {
    //     //     $this->owner->ShowInMenus = 0;
    //     //     $this->owner->ShowInFooter = 0;
    //     // }
    // }

    public function updateCMSActions(FieldList $actions)
    {
        if ($this->owner->hasMethod('Link'))
        {
            $actions->insertBefore('ActionMenus', LiteralField::create('test', '<a target="_blank" href="'.$this->owner->Link().'?stage=Stage" class="btn btn-primary bi bi-binoculars-fill me-1" title="Review page on the website"></a>'));
        }
    }
}
