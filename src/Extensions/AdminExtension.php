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
        // $cmsConfig = \SilverStripe\Forms\HTMLEditor\HTMLEditorConfig::get('cms');
        // $generator = \SilverStripe\Core\Injector\Injector::inst()->get(\SilverStripe\Forms\HTMLEditor\TinyMCECombinedGenerator::class);
        // $link = $generator->getScriptURL($cmsConfig);

        // \SilverStripe\View\Requirements::insertHeadTags('<script type="text/javascript" src="' . $link . '"></script>');
    }
}
