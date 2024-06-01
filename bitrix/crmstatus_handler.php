<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$incl_res = CModule::IncludeModuleEx("sproduction.crmstatus");
switch ($incl_res) {
    case MODULE_NOT_FOUND:
        echo 'Module sproduction.crmstatus not found.';
        die();
        break;
    case MODULE_DEMO_EXPIRED:
        echo 'Module sproduction.crmstatus demo expired.';
        die();
        break;
    default: // MODULE_INSTALLED
}

use Bitrix\Main\Config\Option;
use SProduction\CrmStatus\CrmStatus;

SProdCRMStatusLog('(crmstatus_handler) ' . print_r($_REQUEST, true));

$obRest = getRestObj();

if (!in_array($_REQUEST['event'], array('ONCRMDEALUPDATE', 'ONCRMDEALADD'))) {
    return;
}

$arCred = $obRest->getFileCred();
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
    SProdCRMStatusLog('(crmstatus_handler) $arDeal:'.print_r($arDeal, true));
}

if (!$order_id) {
    return;
}

// If status changing is blocked
$ts = CrmStatus::isOrderBlocked($order_id);
// Nothing to do
if ($ts) {
    // Clear block
    CrmStatus::delOrderBlock($order_id);
}
else {
    // Order data
    $arFilter = array('ID' => $order_id);
    $arSelect = array('ID', 'ACCOUNT_NUMBER', 'STATUS_ID', 'DATE_INSERT', 'DATE_STATUS', 'PAYED');
    $db = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter, false, false, $arSelect);
    if ($arOrder = $db->Fetch()) {
        SProdCRMStatusLog('(crmstatus_handler) $arOrder:'.print_r($arOrder, true));
        $arStatTbl = CrmStatus::getStatusTable();
        $opt_payment_block = Option::get("sproduction.crmstatus", "payment_block");
        $opt_direction = Option::get("sproduction.crmstatus", "direction");
        // If this Deal is new
        if ($_REQUEST['event'] == 'ONCRMDEALADD') {
            SProdCRMStatusLog('(crmstatus_handler) New deal');
            // Change CRM status to the Store status
            CrmStatus::syncOrderToDeal($arOrder, $arStatTbl, true);
        }
        // If CRM status is changed after payment
        elseif ($arOrder['PAYED'] == 'Y' && $arDeal['STAGE_ID'] == 'WON'
            && ($opt_payment_block == 'hard' || CrmStatus::isOrderPayed($order_id))) {
            SProdCRMStatusLog('(crmstatus_handler) Status needs to be reset');
            CrmStatus::delOrderPayed($order_id);
            // Change CRM status back to the Store status
            CrmStatus::syncOrderToDeal($arOrder, $arStatTbl, true);
            // Save a record that the status has already been changed
            CrmStatus::addOrderBlock($order_id);
        }
        // If status get from CRM
        else {
            SProdCRMStatusLog('(crmstatus_handler) Status doesn\'t needed to be reset');
            // Change status in Store
            if (!$opt_direction || $opt_direction == 'full' || $opt_direction == 'ctos') {
                CrmStatus::syncDealToOrder($arDeal, $arStatTbl, true);
            }
        }
    }
}
