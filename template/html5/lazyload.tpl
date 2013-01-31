<{extends file="common/main.tpl"}>
<{block:body.content}>
<progress id="pro" max="100"></progress>
<{/block}>


<{block:footer.resource}>
<script src="<{$js}>jquery.interval.js"></script>
<{/block}>
<{block:javascript}>
$(function(){
	var data=[<{range 1 to 100}><{$range}>,<{/range}>undefined];
	$("#pro").interval({
		source:data,
		interval:25,
		stack:0.5,
		reduce:function(res,item){
			return res=item;
		},
		loaded:function(elem,res){
			elem.val(res);
		},
		complete:function(){
		
		}
	});
});
<{/block}>