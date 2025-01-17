<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
?><div class="bx_item_detail <? echo $templateData['TEMPLATE_CLASS']; ?>" id="<? echo $arItemIDs['ID']; ?>" itemscope itemtype="http://schema.org/Product">
<?
if ('Y' == $arParams['DISPLAY_NAME'])
{
?>
<div class="bx_item_title">
	<h1 itemprop="name">
		<? echo (
			isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
			? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
			: $arResult["NAME"]
		); ?>
	</h1>
</div>
<?
}
reset($arResult['MORE_PHOTO']);
$arFirstPhoto = current($arResult['MORE_PHOTO']);
?>
	<div class="bx_item_container">
		<div class="bx_lt">
<div class="bx_item_slider" id="<? echo $arItemIDs['BIG_SLIDER_ID']; ?>">
	<div class="bx_bigimages id="<? echo $arItemIDs['BIG_IMG_CONT_ID']; ?>">
		<div class="bx_bigimages_imgcontainer">
			<span class="bx_bigimages_aligner"><img
				id="<? echo $arItemIDs['PICT']; ?>"
				src="<? echo $arFirstPhoto['SRC']; ?>"
				alt="<? echo $strAlt; ?>"
				title="<? echo $strTitle; ?>"
        itemprop="image"
			></span>
		<!--<div class="prop-icon">
			<?if(!empty($arResult['PROPERTIES']['DISCOUNT']['VALUE'])):?>
				<span class="prop-ico-discount" title="<?= GetMessage("DICOUNT_TITLE")?>"></span>
			<?endif;?>
			<?if(!empty($arResult['PROPERTIES']['HITS']['VALUE'])):?>
				<span class="prop-ico-hit" title="<?= GetMessage("HIT_TITLE")?>"></span>
			<?endif;?>			
			<?if(!empty($arResult['PROPERTIES']['SAMPLE']['VALUE'])):?>
				<span class="prop-ico-sample" title="<?= GetMessage("SAMPLE_TITLE")?>"></span>
			<?endif;?>			
		</div>-->			
<?
if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
{
?>
			<div class="bx_stick_disc" id="<? echo $arItemIDs['DISCOUNT_PICT_ID'] ?>" style="display: none;"></div>
<?
}
if ($arResult['LABEL'])
{
?>
			<div class="bx_stick new" id="<? echo $arItemIDs['STICKER_ID'] ?>"><? echo $arResult['LABEL_VALUE']; ?></div>
<?
}
?>
		</div>
	</div>
<?
if ($arResult['SHOW_SLIDER'])
{
	if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS']))
	{
		if (5 < $arResult['MORE_PHOTO_COUNT'])
		{
			$strClass = 'bx_slider_conteiner full';
			$strOneWidth = (100/$arResult['MORE_PHOTO_COUNT']).'%';
			$strWidth = (20*$arResult['MORE_PHOTO_COUNT']).'%';
			$strSlideStyle = '';
		}
		else
		{
			$strClass = 'bx_slider_conteiner';
			$strOneWidth = '20%';
			$strWidth = '100%';
			$strSlideStyle = 'display: none;';
		}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_ID']; ?>">
		<div class="bx_slider_scroller_container">
			<div class="bx_slide">
				<ul style="width: <? echo $strWidth; ?>;" id="<? echo $arItemIDs['SLIDER_LIST']; ?>">
<?
		foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
		{
?>
					<li data-value="<? echo $arOnePhoto['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>;"><span class="cnt"><span class="cnt_item" style="background-image:url('<? echo $arOnePhoto['SRC']; ?>');"></span></span></li>
<?
		}
		unset($arOnePhoto);
?>
				</ul>
			</div>
			<div class="bx_slide_left" id="<? echo $arItemIDs['SLIDER_LEFT']; ?>" style="<? echo $strSlideStyle; ?>"></div>
			<div class="bx_slide_right" id="<? echo $arItemIDs['SLIDER_RIGHT']; ?>" style="<? echo $strSlideStyle; ?>"></div>
		</div>
	</div>
<?
	}
	else
	{
		foreach ($arResult['OFFERS'] as $key => $arOneOffer)
		{
			if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
				continue;
			$strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');
			if (5 < $arOneOffer['MORE_PHOTO_COUNT'])
			{
				$strClass = 'bx_slider_conteiner full';
				$strOneWidth = (100/$arOneOffer['MORE_PHOTO_COUNT']).'%';
				$strWidth = (20*$arOneOffer['MORE_PHOTO_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_slider_conteiner';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'].$arOneOffer['ID']; ?>" style="display: <? echo $strVisible; ?>;">
		<div class="bx_slider_scroller_container">
			<div class="bx_slide">
				<ul style="width: <? echo $strWidth; ?>;" id="<? echo $arItemIDs['SLIDER_LIST_OF_ID'].$arOneOffer['ID']; ?>">
<?
			foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto)
			{
?>
					<li data-value="<? echo $arOneOffer['ID'].'_'.$arOnePhoto['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>"><span class="cnt"><span class="cnt_item" style="background-image:url('<? echo $arOnePhoto['SRC']; ?>');"></span></span></li>
<?
			}
			unset($arOnePhoto);
?>
				</ul>
			</div>
			<div class="bx_slide_left" id="<? echo $arItemIDs['SLIDER_LEFT_OF_ID'].$arOneOffer['ID'] ?>" style="<? echo $strSlideStyle; ?>" data-value="<? echo $arOneOffer['ID']; ?>"></div>
			<div class="bx_slide_right" id="<? echo $arItemIDs['SLIDER_RIGHT_OF_ID'].$arOneOffer['ID'] ?>" style="<? echo $strSlideStyle; ?>" data-value="<? echo $arOneOffer['ID']; ?>"></div>
		</div>
	</div>
<?
		}
	}
}
?>
</div>
		</div>

		<div class="bx_rt">
<?
$useBrands = ('Y' == $arParams['BRAND_USE']);
$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
if ($useBrands || $useVoteRating)
{
?>
	<div class="bx_optionblock">
<?
	if ($useVoteRating)
	{
		?><?$APPLICATION->IncludeComponent(
			"bitrix:iblock.vote",
			"stars",
			array(
				"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
				"IBLOCK_ID" => $arParams['IBLOCK_ID'],
				"ELEMENT_ID" => $arResult['ID'],
				"ELEMENT_CODE" => "",
				"MAX_VOTE" => "5",
				"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
				"SET_STATUS_404" => "N",
				"DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
				"CACHE_TYPE" => $arParams['CACHE_TYPE'],
				"CACHE_TIME" => $arParams['CACHE_TIME']
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?><?
	}
	if ($useBrands)
	{
		?><?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", ".default", array(
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['IBLOCK_ID'],
			"ELEMENT_ID" => $arResult['ID'],
			"ELEMENT_CODE" => "",
			"PROP_CODE" => $arParams['BRAND_PROP_CODE'],
			"CACHE_TYPE" => $arParams['CACHE_TYPE'],
			"CACHE_TIME" => $arParams['CACHE_TIME'],
			"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
			"WIDTH" => "",
			"HEIGHT" => ""
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?><?
	}
?>
	</div>
<?
}
unset($useVoteRating);
unset($useBrands);
?>
<div class="item_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	<? if (!empty($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID']) && in_array($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID'], array(4914, 5044, 5649))) { ?>
		<div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo $arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['DISPLAY_VALUE']; ?></div>
	<? } else /*if (empty($arResult["PROPERTIES"]["RECOMMENDED_PRICE"]["VALUE"]))*/ { ?>
		<?
		$boolDiscountShow = (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']);
		?>
		<div class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? $arResult['MIN_PRICE']['PRINT_VALUE'] : ''); ?></div>
		<div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></div>
		<div class="item_economy_price" id="<? echo $arItemIDs['DISCOUNT_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arResult['MIN_PRICE']['PRINT_DISCOUNT_DIFF'])) : ''); ?></div>
	<? } /*else { ?>
		<div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo GetMessage("CALL_FOR_PRICE"); ?></div>
	<? }*/ ?>
    <span itemprop="price" class="dn"><? echo $arResult['MIN_PRICE']['VALUE']; ?></span>
    <meta itemprop="priceCurrency" content="<? echo $arResult['MIN_PRICE']['CURRENCY']; ?>" />
	<span class="item_current_price" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><?
                                      if (!in_array($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID'], array(4914, 5044, 5649))){ //проверка наличия товара
                                      echo "/ {$arResult['CATALOG_MEASURE_NAME']} "; // выводит ед .изм
                                    } ?></span>
</div>
					<? echo "Код товара: {$arResult['ID']} "; ?><br><br>
<?
if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
{
?>
<a href="/collections/deshevle.php/" ><img src="/upload/iblock/files/buttonDeshev.png" ></a>
<br>
<br>

<style type="text/css">{
 margin:0;
 width:100%;
  height:100%;
}
#Wrapp,#Wrapp2 {
 top:0;
  left:0;
 position:fixed;
 background-color:#000;
 opacity: 0.87;
 width:100%;
 height:100%;
 z-index:10000;
}
#tt,#tt2 {
  position:relative;
  background-color:#fff;
  width:420px;
  padding:12px;
  height:440px;
  margin:20% auto auto auto;
  border:red solid 1px;

}
div.close {
  cursor:pointer;
  position:absolute;
  font-weight:700;
  text-shadow:#000 1px 1px 0;
  color:red;
  right:7px;
  top:2px;
}
</style>
<script type="text/javascript">
  function openbox(id,tt) {
	var div = document.getElementById(id);
	var tt_div = document.getElementById(tt);
	if(div.style.display == 'block') {
		div.style.display = 'none';
	}
	else {
		div.style.display = 'block';
	}
  }
</script>
<div id="Wrapp" style="display:none">
<div id="tt">
<div class="close" onclick="openbox('Wrapp')">x</div>
<br>
<p>Для того чтобы купить плитку в кредит, нужно перейти в корзину и выбрать способ оплаты "Кредит", затем нажать оформить заказ.
</p>
<img src="/bitrix/templates/eshop_adapt_blue/images/credit5.jpg" alt="Кредит"">

</div>
</div> 
<div class="i_creditblock_2">

		<div class="i_creditbtn_first">
		    <p id="<? echo $arItemIDs['PRICE']; ?>"><? echo round (0.1*$arResult['MIN_PRICE']['DISCOUNT_VALUE'] ); ?><span>руб/мес</span></p>
		    <a href="" onclick="openbox('Wrapp');return false;">Купить в кредит</a>
		</div>
		
</div>
<style>
.i_creditwrapper{width:490px; height:420px; padding: 20px;}
.i_creditblock_l{float: left;}
.i_creditblock_r{float: right;}

.i_creditbtn_first, .i_creditbtn_first2, .i_creditbtn_next, .i_creditbtn_next2 p, .i_creditbtn_next3 p{border-radius:3px; -moz-border-radius:3px;  -webkit-border-radius:3px; background:#fff;
	-moz-box-shadow: 0px 1px 2px 1px rgba(202,207,208,0.7);
	box-shadow: 0px 1px 2px 1px rgba(202,207,208,0.7);
	-webkit-box-shadow: 0px 1px 2px 1px rgba(202,207,208,0.7);
}
.i_creditbtn_first a, .i_creditbtn_next a, .i_creditbtn_next2 a, .i_creditbtn_first2 a, .i_creditbtn_next3 a, .i_creditbtn_first2 a{text-shadow: 1px 1px 0px #666;
	-webkit-box-shadow: 0px 1px 1px 1px rgba(199,204,206,0.7);
	-moz-box-shadow: 0px 1px 1px 1px rgba(199,204,206,0.7);
	box-shadow: 0px 1px 1px 1px rgba(199,204,206,0.7);
}
.i_creditbtn_first a:hover{opacity: 1;}
.i_creditbtn_first2 a:hover{opacity: 1;}
.i_creditbtn:hover{opacity: 1;}
.i_creditbtn_next a:hover{opacity: 1;}

.i_creditbtn_first{width: 220px; height: 38px; font-family:"Open Sans"; padding-top:1px; padding-bottom:10px;} 
	.i_creditbtn_first a{text-decoration:none; opacity: .8; margin: 2px 3px 2px 0; color:#fff; font-size:14px; text-align: center; background: #e08d0d; width:147px; float:right; padding:7px 0;}/*---для ссылки можно поменять background-*/
	.i_creditbtn_first p{font-weight:bold; color:#000;  font-size:16px; float:left; text-align:center; width:70px; line-height: 1.3; padding-top: 1px; margin: 0px;}
	.i_creditbtn_first p span{color: #8f8f8f; font-weight:normal; font-size:11px; margin-top:0; padding:0; display: block;}

 .i_creditbtn_first a.i_creditorange{background:#f0ad27;}
 .i_creditbtn_first a.i_creditgreen{background:#4cb866;}
 .i_creditbtn_first a.i_creditblue{background:#17b7c5;}
 .i_creditbtn_first a.i_creditdark_blue{background:#1081df;}
 .i_creditbtn_first a.i_creditpink{background:#cb76ee;}
 .i_creditbtn_first a.i_creditgray{background:#443f50;}
 .i_creditbtn_first2 a.i_creditgray{background:#443f50;}

.i_creditbtn_next{width:139px; height: 70px; font-family:"Open Sans"}
	.i_creditbtn_next a {text-decoration:none; opacity: .8; margin:5px 2px 2px 2px; color:#fff; display: block; font-size:14px; text-align: center; background: #ed574d; width:135px;padding:7px 0;}/*---для ссылки можно поменять background-*/
	.i_creditbtn_next p {color:#000; line-height: 1.8; font-size:16px; text-align:center; font-weight:bold; position: relative; top: 2px;}
	.i_creditbtn_next p span{color: #8f8f8f; font-size:12px; margin-top:0; padding:0; font-weight: normal; margin-left:5px; }



.i_creditbtn{text-decoration:none; opacity: .8; line-height: 2.3; color:#fff; font-size:14px;font-family:"Open Sans"; text-align: center; background: #ed574d; width:140px; display:block; height: 34px;text-shadow: 1px 1px 1px #333;}
</style>



<div class="prop-icon2">
			<?if(!empty($arResult['PROPERTIES']['DISCOUNT']['VALUE'])):?>
				<span class="prop-ico-discount" title="<?= GetMessage("DICOUNT_TITLE")?>"></span>
			<?endif;?>
			<?if(!empty($arResult['PROPERTIES']['HITS']['VALUE'])):?>
				<span class="prop-ico-hit" title="<?= GetMessage("HIT_TITLE")?>"></span>
			<?endif;?>			
			<?if(!empty($arResult['PROPERTIES']['SAMPLE']['VALUE'])):?>
				<span class="prop-ico-sample" title="<?= GetMessage("SAMPLE_TITLE")?>"></span>
			<?endif;?>			
</div>			
<a href="/about/disign/" ><img src="/upload/iblock/files/d4.png" ></a>
<a href="/collections/remont.php/" ><img src="/upload/iblock/files/d3.png" ></a>
<div class="item_info_section" itemprop="description">
<?
	if (!empty($arResult['DISPLAY_PROPERTIES']))
	{
?>
	<dl>
<?
		foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
		{
?>
		<dt><? echo $arOneProp['NAME']; ?></dt><span class="dn"> - </span><?
			echo '<dd>', (
				is_array($arOneProp['DISPLAY_VALUE'])
				? implode(' / ', $arOneProp['DISPLAY_VALUE'])
				: $arOneProp['DISPLAY_VALUE']
			), '</dd><span class="dn">. </span>';
		}
		unset($arOneProp);
?>
<? if ($arParams['IBLOCK_ID'] == 4) : ?>
    <dt><? echo GetMessage('MANUFACTURER') ?></dt><span class="dn"> - </span><dd><a href="<? echo $arResult['SECTION']['PATH'][1]['SECTION_PAGE_URL'] ?>"><? echo $arResult['SECTION']['PATH'][1]['NAME'] ?></a></dd>
    <dt><? echo GetMessage('COLLECTION') ?></dt><span class="dn"> - </span><dd><a href="<? echo $arResult['SECTION']['PATH'][2]['SECTION_PAGE_URL'] ?>"><? echo $arResult['SECTION']['PATH'][2]['NAME'] ?></a></dd><span class="dn">. </span>
	</dl>
<? endif; ?>
<?
	}
	if ($arResult['SHOW_OFFERS_PROPS'])
	{
?>
	<dl id="<? echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></dl>
<?
	}
?>
</div>
<?
}
if ('' != $arResult['PREVIEW_TEXT'])
{
	if (
		'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
		|| ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
	)
	{
?>
<div class="item_info_section">
<?
		echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');
?>
</div>
<?
	}
}
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
{
	$arSkuProps = array();
?>
<div class="item_info_section" style="padding-right:150px;" id="<? echo $arItemIDs['PROP_DIV']; ?>">
<?
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
			continue;
		$arSkuProps[] = array(
			'ID' => $arProp['ID'],
			'SHOW_MODE' => $arProp['SHOW_MODE'],
			'VALUES_COUNT' => $arProp['VALUES_COUNT']
		);
		if ('TEXT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_size full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_size';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_size_scroller_container"><div class="bx_size">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
?>
				<li
					data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID']; ?>"
					data-onevalue="<? echo $arOneValue['ID']; ?>"
					style="width: <? echo $strOneWidth; ?>;"
				><i></i><span class="cnt"><? echo htmlspecialcharsex($arOneValue['NAME']); ?></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
		elseif ('PICT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_scu full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_scu';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_scu_scroller_container"><div class="bx_scu">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
?>
				<li
					data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID'] ?>"
					data-onevalue="<? echo $arOneValue['ID']; ?>"
					style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>;"
				><i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
				<span class="cnt"><span class="cnt_item"
					style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
					title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"
				></span></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
	}
	unset($arProp);
?>
</div>
<?
}
?>
<div class="item_info_section">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	$canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
}
else
{
	$canBuy = $arResult['CAN_BUY'];
}
if ($canBuy)
{
	$buyBtnMessage = ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
	$buyBtnClass = 'bx_big bx_bt_button bx_cart';
}
else
{
	$buyBtnMessage = ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
	$buyBtnClass = 'bx_big bx_bt_button_type_2 bx_cart';
}
if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
{
?>
	<?/*---bgn 2017-04-14---*/?>
	<?php $units_recalc = false;
	if ($arResult['CATALOG_MEASURE_NAME'] == 'кв. м.' && !empty($arResult['DISPLAY_PROPERTIES']['SIZE']) && $arParams['IBLOCK_ID'] == CATALOG_ID) {
		$itemSize = str_replace(array('х', ',', ','), array('x', '.', '.'), strtolower($arResult['DISPLAY_PROPERTIES']['SIZE']['DISPLAY_VALUE']));
		$itemSize = explode('x', $itemSize);
		if (is_numeric($itemSize[0]) && is_numeric($itemSize[1])) {
			$units_recalc = true;
		}
	} ?>
	<?/*---end 2017-04-14---*/?>
	<span class="item_section_name_gray"><? echo GetMessage('CATALOG_QUANTITY'); ?></span>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
			<input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					? 1
					: $arResult['CATALOG_MEASURE_RATIO']
				); ?>"<?/*---bgn 2017-04-14---*/?><?php if ($units_recalc) { ?> style="display: none;"<?php } ?><?/*---end 2017-04-14---*/?>>
			<?/*---bgn 2017-04-14---*/?>
			<?php if ($units_recalc) { ?>
				<input id="unit-quantity" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) ? 1 : $arResult['CATALOG_MEASURE_RATIO']); ?>">
			<?php } ?>
			<?/*---end 2017-04-14---*/?>
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
			<span class="bx_cnt_desc<?/*---bgn 2017-04-14---*/?><?php if ($units_recalc) { ?> hidden<?php } ?><?/*---end 2017-04-14---*/?>" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><? echo (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
		</span>
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
<?
	if ('Y' == $arParams['DISPLAY_COMPARE'])
	{
?>
			<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" style="margin-left: 10px"><? echo ('' != $arParams['MESS_BTN_COMPARE']
					? $arParams['MESS_BTN_COMPARE']
					: GetMessage('CT_BCE_CATALOG_COMPARE')
				); ?></a>
<?
	}
?>
		</span>
		<?/*---bgn 2017-04-14---*/?>
		<?php if ($units_recalc) {
			//переведём см. в м.
			$itemSize[0] = floatval($itemSize[0]) / 100;
			$itemSize[1] = floatval($itemSize[1]) / 100;
			$sqr = $itemSize[0] * $itemSize[1]; ?>
			<div class="calc-measures" data-inpt="<? echo $arItemIDs['QUANTITY']; ?>" data-w="<?php echo $itemSize[0]; ?>" data-h="<?php echo $itemSize[1]; ?>" data-sqr="<?php echo $sqr; ?>" data-pac="<?php echo (!empty($arResult['DISPLAY_PROPERTIES']['SHTUK_V_UPAC'])) ? $arResult['DISPLAY_PROPERTIES']['SHTUK_V_UPAC']['DISPLAY_VALUE'] : 0; ?>">
				<a class="active" href="javascript:void(0)" data-unit="m"><?php echo $arResult['CATALOG_MEASURE_NAME']; ?></a>
				<a href="javascript:void(0)" data-unit="i">шт.</a>
				<?php /*if (!empty($arResult['DISPLAY_PROPERTIES']['SHTUK_V_UPAC'])) { ?>
					<a href="javascript:void(0)" data-unit="p">упак.</a>
				<?php }*/ ?>
				<?/*<div class="hidden">в упаковке <?php echo $arResult['DISPLAY_PROPERTIES']['SHTUK_V_UPAC']['VALUE']; ?> шт. = <?php echo (intval($arResult['DISPLAY_PROPERTIES']['SHTUK_V_UPAC']['VALUE'] * $sqr * 10000) / 10000).' '.$arResult['CATALOG_MEASURE_NAME']; ?></div>*/?>
			</div>
		<?php } ?>
		<?/*---end 2017-04-14---*/?>
	</div>
<?
	if ('Y' == $arParams['SHOW_MAX_QUANTITY'])
	{
		if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
		{
?>
	<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>" style="display: none;"><? echo GetMessage('OSTATOK'); ?>: <span></span></p>
<?
		}
		else
		{
			if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO'])
			{
?>
	<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>"><? echo GetMessage('OSTATOK'); ?>: <span><? echo $arResult['CATALOG_QUANTITY']; ?></span></p>
<?
			}
		}
	}
}
else
{
?>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
<?
	if ('Y' == $arParams['DISPLAY_COMPARE'])
	{
?>
			<a id="<? echo $arItemIDs['COMPARE_LINK']; ?>" href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" style="margin-left: 10px"><? echo ('' != $arParams['MESS_BTN_COMPARE']
					? $arParams['MESS_BTN_COMPARE']
					: GetMessage('CT_BCE_CATALOG_COMPARE')
				); ?></a>
<?
	}
?>
		</span>
	</div>
<?
}
?>
</div>
			<div class="clb"></div>
		</div>

		<div class="bx_md">
<div class="item_info_section">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	if ($arResult['OFFER_GROUP'])
	{
		foreach ($arResult['OFFERS'] as $arOffer)
		{
			if (!$arOffer['OFFER_GROUP'])
				continue;
?>
	<span id="<? echo $arItemIDs['OFFER_GROUP'].$arOffer['ID']; ?>" style="display: none;">
<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
		"ELEMENT_ID" => $arOffer['ID'],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?
?>
	</span>
<?
		}
	}
}
else
{
	if ($arResult['MODULES']['catalog'])
	{
?><?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arResult["ID"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?
	}
}
?>
</div>
		</div>
		<div class="bx_md">
<div class="item_info_section">
<?
if ('' != $arResult['DETAIL_TEXT'])
{
?>
	<div class="bx_item_description">
		<div class="bx_item_section_name_gray" style="border-bottom: 1px solid #f2f2f2;"><? echo GetMessage('FULL_DESCRIPTION'); ?></div>
<?
	if ('html' == $arResult['DETAIL_TEXT_TYPE'])
	{
		echo $arResult['DETAIL_TEXT'];
	}
	else
	{
		?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
	}
?>
	</div>
<?
}
?>
<?if($arResult["IBLOCK_ID"] == 4):?>
<div class="bx_item_description">
	<?= GetMessage("DETAIL_TEXT", $arResult['PLACEHOLDER'])?>
</div>
<?endif;?>
</div>
		</div>
		<div class="bx_lb">
<div class="tac ovh">
</div>
<div class="tab-section-container">
<?
if ('Y' == $arParams['USE_COMMENTS'])
{
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.comments",
	"",
	array(
		"ELEMENT_ID" => $arResult['ID'],
		"ELEMENT_CODE" => "",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"URL_TO_COMMENT" => "",
		"WIDTH" => "",
		"COMMENTS_COUNT" => "5",
		"BLOG_USE" => $arParams['BLOG_USE'],
		"FB_USE" => $arParams['FB_USE'],
		"FB_APP_ID" => $arParams['FB_APP_ID'],
		"VK_USE" => $arParams['VK_USE'],
		"VK_API_ID" => $arParams['VK_API_ID'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"BLOG_TITLE" => "",
		"BLOG_URL" => "",
		"PATH_TO_SMILE" => "/bitrix/images/blog/smile/",
		"EMAIL_NOTIFY" => "N",
		"AJAX_POST" => "Y",
		"SHOW_SPAM" => "Y",
		"SHOW_RATING" => "N",
		"FB_TITLE" => "",
		"FB_USER_ADMIN_ID" => "",
		"FB_APP_ID" => $arParams['FB_APP_ID'],
		"FB_COLORSCHEME" => "light",
		"FB_ORDER_BY" => "reverse_time",
		"VK_TITLE" => "",
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>
<?
}
?>
</div>
		</div>
			<div style="clear: both;"></div>
	</div>
	<div class="clb"></div>
</div><?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	foreach ($arResult['JS_OFFERS'] as &$arOneJS)
	{
		if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE'])
		{
			$arOneJS['PRICE']['PRINT_DISCOUNT_DIFF'] = GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF']));
			$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
		}
		$strProps = '';
		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($arOneJS['DISPLAY_PROPERTIES']))
			{
				foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp)
				{
					$strProps .= '<dt>'.$arOneProp['NAME'].'</dt><dd>'.(
						is_array($arOneProp['VALUE'])
						? implode(' / ', $arOneProp['VALUE'])
						: $arOneProp['VALUE']
					).'</dd>';
				}
			}
		}
		$arOneJS['DISPLAY_PROPERTIES'] = $strProps;
	}
	if (isset($arOneJS))
		unset($arOneJS);
	$arJSParams = array(
		'CONFIG' => array(
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'NAME' => $arResult['~NAME']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $arSkuProps
	);
}
else
{
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
	{
?>
<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
<?
		if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
		{
			foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
			{
?>
	<input
		type="hidden"
		name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
		value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"
	>
<?
				if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
					unset($arResult['PRODUCT_PROPERTIES'][$propID]);
			}
		}
		$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
		if (!$emptyProductProperties)
		{
?>
	<table>
<?
			foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo)
			{
?>
	<tr><td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
	<td>
<?
				if(
					'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
					&& 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
				)
				{
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><label><input
							type="radio"
							name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
							value="<? echo $valueID; ?>"
							<? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>
						><? echo $value; ?></label><br><?
					}
				}
				else
				{
					?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><option
							value="<? echo $valueID; ?>"
							<? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>
						><? echo $value; ?></option><?
					}
					?></select><?
				}
?>
	</td></tr>
<?
			}
?>
	</table>
<?
		}
?>
</div>
<?
	}
	$arJSParams = array(
		'CONFIG' => array(
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'PICT' => $arFirstPhoto,
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'PRICE' => $arResult['MIN_PRICE'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
			'BUY_URL' => $arResult['~BUY_URL'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL']
		)
	);
	unset($emptyProductProperties);
}
?>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
BX.message({
	MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CT_BCE_CATALOG_BUY')); ?>',
	MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CT_BCE_CATALOG_ADD')); ?>',
	MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? CUtil::JSEscape($arParams['MESS_NOT_AVAILABLE']) : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE')); ?>',
	TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
	TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
	BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
	BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE') ?>'
});
</script>