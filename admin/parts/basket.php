<!--begin-->

  <!--end_begin-->
  <!--row-->

  <div class="row clearfix row-one">
    <div class="col-md-1 col-sm-1  col-xs-2">{GOOD_IMG}</div>
    <div class="col-md-4 col-sm-2  col-xs-6"><a href="{GOOD_LINK}">{GOOD_TITLE}</a>
      <div class="article">Артикул: {articul}</div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-4">{GOOD_PRICE} р/{edizm}</div>
    <div class="col-md-2 col-sm-3 col-xs-7">
      <div class="count clearfix"><a href="" class="_js_minus{b_type_col}" data-id="{CART_ID}">-</a><input placeholder="1" value="{GOOD_COL}" name="gq[{CART_ID}]" id="_col{CART_ID}" data-max="{NEWS_MAXKOL}" class="js_col"/> <a href="" class="_js_plus{b_type_col}" data-id="{CART_ID}" data-max="{NEWS_MAXKOL}">+</a></div>
    </div>
    <div class="col-md-2 col-sm-2 price col-xs-5">{GOOD_PRICE_ALL} р </div>
    <div class="col-md-1 col-sm-2 del col-xs-12"><a href="?cart_id={CART_ID}&action=del" class="delete">удалить <img src="/img/del.png" width="7" height="7" alt=""/></a></div>
  </div>

  <!--end_row-->




  <!--delim-->
  <!--end_delim-->

  <!--end-->
  <div class="row" style="padding-top:10px; padding-bottom:0px;">
  <div class="col-sm-8">
  <input name="coupon" id="coupon" placeholder="Купон на скидку" class="input" style="margin-top:2px;" value="{coupon}">
  {coupon_msg}
  </div><div class="col-sm-4">
  <a href="javascript:" class="btn btn-click" style="margin-top:0px;" onclick="cbform.submit(); return false;">Применить</a>
  </div></div>
  
  
 <div align="right"><a href="#" onclick="cbform.submit(); return false;" class="btn">Пересчитать</a></div>
    <div class="row itog">
    <div class="col-md-11" align="right">
      <div>Итого заказ на сумму: <span class="price-big"> {GOOD_PRICE_SUM} р</span></div>
      <!--div class="article">От 3 метров действует оптовая цена независимо от суммы</div-->
    </div>
    <div class="col-md-1"></div>
  </div>



<!--end_end-->
