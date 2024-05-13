<?php

use Spatie\Ignition\Ignition;
use SilverStripe\Admin\CMSMenu;
use Symbiote\QueuedJobs\Controllers\QueuedJobsAdmin;

if (class_exists(QueuedJobsAdmin::class)) {
    CMSMenu::remove_menu_class(QueuedJobsAdmin::class);
}

Ignition::make()->register();
