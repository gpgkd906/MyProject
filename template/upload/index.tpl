<{extends file="common/main.tpl"}>
<{block:body.content}>
<div style="float:left">
	<{for $art in $article}>
	<div>
		<a href="<{echo $app->link("upload","article",array("id"=>$art.id))}>"><img src="<{$img}><{$art.filename}>"></a>
	</div>
	<hr/>
	<{/for}>
</div>
<div style="float:right">
<div>
カテゴリ:<br/>
<{for $cat in $category}>
      <p><a href="<{echo $app->link("upload","index",array("cate"=>$cat.val))}>"><{$cat.key}></a></p>
<{/for}>
</div>
<hr/>
<div>
タグ:<br/>
<p>
<{$tagCloud->display()}>
</p>
<{*for $tg in $tag}>
      <p><a href="<{echo $app->link("upload","index",array("tag"=>$tg.val))}>"><{$tg.key}></a></p>
<{/for*}>
</div>
<{/block}>