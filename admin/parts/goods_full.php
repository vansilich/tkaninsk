<div class="row product-one">
<h1>{NEWS_TITLE}</h1>
        <div class="col-md-6 col-sm-6 col-xs-12"> 
        
        <div class="big_foto">{BIG_FOTO}</div>
        
        <br>
        {SMALL_FOTO}
        <br class="clearfix">
        <div class="dop_text">
•	Оттенок на вашем мониторе может отличаться от реального.
</div>
        
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 product-text">
           
          <div class="row">
            <div class="col-md-7 col-sm-12 col-xs-12 "> <span class="price-old">{PRICE_OLD}</span> <span class="price-big">{PRICE} р</span> / {edizm} </div>
            <div class="col-md-5 col-sm-12 col-xs-12 article"> Артикул: {ARTICUL} </div>
          </div>
          {PRICE_OPT}
         
         
            <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="hidden" id="_type{NEWS_ID}" name="type_id" value="{TYPE_ID}">
            <div class="count clearfix">
            <a href="javascript:" class="_js_minus{b_type_col}" data-id="{NEWS_ID}">-</a> 
            <input id="_col{NEWS_ID}" placeholder="1" value="1"  data-max="{NEWS_MAXKOL}" class="js_col"/> 
            <a href="javascript:" class="_js_plus{b_type_col}" data-id="{NEWS_ID}" data-max="{NEWS_MAXKOL}">+</a>
            </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
            {select_color}
            </div>
            
          </div>
         
         <div id="no_nal" class="{CLASS_NAL}">Нет в наличии</div>
         <div class="row {CLASS_BASKET}">
            
            <div class="col-md-12 col-sm-12 col-xs-12"><a href="javascript:" data-id="{NEWS_ID}" class="js_basket_color btn">Добавить в корзину</a></div>
          <div class="col-md-6 col-sm-12 col-xs-12">
          <!--a  href="javascript:" onclick="
$('#one_click_name').html('{NEWS_TITLE}');
$('#gid-120').val({NEWS_ID});
ShowWin(120,'{NEWS_TITLE}');return false;" class="btn btn-click">купить в 1 клик</a--></div>
          
          </div>
          <div class="delivery">
          <a href="/delivery/" class="delivery-plus">Подробнее о доставке</a>
         <div class="name">Способы доставки:</div>
         <ul class="ul">
          <li>РЦР по Новосибирску</li>
           <li>Почтой России</li>
           <li>Транспортной компанией</li>
   
         </ul>
         </div>
         
         
         
<div class="tabs" id="_gfull" itemprop="description">
<!--tabs-->
<input id="tab_text" type="radio" name="tabs" checked>
<label for="tab_text" title="Описание">Описание</label>

<input id="tab_har" type="radio" name="tabs">
<label for="tab_har" title="Характеристики">Хар-ки</label>

{GOOD_VIDEO_TAB}
<!--/tabs-->
   
    <section id="content-tab_text">
        {FULL_TEXT}
    </section>
    
    <section id="content-tab_har">
	    {PARAMS}
    </section>
    {GOOD_VIDEO}
</div>    
         
         
         
         
        </div>
      </div>


