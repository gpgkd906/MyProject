<{extends file="common/main.tpl"}>
<{block:body.content}>
<div id="abc" style="position:absolute;top:250px;left:200px;">
<img class="up" src="<{$img}>more1209.gif" style="position:absolute;top:0px;left:0px">
<img class="up" src="<{$img}>vivi1209.gif" style="position:absolute;top:0px;left:200px">
<img class="down" src="<{$img}>test.png" width="500" height="500" style="position:absolute;top:50px;left:0px">
</div>
<{/block}>
<{block:javascript}>
$(function(){
	$(".up").mouseenter(function(){
		$(this).css({top:"-50px"});
		$(".down").css({top:"100px"});
	}).mouseleave(function(){
		$(this).css({top:"0px"});
		$(".down").css({top:"50px"});		
	});
})
<{/block}>