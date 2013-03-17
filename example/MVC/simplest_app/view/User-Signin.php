<html>
<head>
<title>Lotusphp MVC simplest例子</title>
<meta name="generator" content="lotusphp" />
</head>
<body>

<h1><?php echo $this->code ?> - <?php echo $this->message ?></h1>

<form>
<input type="text" name="username" value="<?php 
if(isset($this->data['username']))
{
	echo $this->data["username"];
}
?>" />
</form>

<pre>
<?php print_r($this->data);?>
</pre>

</body>
</html>