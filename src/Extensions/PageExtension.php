<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\LinkField\Models\Link;
use SilverStripe\AnyField\Form\AnyField;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use SilverStripe\CMS\Controllers\CMSPageEditController;

class PageExtension extends Extension
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

        if ($fields->findTab('Root.Settings')) {
            $visibilityTab = $fields
                ->findTab('Root.Settings')
                ->getChildren()
                ->findTab('Visibility');

            $fields
                ->findTab('Root.Settings')
                ->getChildren()
                ->removeByName([
                    'Priority',
                    'Visibility',
                    'ShowInMenus',
                    'ShowInSearch',
                ]);

            $fields->addFieldsToTab('Root.General', [
                // $mainFields->dataFieldByName('Title'),
                // $mainFields->dataFieldByName('URLSegment')->setTitle('URL'),
                // $mainFields->dataFieldByName('MenuTitle'),

                FieldGroup::create(
                    CheckboxField::create('ShowInMenus', 'Show in menus'),
                    CheckboxField::create('ShowInFooter', 'Show in footer'),
                )->setTitle('Visibility'),

                // $visibilityTab,
            ]);

            $fields->addFieldsToTab('Root.SEO', [
                TextField::create('MetaTitle', 'Meta title'),

                TextareaField::create('MetaDescription', 'Meta description'),

                CheckboxField::create('ShowInSearch', 'Show in search'),

                CheckboxField::create('ShowOnlyToRobots', 'Show only to robots'),

                Wrapper::create(
                    AnyField::create(
                        'ShowOnlyToRobots_BackLink',
                        'Back link for users',
                    ),
                )
                    ->displayIf('ShowOnlyToRobots')
                    ->isChecked()
                    ->end(),
            ]);

            // tab reorder
            $settingFields = $fields->findTab('Root.Settings');
            $fields->removeByName('Settings');
            $fields->addFieldsToTab('Root.Advanced', $settingFields->getChildren());

        }
    }

    public function updateCMSFields(&$fields)
    {
        $fields->removeByName(['ExtraMeta', 'Metadata']);

        if (Controller::has_curr() && get_class(Controller::curr()) === CMSPageEditController::class) {
            // $fields->removeByName(['Title', 'URLSegment', 'MenuTitle']);
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
}
