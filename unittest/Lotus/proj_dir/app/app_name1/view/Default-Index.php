<?php if (!LOTUS_UNITTEST_DEBUG) return;?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome LotusPHP</title>
<meta name="generator" content="lotusphp" />
</head>
<body>
<h1>{$this->message}</h1>
<pre>
LotusPHP Works!
code: {$this->code}{CR}{LF}
username: {$this->data[username]}{CR}{LF}
</pre>
</body>
</html>