<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->_vars['title'] ?></title>
<!-- block:header.resource -->

  

</head>
<body>

<div id="table">
       <?php foreach(range(1,20,1) as $this->_vars['range']){ ?>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div class="grid" style="width:30px;height:30px;padding:0px;margin:0px;float:left"></div>
	<div style="width:30px;height:30px;padding:0px;margin:0px" class="disable"></div>
       <?php } ?>
</div>

<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>

<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
<link type="text/css" href="http://code.jquery.com/ui/1.9.2/themes/smoothness/jquery-ui.css" rel="stylesheet">
<!-- block:footer.resource -->

<script src="<?php echo $this->_vars['js'] ?>tetris.js">

<script>


</script>
</body>
</html>