<{extends file="common/main.tpl"}>
<{block:body.content}>
<form <{$form.magazineManage.attrs|join:''}>>
<{$form.magazineManage.helper}>
<{for $id in $iterator}>
<hr/>
マガジン名:<{$form.magazineManage.text["old[{$id}][name]"]}><br/>
コンテンツ:<{$form.magazineManage.textarea["old[{$id}][invalue]"]}><br/>
タイトル:<{$form.magazineManage.text["old[{$id}][option][subject]"]}><br/>
Cc:<{$form.magazineManage.text["old[{$id}][option][cc]"]}><br/>
Bcc:<{$form.magazineManage.text["old[{$id}][option][bcc]"]}><br/>
送信元:<{$form.magazineManage.text["old[{$id}][option][from]"]}><br/>
状態：<label><{$form.magazineManage.checkbox["old[{$id}][del_flg]"]}>停止</label><br/>
<{/for}>
<hr/>
マガジン名:<{$form.magazineManage.text["new[0][name]"]}><br/>
コンテンツ:<{$form.magazineManage.textarea["new[0][invalue]"]}><br/>
タイトル:<{$form.magazineManage.text["new[0][option][subject]"]}><br/>
Cc:<{$form.magazineManage.text["new[0][option][cc]"]}><br/>
Bcc:<{$form.magazineManage.text["new[0][option][bcc]"]}><br/>
送信元:<{$form.magazineManage.text["new[0][option][from]"]}><br/>
状態：<label><{$form.magazineManage.checkbox["new[0][del_flg]"]}>停止</label><br/>
<input type="submit" value="submit">
</form>
<{/block}>