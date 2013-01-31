<{extends file="common/main.tpl"}>
<{block:body.content}>
<img src="<{$img}>gra.png" style="position:absolute;top:0px;left:0px;">
</div>
<{/block}>
<{block:appendResource}>
<script src="<{$js}>jquery.sleep.js"></script>
<{/block}>
<{block:javascript}>
$(function(){
	$("img").sleep(10000,function(){
		$(this).fadeOut(2000,function(){
			$(this).fadeIn(2000);
		})
	});
});
<{/block}>