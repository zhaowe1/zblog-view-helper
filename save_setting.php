<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action = 'root';

if (!$zbp->CheckRights($action)) {
    $zbp->ShowError(6);
    die();
}
if (!$zbp->CheckPlugin('ViewHelper')) {
    $zbp->ShowError(48);
    die();
}

if (count($_POST) > 0) {
    CheckIsRefererValid();
}

foreach ($_POST as $key => $value) {
    if(strpos($key, 'category_') !== false || $key == 'global') {
        $zbp->Config('ViewHelper')->$key = intval($value);
    }
}
$zbp->SaveConfig('ViewHelper');
$zbp->SetHint('good');

Redirect('main.php');