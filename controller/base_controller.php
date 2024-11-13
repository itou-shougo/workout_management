<?php
require_once('../common/database_manager.php');
require_once('../common/page_transition_manager.php');
require_once('../common/validation_manager.php');


// 基底コントローラ
class BaseController
{
    // コンストラクタ
    function __construct()
    {
        $pg = new PageTransitionManager();
        $pg->isAllowedDirectTransition();
    }
}
