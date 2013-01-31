<{extends file="common/main.tpl"}>
<{block:body.content}>


<{/block}>
<{block:javascript}>
var server="ws://vm:8888/demo";
var ws=new WebSocket(server);
ws.onopen=function(e){
	console.log("connect to server");
};
ws.onclose=function(e){
	console.log("close socket");
};
ws.onmessage=function(e){
	console.log("reviced:"+e.data);
};
ws.onerroe=function(e){
	console.error(e.data);
}
<{/block}>