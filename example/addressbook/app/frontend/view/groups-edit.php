<div class="area">
  <form action="{url('Groups', 'DoEdit')}" method="post"  enctype="multipart/form-data" name="theform" id="theform" onsubmit="return validate(this)">
    <table border="0" cellpadding="0" cellspacing="0" class="listtable">
      <caption>
      编辑联系人分组
      </caption>
      <tbody class="stripe">
        <tr>
          <td>联系人分组，名称</td>
          <td><input value="{$this->data['group']['groupname']}" name="data[groupname]" type="text" size="50" maxlength="50" /></td>
        </tr>
      </tbody>
    </table>
    <div class="button_box">
	<input type="hidden" value="{$this->data['group']['gid']}" name="data[gid]" />
      <input class="btn" name="dosubmit" type="submit" value="提交" />
    </div>
  </form>
</div>
