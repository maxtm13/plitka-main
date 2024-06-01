<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$incl_res = CModule::IncludeModuleEx("sproduction.crmfields");
switch ($incl_res) {
    case MODULE_NOT_FOUND:
        echo 'Module sproduction.crmfields not found.';
        die();
        break;
    case MODULE_DEMO_EXPIRED:
        echo 'Module sproduction.crmfields demo expired.';
        die();
        break;
    default: // MODULE_INSTALLED
}

use Bitrix\Main\Config\Option,
    Bitrix\Sale,
    SProduction\CrmFields\CrmFields;

$obRest = SProdCRMFieldsGetRestObj();

if (!in_array($_REQUEST['event'], array('ONCRMDEALUPDATE', 'ONCRMDEALADD'))) {
    return;
}
SProdCRMFieldsLog('event: '.$_REQUEST['event']);

$arCred = $obRest->getCred();
if (!$arCred) {
    return;
}

// Check source of event
if ($_REQUEST['auth']['member_id'] != $arCred['member_id']) {
    return;
}

// Order number
$arRes = $obRest->restCommand('crm.deal.get', array(
    'id' => $_REQUEST['data']['FIELDS']['ID'],
), $arCred);
if (is_array($arRes['result']) && !empty($arRes['result'])) {
    $arDeal = $arRes['result'];
    $order_id = (int)$arDeal['ORIGIN_ID'];
    SProdCRMFieldsLog('$arDeal:'.print_r($arDeal, true));
}

if (!$order_id) {
    return;
}

// If changing is blocked
$ts = CrmFields::isOrderLocked($order_id);
SProdCRMFieldsLog('isOrderLocked: '.$ts);
// Nothing to do
if ($ts) {
    // Clear block
    CrmFields::delOrderLock($order_id);
}
else {
    // Order data
    if ($obOrder = Sale\Order::load($order_id)) {
        $arFieldsMap = CrmFields::getFieldsMap();
        // If this Deal is new
        if ($_REQUEST['event'] == 'ONCRMDEALADD') {
            // Change fields from CRM to Store
            CrmFields::syncOrderToDeal($obOrder, $arFieldsMap, true);
        }
        // If fields get from CRM
        else {
            // Change fields in Store
            $opt_direction = Option::get("sproduction.crmfields", "direction");
            if (!$opt_direction || $opt_direction == 'full' || $opt_direction == 'ctos') {
                CrmFields::syncDealToOrder($arDeal, $arFieldsMap, true);
            }
        }
    }
}
