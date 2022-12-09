<div class="select-div cursor _js_search_param">{name} <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/>
</div>
<div class="param_div" id="{sn}">

<label>
<span class="price-from">от</span>
<input class="input small_input" type="text" id="{namefrom}" name="{namefrom}" value="{curvalfrom}" placeholder="{minvalue}">
</label>

<label>
<span class="price-before">до</span>
<input class="input small_input" type="text" id="{nameto}" name="{nameto}" value="{curvalto}" placeholder="{maxvalue}">
</label>{edizm}

</div>




<script>
document.addEventListener('DOMContentLoaded', function () {
		$("input#{namefrom}").change(function(){
			var value1=$("input#{namefrom}").val();
			var value2=$("input#{nameto}").val();
			if (value1 < {minvalue}) { value1 = {minvalue}; $("input#{namefrom}").val({minvalue})}

			if(parseInt(value1) > parseInt(value2)){
				value1 = value2;
				$("input#{namefrom}").val(value1);
			}
			onSchange();

		});

		$("input#{nameto}").change(function(){
			var value1=$("input#{namefrom}").val();
			var value2=$("input#{nameto}").val();

			if (value2 > {maxvalue}) { value2 = {maxvalue}; $("input#{nameto}").val({maxvalue})}

			if(parseInt(value1) > parseInt(value2)){
				value2 = value1;
				$("input#{nameto}").val(value2);
			}
			onSchange();
		});
});
</script>