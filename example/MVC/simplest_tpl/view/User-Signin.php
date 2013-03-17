<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>Lotusphp MVC simplest</title>
	<meta name="generator" content="lotusphp" />
	</head>
<body>

<h1>{$this->code} - {$this->message}</h1>

<form>
	<input type="hidden" name="module" value="User" />
	<input type="hidden" name="action" value="Signin" />
	<input type="text" name="username" value="<?php 
	if (isset($this->data['username'])) echo $this->data['username'];?>" />
</form>

<p><a href="simplest_tpl.php">返回 go back</a></p>

<pre>
	<?php print_r($this->data);?>

</pre>

</body>
</html>