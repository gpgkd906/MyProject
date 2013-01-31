<{extends file="common/main.tpl"}>
<{block:body.content}>
<div style="padding:10px;margin:10px">
<img src="<{echo $app->link("img",$article.filename)}>">
</div>
<a href="<{$app->link("upload","download",array("id"=>$article.id))}>"><div id="download" style="overflow:hidden;width:10px;height:10px"><img src="<{$img}>normal.png" style="position:absolute;left:30px"></div></a>
<div style="padding:10px;margin:10px">     
カテゴリ:<{for $cat in $article.category}><{$cat.key}>,<{/for}>
</div>
<div style="padding:10px;margin:10px">     
タグ:<{for $tag in $article.tags}><{$tag.key}>,<{/for}>
</div>
<div style="padding:10px;margin:10px">     
作者:<{$article.author.name}>
</div>
<{/block}>

<{block:javascript}>
	$("#download").hover(function(){
		$(this).find("img").attr("src","<{$img}>hover.png");
	},function(){	
		$(this).find("img").attr("src","<{$img}>normal.png");
	});
	$("#download").mousedown(function(){
		$(this).find("img").attr("src","<{$img}>click.png");
	});
	("#download").mouseup(function(){
		$(this).find("img").attr("src","<{$img}>hover.png");
	});

<{/block}>