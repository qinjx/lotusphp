<?php
/**
 * shortcut
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause New BSD License
 * @version svn:$Id: shortcut.php 964 2012-08-27 04:02:32Z zhao5908@gmail.com $
 * @category runtime
 * @package Lotusphp\Lotus
 */

/**
 * C is LtObjectUtil::singleton
 * @author Jianxiang Qin <TalkativeDoggy@gmail.com>
 * @category runtime
 * @package Lotusphp\Lotus
 * @param string $className
 * @return LtObjectUtil::singleton('className')
 */
function C($className)
{
	return LtObjectUtil::singleton($className);
}
