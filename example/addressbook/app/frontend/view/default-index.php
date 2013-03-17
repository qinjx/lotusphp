<div class="area">

<form name="search" method="post" action="{url('Default','Index',array('op'=>'search'))}">
<table class="listtable" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="200" align="center">
<a href="{url('Addressbook','Add')}">新建联系人</a> | <a href="{url('Groups','Index')}">新建联系组</a>
</td>

<td>
<select name="gid">
<option value="-1">所有联系组</option>
<!--{loop $this->data['groups']['rows'] $k $v}-->
<option value="{$v['gid']}">{$v['groupname']}</option>
<!--{/loop}-->
<option value="0">未分组</option>
</select>

<select name='field'>
<option value='name'>姓名</option>
<option value='mobile'>手机</option>
</select>

<input type="text" name="q" value="" size="15" />
<input class="btn" type="submit" name="dosubmit" value=" 查询 " />

</td>
</tr>
</table>
</form>

<form name="myform" method="post" action="">
  <table border="0" cellpadding="0" cellspacing="0" class="listtable">
    <caption>
    通讯录
    </caption>
    <tr>
      <th>选择</th>
      <th>姓名</th>
      <th>组</th>
      <th>手机</th>
      <th>电话</th>
      <th>地址</th>
      <th>更新时间</th>
      <th>管理操作</th>
    </tr>
	<tbody class="stripe">
<!--{loop $this->data['data']['rows'] $data}-->
    <tr>
      <td><input type="checkbox" name="ids[]" value="{$data['id']}" /></td>
      <td>{$data['firstname']} {$data['lastname']}</td>
      <td>{if $data['groupname']}{$data['groupname']}{else}未分组{/if}</td>
      <td>{$data['mobile']}</td>
      <td>{$data['phone']}</td>
      <td>{$data['address']}</td>
      <td>{date('Y-m-d H:i:s', $data['modified'])}</td>
      <td><a href="{url('Addressbook', 'Edit',array('id'=>$data['id']))}">编辑</a> | <a href="javascript:confirmurl('{url('Addressbook', 'Dodelete',array('id'=>$data['id']))}','确认删除吗？')">删除</a></td>
    </tr>
<!--{/loop}-->
	</tbody>
  </table>
  <div class="button_box">对选中项操作</div>
</form>
{$this->data['pages']}
</div>