<?php
class StringUtil
{
	public function format_size($size)
	{
		if ($size >= 1073741824)
		{
			$size = round($size / 1073741824, 2) . ' GB';
		}
		else if ($size >= 1048576)
		{
			$size = round($size / 1048576, 2) . ' MB';
		}
		else if ($size >= 1024)
		{
			$size = round($size / 1024, 2) . ' KB';
		}
		else
		{
			$size = round($size, 2) . ' Bytes';
		}
		return $size;
	}
}