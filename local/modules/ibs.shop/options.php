<?php
define("MODULE_ID", 'ibs.shop');

$incl_res = CModule::IncludeModuleEx(MODULE_ID);
switch ($incl_res) {
    case MODULE_NOT_FOUND:
        echo BeginNote();
        echo '<span class="required">' . GetMessage('MODULE_NOT_FOUND') . '</span>';
        echo EndNote();
    default:
        break;
}


