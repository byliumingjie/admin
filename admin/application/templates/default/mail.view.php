<?php
 
$insert_html = Page_Lib::loadJs('mail','','tool');

echo Page_Lib::head($insert_html,'',1);
?>
<!-- 站内导航 -->
<div id="content-header">
    <h1>后台基本操作</h1>
        <div class="btn-group">        
    </div>
</div>

 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">新建邮件</a>
 </div>
<div class="container-fluid">					
  <div class="row-fluid">
    <div class="widget-box">
    <div class="widget-title">
	<span class="icon">
            <i class="icon-th"></i>
        </span>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab1">邮件发送</a></li>
                <li><a data-toggle="tab" href="#tab2">群发邮件</a></li>
                <li><a data-toggle="tab" href="#tab3">全服邮件</a></li>
            </ul>
	</div>
        <div class="widget-content tab-content">
	  <!-- 1页表格 正文 -->
            <div id="tab1" class="tab-pane active">
                 <form class="form-horizontal" action="createMail" method="POST" id="savePerMailForm" onsubmit="return false;">        
                    <table class="table  table-striped" > 
                    <tbody>
                        <tr>
                            <td>
                              <div class="control-group">
                                <label class="col-md-3 control-label">*区服</label>
                                <div class="controls">
                                   <input type="text" class="form-control" name="server"  placeholder="区服Id"/>
                                </div>
                            </div>  
                            </td>
                            <td></td>
                            </tr>
                        <tr>
                            <td>
                                <div class="control-group">
                                    <label class="control-label">*角色ID</label>
                                    <div class="controls"><input type="text" 
                                    class="form-control" name="roleid"  placeholder="玩家的帐号ID -1表示全部玩家"/></div>
                                </div>
                            </td>
                            <td></td>
                        </tr> 
                        <tr>
                            <td>
                               <div class="control-group">
                       			 <label class="control-label">*邮件标题</label>
                        		<div class="controls "><input type="text" class="form-control"  maxlength="60"  placeholder="最多输入十个汉字" name="mailtitle"/></div>
                   		 		</div> 
                            </td>
                            <td></td>
                        </tr>
                         
                        <tr>
                            <td>
                              <div class="control-group">
                                    <label class="col-md-3 control-label">*邮件内容</label>
                                    <div class="controls">
                                        <textarea class="form-control " name="context" rows="7" maxlength="800"   placeholder="最多输入800汉字,包括标点符号和空格"></textarea>
                                    </div>
                                </div>  
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="control-group">
                                    <label class="control-label">邮件有效期</label>
                                    <div class="controls">
                                    <input type="text" 
                                    class="form-control" name="delayDeleteDay" placeholder="邮件有效期默认7天"/>&nbsp天</div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        
                            <tr>
                                <td>
                                    <div class="control-group">
                                    <div class="control-group">
                                        <label class="col-md-3 control-label">添加附件: </label>
                                         <div class="controls ">
                                             <span style="color:gray" >  </span>   
                                        </div> 
                                    </div>
                                    </div>
                                </td> 
                                <td></td>
                            </tr>  
                            <tr>
                                <td>
                                    
                                    <div class="control-group">
                                        <label class="checkbox-inline">
                                        <div class="controls ">道具ID1
                                            <input type="checkbox" value="propid1" name="checkbox[]"/>
                                            <input type="text" class="form-control " placeholder="道具的ID" name="propid1" onblur="checkProp(this.value,3)"/>
                                        </div>
                                            </label>
                                    </div>
                                 
                                </td>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">道具数量1</label>
                                        <div class="controls "><input type="text" class="form-control"  maxlength="10" placeholder="不可输入小数" name="propnum1"/></div>
                                    </div>
                                </td>  
                            </tr>
                            <tr>
                                <td>
                                    
                                    <div class="control-group">
                                        <label class="checkbox-inline">
                                        <div class="controls ">道具ID2
                                            <input type="checkbox" value="propid2" name="checkbox[]"/>
                                            <input type="text" class="form-control " placeholder="道具的ID" name="propid2" onblur="checkProp(this.value,3)"/>
                                        </div>  
                                            </label>
                                    </div>
                                 
                                </td>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">道具数量2</label>
                                        <div class="controls "><input type="text" class="form-control"  maxlength="10" placeholder="不可输入小数" name="propnum2"/></div>
                                    </div>
                                </td>  
                            </tr>
                            <tr>
                                <td>
                                    
                                    <div class="control-group">
                                        <label class="checkbox-inline">
                                        <div class="controls ">道具ID3
                                            <input type="checkbox" value="propid3" name="checkbox[]"/>
                                            <input type="text" class="form-control " placeholder="道具的ID" name="propid3" onblur="checkProp(this.value,3)" />
                                        </div> 
                                            </label>
                                    </div>
                                 
                                </td>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">道具数量3</label>
                                        <div class="controls "><input type="text" class="form-control"  maxlength="10" placeholder="不可输入小数" name="propnum3"/></div>
                                    </div>
                                </td>  
                            </tr>
                            <tr>
                                <td>
                                    
                                    <div class="control-group">
                                        <label class="checkbox-inline">
                                        <div class="controls ">道具ID4
                                            <input type="checkbox" value="propid4" name="checkbox[]"/>
                                            <input type="text" class="form-control "  placeholder="道具的ID" name="propid4" onblur="checkProp(this.value,3)"/></div>  
                                    </label>
                                 </div>
                                </td>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">道具数量4</label>
                                        <div class="controls "><input type="text" class="form-control"  maxlength="10"  placeholder="不可输入小数" name="propnum4"/></div>
                                    </div>
                                </td>  
                            </tr>
                            <tr>
                                <td>
                                    
                                    <div class="control-group">
                                        <label class="checkbox-inline">
                                        <div class="controls ">道具ID5
                                            <input type="checkbox" value="propid5" name="checkbox[]"/>
                                            <input type="text" class="form-control "  placeholder="道具的ID" name="propid5" onblur="checkProp(this.value,3)"/></div>  
                                    </label>
                                 </div>
                                </td>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">道具数量5</label>
                                        <div class="controls "><input type="text" class="form-control"  maxlength="10"  placeholder="不可输入小数" name="propnum5"/></div>
                                    </div>
                                </td>  
                            </tr>
                        </tbody>
                    </table>
                     
                     <div style="text-align: center;">	
                        <button class="btn btn-success btn-xs ladda-button" 
						data-style="expand-right" id="savePerBtn" style="margin: auto;">发送</button>
                    </div>
                    
                </form>
                
            </div>
          <!-- 2页表格 正文 -->
   
          <!-- 3页表格 正文 -->
  
        </div>
    </div>
   </div>
</div>
<?php 
    echo Page_Lib::footer(); 
?>
  