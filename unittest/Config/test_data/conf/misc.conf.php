<?php
$config["misc.test_array"] = array(
	"test_array_key_1" => "test_array_value_1",
);
$config["misc.test_array"]["test_array_key_2"] = "test_array_value_2";
$config["misc.now"] = new LtConfigExpression('time()');