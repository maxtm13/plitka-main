<?
if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']){
	$full='width:100%';
	$align='text-align: left;margin-top: 1px;margin-left: 31px;';
} else {
	$align='text-align: center;margin-top: 1px;margin-left: 18px;';
}
if (!empty($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID']) && in_array($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID'], array(4914, 5044, 5649))) { $full.=';float:none;'; }
?>
<meta itemprop="name" content="<?=$arResult['NAME'];?>">
<div class="bx_rt">	
	<div class="new-row-even">
		<span id="cena" style="display: none;"><?if($arResult['MIN_PRICE']['VALUE']) { echo $arResult['MIN_PRICE']['VALUE']; } ?></span>
		<div class="item_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer" style="<?=$full;?>">
			<? if (!empty($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID']) && in_array($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID'], array(4914, 5044, 5649))) { ?>
				<span class="new-price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo $arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['DISPLAY_VALUE']; ?></span>
			<? } else { ?>
				<?
				$boolDiscountShow = (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']);
				?>
				<span class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? $arResult['MIN_PRICE']['PRINT_VALUE'] : ''); ?></span>
				<? 
				$arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']=str_replace('руб.', '<span class="new-ci">руб.</span>', $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);
				?>
				<span class="new-price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></span>
				<? echo ($boolDiscountShow ? '<div style="display: inline-block;padding: 0 0 0 10px;">' : ''); ?>
					<span class="item_economy_price" id="<? echo $arItemIDs['DISCOUNT_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arResult['MIN_PRICE']['PRINT_DISCOUNT_DIFF'])) : ''); ?></span>
			<? } ?>
		    <span itemprop="price" class="dn"><? echo $arResult['MIN_PRICE']['VALUE']; ?></span>
		    <meta itemprop="priceCurrency" content="<? echo $arResult['MIN_PRICE']['CURRENCY']; ?>" />
			<span class="new-ci" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><?
		                                      if (!in_array($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID'], array(4914, 5044, 5649))){ //проверка наличия товара
		                                      echo "/{$arResult['CATALOG_MEASURE_NAME']} "; // выводит ед .изм
		                                    } ?></span>
				<? echo ($boolDiscountShow ? '</div>' : ''); ?>


		</div>
					  <?if($arResult['PROPERTIES']['DISCOUNT']['VALUE'] == 3):?>
					  <div class="item_current_price">
		              <?echo 'Доступно по акционной цене: '; echo $arResult['PRODUCT']['QUANTITY']; ?><span class="measure_discount"><?echo $arResult['CATALOG_MEASURE_NAME']; ?></span>
		              </div>
		<?endif;?>
		<?if($arResult['PROPERTIES']['DISCOUNT']['VALUE'] == 3):?>
		<div class="dopop">
		<?echo 'Большее количество плитки также можно купить, но по обычной цене (не по акционной)'; ?>
		</div>
		<?endif;?>
		<!-- Начало кнопки купить -->

		<div class="item_info_section">
		<?
		if (!in_array($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID'], array(4914, 5044, 5649))){ // скрываем кнопку купить если нет в наличии
			if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
			{?><?
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
				//2018-01-17 корректировка: получение размеров из SIZE_WIDTH и SIZE_LENGTH
				if ($arResult['CATALOG_MEASURE_NAME'] == 'кв. м.' && /*!empty($arResult['DISPLAY_PROPERTIES']['SIZE'])*/ !empty($arResult['DISPLAY_PROPERTIES']['SIZE_WIDTH']['VALUE']) && !empty($arResult['DISPLAY_PROPERTIES']['SIZE_LENGTH']['VALUE']) && $arParams['IBLOCK_ID'] == CATALOG_ID) {
					/*$itemSize = str_replace(array('х', ',', ','), array('x', '.', '.'), strtolower($arResult['DISPLAY_PROPERTIES']['SIZE']['DISPLAY_VALUE']));
					$itemSize = explode('x', $itemSize);*/
					$itemSize =array(
						0 => trim(str_replace(',', '.', $arResult['DISPLAY_PROPERTIES']['SIZE_WIDTH']['DISPLAY_VALUE'])),
						1 => trim(str_replace(',', '.', $arResult['DISPLAY_PROPERTIES']['SIZE_LENGTH']['DISPLAY_VALUE']))
					);
					if (is_numeric($itemSize[0]) && is_numeric($itemSize[1])) {
						$units_recalc = true;
					}
				} ?>
				<?/*---end 2017-04-14---*/?>
				<div class="item_buttons vam">
					<span class="item_bu ons_counter_block">
						<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb new-minus" id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a><input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
								? 1
								: $arResult['CATALOG_MEASURE_RATIO']
							); ?>"<?/*---bgn 2017-04-14---*/?><?php if ($units_recalc) { ?> style="display: none;"<?php } ?><?/*---end 2017-04-14---*/?>><?/*---bgn 2017-04-14---*/?><?php if ($units_recalc) { ?><input id="unit-quantity" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) ? 1 : $arResult['CATALOG_MEASURE_RATIO']); ?>"><?php } ?><?/*---end 2017-04-14---*/?><a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb new-plus" id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
							<span id="testizm">кв.м.</span>
					</span>
					<span class="item_buttons_counter_block">
						<a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>" id="<? echo $arItemIDs['BUY_LINK']; ?>">В корзину</a>
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
						$itemSize[0] = floatval($itemSize[0]) / 100; //см. в м.
						$itemSize[1] = floatval($itemSize[1]) / 100; //см. в м.
						$sqr = $itemSize[0] * $itemSize[1]; //площадь ?>
						<div style="<?=$align;?>" class="calc-measures" data-inpt="<? echo $arItemIDs['QUANTITY']; ?>" data-w="<?php echo $itemSize[0]; ?>" data-h="<?php echo $itemSize[1]; ?>" data-sqr="<?php echo $sqr; ?>" data-pac="<?php echo (!empty($arResult['DISPLAY_PROPERTIES']['SHTUK_V_UPAC'])) ? $arResult['DISPLAY_PROPERTIES']['SHTUK_V_UPAC']['DISPLAY_VALUE'] : 0; ?>">
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
		<? 
			/*RBS_CUSTOM_START*/
			} else { ?>
			
			<input class="rbs-btn-feedback-available" type="button" onClick="pop.show();" value="Сообщить о поступлении">
			<script>
				var pop = BX.PopupWindowManager.create('rbs-feedback-popup', null, {
					autoHide: false,
					offsetLeft: 0,
					offsetTop: 0,
					overlay : true,
					closeByEsc: true,
					titleBar: true,
					closeIcon: {top: '10px', right: '10px'},
					content: 
						'<div class="" id="">'+
							'Оставьте вашу почту для оповщенеия о поступлении товара' +
						'</div>'+
						'<div class="rbs-content-feedback-form" id="rbs_feedback">'+
							'<form method="POST" id="rbs_form_feedback">'+
								'<input type="email" name="email" placeholder="example@email.ru" required>'+
								'<input type="hidden" name="num" value="'+<?=$arResult['ID']?:0?>+'">'+
								'<input type="submit" value="Отправить">'+
							'</form>'+
						'</div>',
					titleBar: 'Сообщить о поступлении товара'
				});
				
				$('#rbs_form_feedback').on('submit', function(e){
					
					$.ajax({
						url: "/include/rbs_ajax_feedback.php",
						dataType: "json",
						type: 'POST',
						method: 'POST',
						data: $(this).serialize(),
						success: function(data){
							if(data.TYPE == 'OK'){
								$('#rbs_feedback').addClass('rbs-success-msg');
							} else {
								$('#rbs_feedback').addClass('rbs-error-msg');
							}

							$('#rbs_feedback').html(data.TEXT);
						},
						error: function(){
							$('#rbs_feedback').addClass('rbs-error-msg');
							$('#rbs_feedback').html('Упс! Возникла ошибка, попробуйте перезагрузить страницу и попробовать снова.');
						}
					});
					return false;
				});
			</script>

		<?} 
			/*RBS_CUSTOM_END*/
		?>
		<!-- Конец кнопки купить -->
	</div>
	
	<div class="prop-icon2" style="margin-top: 15px;">
	              <?if($arResult['PROPERTIES']['DISCOUNT']['VALUE'] == 1):?>
	              	<div class="new-icon">
		                <img src="/bitrix/templates/eshop_adapt_blue/components/bitrix/catalog/template1/bitrix/catalog.element/.default/images/1.png">
		                <span>Бесплатная<br>доставка</span>
	                </div>
	                <div class="new-icon">
		                <img src="/bitrix/templates/eshop_adapt_blue/components/bitrix/catalog/template1/bitrix/catalog.element/.default/images/2.png">
		                <span>Возможны<br>скидки</span>
	                </div>
	              <? elseif($arResult['PROPERTIES']['DISCOUNT']['VALUE'] == 2):?>
	                <span class="prop-ico-discount2" title="<?= GetMessage("DICOUNT_TITLE")?>"></span>
	             <?endif;?>
				<?if(!empty($arResult['PROPERTIES']['HITS']['VALUE'])):?>
					<span class="prop-ico-hit" title="<?= GetMessage("HIT_TITLE")?>"></span>
				<?endif;?>
				<?if(!empty($arResult['PROPERTIES']['SAMPLE']['VALUE'])):?>
					<div class="new-icon">
						<img src="/bitrix/templates/eshop_adapt_blue/components/bitrix/catalog/template1/bitrix/catalog.element/.default/images/3.png">
						<span>Есть<br>образец</span>
					</div>
				<?endif;?>
				<div style="clear: left;"></div>

	</div>
			
	<? if (!empty($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID']) && in_array($arResult['DISPLAY_PROPERTIES']['AVAILABILITY']['VALUE_ENUM_ID'], array(4914, 5044, 5649))) { ?></div><?}?>	
	<div style="clear: left;"></div>
	<div style="padding: 10px 0px;">
	<?
	$useBrands = ('Y' == $arParams['BRAND_USE']);
	$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
	if ($useBrands || $useVoteRating)
	{

	?>
		<div class="new-vote bx_optionblock">
	<?
		if ($useVoteRating)
		{
			?><?$APPLICATION->IncludeComponent(
				"bitrix:iblock.vote",
				"stars1",
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
		<?echo "<div style='padding: 10px 0px;'>Код товара: {$arResult['ID']}</div>";?>
	</div>
	<div style="clear: left;"></div>
	<div class="item_info_section" itemprop="description">
		<?
			if (!empty($arResult['DISPLAY_PROPERTIES']))
			{
		?>
				<? //2018-01-17 добавлено объединение SIZE_WIDTH и SIZE_LENGTH
				$hasSize = false;
				foreach ($arResult['DISPLAY_PROPERTIES'] as $pcode => &$arOneProp)
				{
				?>
				<?
					if (in_array($pcode, array('NIGHT_PRICE', 'MARGIN', 'SIZE'))) continue;
					if (in_array($pcode, array('SIZE_WIDTH', 'SIZE_LENGTH'))) {
						if (!$hasSize) { ?>
							<div class="new-row">
							<span><span><? echo GetMessage('PROP_SIZE_NAME'); ?></span></span>
							<span>
								<? switch ($pcode) {
									case 'SIZE_WIDTH':
										echo $arOneProp['DISPLAY_VALUE'].'x'.$arResult['DISPLAY_PROPERTIES']['SIZE_LENGTH']['DISPLAY_VALUE'];
										break;
									case 'SIZE_LENGTH':
										echo $arResult['DISPLAY_PROPERTIES']['SIZE_WIDTH']['DISPLAY_VALUE'].'x'.$arOneProp['DISPLAY_VALUE'];
										break;
								}
								$hasSize = true; ?>
							</span></div>
						<? }
					} else { ?>
							<div class="new-row"><span><span><? echo $arOneProp['NAME']; ?></span></span><?
							echo '<span>', (
								is_array($arOneProp['DISPLAY_VALUE'])
								? implode(' / ', $arOneProp['DISPLAY_VALUE'])
								: $arOneProp['DISPLAY_VALUE']
							), '</span></div>';
					}
				}
				unset($arOneProp);
		?>
		<? if ($arParams['IBLOCK_ID'] == 4) : ?>
		    <div class="new-row"><span><span><? echo GetMessage('MANUFACTURER') ?></span></span><span><a href="<? echo $arResult['SECTION']['PATH'][1]['SECTION_PAGE_URL'] ?>"><? echo $arResult['SECTION']['PATH'][1]['NAME'] ?></a></span></div>
		    <div class="new-row"><span><span><? echo GetMessage('COLLECTION') ?></span></span><span><a href="<? echo $arResult['SECTION']['PATH'][2]['SECTION_PAGE_URL'] ?>"><? echo $arResult['SECTION']['PATH'][2]['NAME'] ?></a></span></div>
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
	<div class="new-row-even" style="border: 1px solid #e7e7e7;position: relative;">
		<a class="obrz pc">Обратный звонок</a>
		<span style="color: rgb(54,183,40);font-weight: bold;font-size: 18px;line-height: 18px;">Нашли дешевле?</span><br>
		<span class="predl" style="color: rgb(75,75,75);font-size: 16px">Мы предложим еще дешевле!</span>
		<a class="obrz mob">Обратный звонок</a>
	</div>
</div>
<div style="clear: left;height: 15px;"></div>
<div class="bx_md" >
	<div class="prim">
		<div><img src="/upload/prim/1.png"> <span>Вам не хватило?<br>Можно дозаказать!</span></div>
		<div><img src="/upload/prim/2.png"> <span>Без предоплаты!</span></div>
		<div><img src="/upload/prim/3.png"> <span>Мы всегда<br>перезваниваем!</span></div>
		<div><img src="/upload/prim/4.png"> <span>Огромный выбор<br>200 000 товаров!</span></div>
	</div>
</div>
<div style="clear: left;height: 10px;"></div>