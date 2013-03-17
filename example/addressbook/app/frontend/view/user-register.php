<div class="area">
  <form action="{url('User', 'DoRegister')}" method="post"  enctype="multipart/form-data" name="theform" id="theform">
    <table border="0" cellpadding="0" cellspacing="0" class="listtable">
      <caption>
      用户注册
      </caption>
      <tbody class="stripe">
        <tr width="60">
          <td>用户名</td>
          <td><input name="username" id="username" type="text" size="80" maxlength="100" /></td>
        </tr>
        <tr>
          <td>手机</td>
          <td><input name="mobile" id="mobile" type="text" size="80" maxlength="100" /><span id="mobile_info"></span></td>
        </tr>
        <tr>
          <td>邮箱</td>
          <td><input name="email" type="text" size="80" maxlength="100" /></td>
        </tr>
        <tr>
          <td>密码</td>
          <td><input name="password" type="text" size="80" maxlength="100" /></td>
        </tr>
        <tr>
          <td>确认密码</td>
          <td><input name="repassword" type="text" size="50" maxlength="20" /></td>
        </tr>
      </tbody>
    </table>
    <div class="button_box">
      <input class="btn" name="dosubmit" type="submit" value="提交" />
    </div>
  </form>
</div>

<script type="text/javascript">

$("#mobile").focus(function(){
	$("#mobile_info").html("");
	return false;
});

$("#mobile").blur(function(){
	var mobile = $("#mobile").val();
	if(mobile == "") { 
		$("#mobile_info").html("请输入手机号");
		return false;
	}
	if(isNaN(mobile)) {
		$("#mobile_info").html("手机号只能是数字");
		return false;
	}

	$("#mobile_info").html("<img src='{$this->data['baseurl']}images/loading.gif' /> 正在检测是否已经注册，请稍候...");

	var url = "{url('User','Check', array('mobile'=>''))}" + mobile;
	$.getJSON(url,function(data){
		$("#mobile_info").html(data.message);
	});
});

</script>