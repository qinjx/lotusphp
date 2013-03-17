<div class="area">
  <form action="{url('Addressbook', 'DoAdd')}" method="post"  enctype="multipart/form-data" name="theform" id="theform" onsubmit="return validate(this)">
    <table border="0" cellpadding="0" cellspacing="0" class="listtable">
      <caption>
      添加联系人
      </caption>
      <tbody class="stripe">
        <tr>
          <td>分组</td>
          <td><select name="data[gid]">
              <option value="0">默认</option>
              <!--{loop $this->data['groups']['rows'] $k $v}-->
              <option value="{$v['gid']}">{$v['groupname']}</option>
              <!--{/loop}-->
            </select></td>
        </tr>
        <tr>
          <td>姓</td>
          <td><input name="data[firstname]" type="text" size="80" maxlength="100" /></td>
        </tr>
        <tr>
          <td>名</td>
          <td><input name="data[lastname]" type="text" size="80" maxlength="100" /></td>
        </tr>
        <tr>
          <td>公司</td>
          <td><input name="data[company]" type="text" size="80" maxlength="100" /></td>
        </tr>
        <tr>
          <td>地址</td>
          <td><textarea name="data[address]" cols="80" rows="3"></textarea></td>
        </tr>
        <tr>
          <td>手机</td>
          <td><input name="data[mobile]" type="text" size="50" maxlength="20" /></td>
        </tr>
        <tr>
          <td>电话</td>
          <td><input name="data[phone]" type="text" size="50" maxlength="50" /></td>
        </tr>
      </tbody>
    </table>
    <div class="button_box">
      <input class="btn" name="dosubmit" type="submit" value="提交" />
    </div>
  </form>
</div>