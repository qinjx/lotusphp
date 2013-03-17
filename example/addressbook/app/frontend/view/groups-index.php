<div class="area">
  <form action="{url('Groups', 'DoAdd')}" method="post"  enctype="multipart/form-data" name="theform" id="theform" onsubmit="return validate(this)">
    <table border="0" cellpadding="0" cellspacing="0" class="listtable">
      <caption>
      添加联系人分组
      </caption>
      <tbody class="stripe">
        <tr>
          <td>联系人分组，名称</td>
          <td><input name="data[groupname]" type="text" size="50" maxlength="50" /></td>
        </tr>
      </tbody>
    </table>
    <div class="button_box">
      <input class="btn" name="dosubmit" type="submit" value="确认添加联系人分组" />
    </div>
  </form>
</div>
<div class="area">
    <table border="0" cellpadding="0" cellspacing="0" class="listtable">
      <caption>
      </caption>
      <tbody class="stripe">
<!--{loop $this->data['groups']['rows'] $k $v}-->
        <tr>
          <td>{$v['groupname']}</td>
          <td><a href="{url('Groups', 'Edit',array('gid'=>$v['gid']))}">编辑</a> | <a href="javascript:confirmurl('{url('Groups', 'Dodelete',array('gid'=>$v['gid']))}','确认删除吗？')">删除</a></td>
        </tr>              
<!--{/loop}-->
      </tbody>
    </table>
</div>