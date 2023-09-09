<?php

namespace Goldfinch\Basement\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\HTMLEditor\HTMLEditorConfig;
use SilverStripe\Forms\HTMLEditor\TinyMCECombinedGenerator;

class AdminExtension extends Extension
{
    public function init()
    {
        // fix issue 'tinymce is not defined'
        $cmsConfig = HTMLEditorConfig::get('cms');
        $generator = Injector::inst()->get(TinyMCECombinedGenerator::class);
        $link = $generator->getScriptURL($cmsConfig);

        Requirements::javascript($link);
    }
}
