<{extends file="common/main.tpl"}>
<{block:body.content}>
<div id="time"></div>
<hr/>
<div id="count"></div>
<{*

dさdさ
*}>
<{/block}>


ここはなにを書いても反映はしないから，コメントなどをここで置いとこう

<{block:javascript}>
var Timer=function(){
    var timeArea=$("#time");
    var serverTime=Math.floor(<{echo microtime(true)*1000 }>);
    return function(){
    	   var time=new Date(serverTime);
    	   serverTime=serverTime + 1000;
	   var timeString=[];
	   timeString.push(time.getFullYear()+"年");
	   var month=time.getMonth()+1;
	   timeString.push(month+"月");
	   var date=time.getDate();
	   timeString.push(date+"日");
	   var hour=time.getHours();
	   timeString.push(hour+"時");
	   var min=time.getMinutes();
	   timeString.push(min+"分");
	   var sec=time.getSeconds();
	   timeString.push(sec+"秒");
	   timeArea.html(timeString.join(" "));
    }
}();
var countTime=function(){
    var countArea=$("#count");
    var serverTime=Math.floor(<{echo microtime(true)*1000 }>);
    var newYear=Math.floor(<{$newYear*1000}>);
    var _diff=newYear-serverTime;
    return function(){
    	   var diff=_diff
	   var days=Math.floor(diff/86400000);
	   diff=diff%86400000;	   
	   var hour=Math.floor(diff/3600000);
	   diff=diff%3600000;
	   var minute=Math.floor(diff/60000);
	   diff=diff%60000;
	   var sec=Math.floor(diff/1000);
	   var timeString=[days,"日",hour,"時",minute,"分",sec,"秒"].join("");
	   countArea.html(timeString);
    	   _diff=_diff-1000;
    }
}();
Timer();
countTime();
setInterval("Timer()",1000);
setInterval("countTime()",1000);
<{/block}>