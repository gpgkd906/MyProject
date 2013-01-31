<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><{$title}></title>
<!-- block:header.resource -->
<{block:header.resource}>
  <{* ここは各ページに継承される:最初にロードすべきファイルはここで,etc.css *}>
<{/block}>
</head>
<body>
<{block:body.content}>
  test1111
  <{* ここは各ページに継承される  *}>  
<{/block}>
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<{*
<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
<link type="text/css" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" rel="stylesheet">
*}>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
<link type="text/css" href="http://code.jquery.com/ui/1.9.2/themes/smoothness/jquery-ui.css" rel="stylesheet">
<!-- block:footer.resource -->
<{block:footer.resource}>
 <{* 最後にロードすべきファイルはここで，etc.js *}>
<{/block}>
<script>
<{block:javascript}>
  <{* ユーザjavascriptロジックはここで *}>  
<{/block}>
</script>
</body>
</html>
