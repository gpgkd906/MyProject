<{extends file="common/main.tpl"}>
<{block:body.content}>

<form <{$form.meta.attrs|join:''}>>
<{$form.meta.helper}>
<table border="1">	
<tr>
<td>metaid</td><td>relatives</td><td>name</td><td>status</td><td>tables</td><td>del_flg</td>
</tr>
<{for $index in $iterator}>
<tr>
<td><{$form.meta.text["old[{$index}][metaid]"]}></td>
<td><{$form.meta.text["old[{$index}][relatives]"]}></td>
<td><{$form.meta.text["old[{$index}][name]"]}></td>
<td><{$form.meta.text["old[{$index}][status]"]}></td>
<td><{$form.meta.text["old[{$index}][tables]"]}></td>
<td><{$form.meta.checkbox["old[{$index}][del_flg]"]}></td>
</tr>
<{/for}>
<{for $item in $range}>
<tr>
<td><{$form.meta.text["new[{$item}][metaid]"]}></td>
<td><{$form.meta.text["new[{$item}][relatives]"]}></td>
<td><{$form.meta.text["new[{$item}][name]"]}></td>
<td><{$form.meta.text["new[{$item}][status]"]}></td>
<td><{$form.meta.text["new[{$item}][tables]"]}></td>
<td><{$form.meta.checkbox["new[{$item}][del_flg]"]}></td>
</tr>
<{/for}>
</table>
<input type="submit" value="submit">
</form>
<{/block}>