<?php

namespace Goldfinch\Basement\Models;

use App\Pages\PDOPage;
use gorriecoe\Link\Models\Link;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Control\Director;
use SilverStripe\ORM\RelationList;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\NumericField;
use SilverStripe\ORM\CMSPreviewable;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Security\Permission;
use SilverStripe\Versioned\Versioned;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\CMS\Controllers\RootURLController;
use DNADesign\Elemental\Controllers\ElementController;
use SilverStripe\VersionedAdmin\Forms\HistoryViewerField;

class PageDataObject extends DataObject implements CMSPreviewable
{
    public static $pdo_up = null;
    public static $pdo_down = null;

    private static $extensions = [
        Versioned::class,
    ];

    private static $singular_name = 'pagedataobject';

    private static $plural_name = 'pagedataobjects';

    private static $versioned_gridfield_extensions = true; // ? check if needed

    private static $controller_class = ElementController::class;

    private static $controller_template = 'PageDataObjectHolder'; // ? check if needed

    private static $table_name = 'PageDataObject';

    private static $show_stage_link = true; // ? check if needed

    private static $show_live_link = true; // ? check if needed

    private static $cascade_duplicates = [];

    private static $db = [
        'URLSegment' => 'Varchar(255)',
        'Title' => 'Varchar(255)',
        'MenuTitle' => 'Varchar(100)',
        'MetaDescription' => 'Text',
        'ShowInSearch' => 'Boolean',
        'Sort' => 'Int',
        'ShowOnlyToRobots' => 'Boolean',
    ];

    private static $casting = [];

    private static $indexes = null;

    private static $defaults = [];

    private static $belongs_to = [];
    private static $has_many = [];
    private static $has_one = [
        // 'ShowOnlyToRobots_Backlink' => Link::class,
    ];
    private static $many_many = [];
    private static $many_many_extraFields = [];
    private static $belongs_many_many = [];

    private static $default_sort = 'Sort';

    private static $searchable_fields = [
        'ID' => [
            'field' => NumericField::class,  // ? check if needed
        ],
        'Title',
    ];

    private static $field_labels = [];

    private static $summary_fields = [];

    public function validate()
    {
        $result = parent::validate();

        // $result->addError('Error message');

        return $result;
    }

    public function onBeforeDelete()
    {
        // ..

        parent::onBeforeDelete();
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab(
          'Root.Settings',
          [
              TextField::create(
                'Component_Name',
                'Component name'
              ),

              CheckboxField::create(
                'Component_Visibility',
                'Visibility'
              ),
          ]
        );

        $fields->addFieldToTab('Root.History', HistoryViewerField::create('PageDataObjectHistory'));

        return $fields;
    }

    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_Company\Website\MyAdmin', 'any', $member);
    }

    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_Company\Website\MyAdmin', 'any', $member);
    }

    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_Company\Website\MyAdmin', 'any', $member);
    }

    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_Company\Website\MyAdmin', 'any', $member);
    }

    protected function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $this->URLSegment = $this->generateURLSegment($this->Title);
    }

    public function PreviewLink($action = null)
    {
        $link = $this->AbsoluteLink($action);
        $this->extend('updatePreviewLink', $link, $action);
        return $link;
    }

    public function generateURLSegment($title)
    {
        $filter = URLSegmentFilter::create();
        $filteredTitle = $filter->filter($title);

        // Fallback to generic page name if path is empty (= no valid, convertable characters)
        if (!$filteredTitle || $filteredTitle == '-' || $filteredTitle == '-1') {
            $filteredTitle = "page-$this->ID";
        }

        // Hook for extensions
        $this->extend('updateURLSegment', $filteredTitle, $title);

        return $filteredTitle;
    }

    public function getMimeType()
    {
        return 'text/html';
    }

    public function CMSEditLink()
    {
        return $this->extend('CMSEditLink')[0] ?? '';
    }

    public function Parent()
    {
        //
    }

    public function RelativeLink($action = null)
    {
        if ($this->ParentID && self::config()->get('nested_urls')) {
            $parent = $this->Parent();
            // If page is removed select parent from version history (for archive page view)
            if ((!$parent || !$parent->exists()) && !$this->isOnDraft()) {
                $parent = Versioned::get_latest_version(self::class, $this->ParentID);
            }
            $base = $parent ? $parent->RelativeLink($this->URLSegment) : null;
        } elseif (!$action && $this->URLSegment == RootURLController::get_homepage_link()) {
            // Unset base for root-level homepages.
            // Note: Homepages with action parameters (or $action === true)
            // need to retain their URLSegment.
            $base = null;
        } else {
            $base = $this->URLSegment;
        }

        // Legacy support: If $action === true, retain URLSegment for homepages,
        // but don't append any action
        if ($action === true) {
            $action = null;
        }

        $link = Controller::join_links($base, $action);

        $this->extend('updateRelativeLink', $link, $base, $action);

        return $link;
    }

    public function Link($action = null)
    {
        $relativeLink = $this->RelativeLink($action);
        $link =  Controller::join_links(Director::baseURL(), $relativeLink);
        $this->extend('updateLink', $link, $action, $relativeLink);
        return $link;
    }

    public function AbsoluteLink($action = null)
    {
        if ($this->hasMethod('alternateAbsoluteLink')) {
            return $this->alternateAbsoluteLink($action);
        } else {
            return Director::absoluteURL((string) $this->Link($action));
        }
    }

    public function SchemaData()
    {
        // Spatie\SchemaOrg\Schema
    }

    public function OpenGraph()
    {
        // Astrotomic\OpenGraph\OpenGraph
    }

    public function PDOChildren()
    {
        if (isset($this->ClassName::$pdo_up) && isset($this->ClassName::$pdo_up_children))
        {
            $method = $this->ClassName::$pdo_up_children;
            $upPDO = $this->ClassName::$pdo_up;
            dd($method);
            // $upPDO = $this->$upPDO();

            // dd($upPDO);
        }
    }

    public function PDOParent()
    {
        if (isset($this->ClassName::$pdo_down))
        {
            $current = $this->ClassName::$pdo_down;

            if ($current === PDOPage::class)
            {
                return $current::get()->filter('PageDataObject', $this->ClassName)->first();
            }

            $parent = $this->$current();

            if (is_subclass_of($parent, RelationList::class))
            {
                // multiple
                return $parent->first();
            }
            else
            {
                // single, belongs to ..
            }
        }
    }

    public function PDOLink($AbsoluteLink = false, $nestedLink = '')
    {
        if ($this->URLSegment)
        {
            $nestedLink = rtrim($this->URLSegment . '/' . $nestedLink, '/');
        }

        if (isset($this->ClassName::$pdo_down))
        {
            $current = $this->ClassName::$pdo_down;

            if ($current === PDOPage::class)
            {
                $pdoPage = $current::get()->filter('PageDataObject', $this->ClassName)->first();

                if ($pdoPage && $pdoPage->exists())
                {
                    if ($AbsoluteLink)
                    {
                        return $pdoPage->AbsoluteLink() . '/' . $nestedLink;
                    }
                    else
                    {
                        return '/' . $pdoPage->URLSegment . '/' . $nestedLink;
                    }
                }

                return '#broken-link';
            }

            $parent = $this->$current();

            if (is_subclass_of($parent, RelationList::class))
            {
                // TODO: multiple conacoil
                // $parent->first()->map('ID', 'URLSegment')->toArray();
                // $parent->map('ID', 'URLSegment')->toArray();
                $obj = $parent->first();

                if ($obj && $obj->exists())
                {
                    return $obj->PDOLink($AbsoluteLink, $nestedLink);
                }
            }
            else
            {
                // single, belongs to ..
            }
        }
        else
        {
            return '#broken-link'; // $this->URLSegment . '/' . $nestedLink;
        }
    }
}
