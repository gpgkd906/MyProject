<{extends file="common/main.tpl"}>
<{block:body.content}>
<div>
<table border="1">
<tr>
<td>日曜日</td>
<td>月曜日</td>
<td>火曜日</td>
<td>水曜日</td>
<td>木曜日</td>
<td>金曜日</td>
<td>土曜日</td>
</tr>
<{echo $cal->create(function(@@day,@@dow) use (@@test){
	if(@@day===null){
		return "<td></td>";
	}else{
		return "<td>".@@day["date"]."</td>";
	}
})}>
</table>
</div>
<{/block}>
<{block:javascript}>

<{/block}>