<{extends file="common/main.tpl"}>
<{block:body.content}>

<form <{$form.upload.attrs|join:''}>>
<{$form.upload.helper}>
<p>作品:<{$form.upload.img.uArticle}><{$form.upload.file.article}></p>
<p>カテゴリ(複数不可):<{$form.upload.select.category}><br/>&nbsp;<{$form.upload.text.addcat}></p>
<p>タグ(複数可):<{$form.upload.checkbox['tag[]']}><br/>&nbsp;<{$form.upload.text.addtag}></p>
<p>名前:<{$form.upload.text.name}></p>
<p>ニックネーム:<{$form.upload.text.nick}></p>
<input type="submit" value="submit">
</form>

<{/block}>
