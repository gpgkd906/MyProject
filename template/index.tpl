<{extends file="common/main.tpl"}>
<{block:body.content}>
<{components:warning message=頭の警告です，注意してください}>
<form <{$form.contact.attrs|join:''}>>
<{$form.contact.helper}>
名前:<{$form.contact.text.name}><span style="color:red"><{$form.contact.error.name}></span><br/>
年齢:<{$form.contact.text.age}><span style="color:red"><{$form.contact.error.age}></span><br/>
性別:<{$form.contact.radio.sex}><br/>
カテゴリ:<{$form.contact.select.category}><br/>
画像:<{$form.contact.file.thumnil}><br/>
コメント:<{$form.contact.textarea.comment}><span style="color:red"><{$form.contact.error.comment}></span><br/>
<input type="submit" value="submit">
</div>
</form>
<{components:warning message=足元の警告です，注意してください}>
<{/block}>
