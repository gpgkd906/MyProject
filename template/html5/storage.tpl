<{extends file="common/main.tpl"}>
<{block:body.content}>
HTML5 Storage
<progress id="pro" max="100">ダウンロードの進捗</progress>
<{/block}>
<{block:javascript}>
<{parent::block}>
(function(){
	Storage.prototype.set=function(key,value){
		this.setItem(key,JSON.stringify(value));
	}
	Storage.prototype.get=function(key){
		return JSON.parse(this.getItem(key));
	}
	$(window).unload(function(){
		var now=new Date();
		localStorage.set("lastExit",now);
	});
})();
<{/block}>