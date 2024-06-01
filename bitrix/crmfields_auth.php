<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!$USER->IsAdmin()) {
    die();
}

CModule::IncludeModule("sproduction.crmfields");

$obRest = SProdCRMFieldsGetRestObj();
$arRes = $obRest->restToken($_REQUEST['code']);

if (!$arRes['error']) {
    LocalRedirect('/bitrix/admin/settings.php?mid=sproduction.crmfields');
}
else {
    echo 'Authorization error';
}
