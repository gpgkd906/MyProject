<{extends file="common/main.tpl"}>
<{block:body.content}>
<div id="table">
       <{range 1 to 20}>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div style="width:30px;height:30px;padding:0px;margin:0px" class="disable"></div>
       <{/range}>
</div>
<{/block}>
<{block:footer.resource}>
<script src="<{$js}>tetris.js">
<{/block}>
<{block:javascript}>
<{/block}>