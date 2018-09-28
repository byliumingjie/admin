<?php 
//$insert_html = Page_Lib::loadJs('multselect');
$insert_html=Page_Lib::loadJs('ajaxupload');
$insert_html.=Page_Lib::loadJs('cdk');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>新增礼包</h1> 
        <div class="btn-group"> 
          <a class="btn btn-large tip-bottom" title="批量新增cdk" 
          data-toggle="modal"data-backdrop="static" 
          data-target="#addMenuModal" id="addName">
          <i class="icon-plus"></i>批量新增</a>         
        </div>
</div>
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">新增礼包</a>
    <a href="#" id="signalNavigation" data-placement="bottom" data-trigger="focus"> 
    	<?php echo Page_Lib::getplatformInfo($_COOKIE['gzoneid'],$_SESSION['platformInfo']);?>	
     	<i class="icon-question"></i></a>
 </div>
<div class="widget-content">
<?php echo   DevToolbox_Lib::cdkToolboxHtml();?>
 
<?php if (!empty($object)):?>
<form action="setPackage" method="post" id="package">
<table id="tableExcel" class="table table-bordered table-striped" >
<thead >
    <tr> 
    	<th>选项&nbsp<input name='checkboxid' id="checkAll" type='checkbox' style="margin:0px">
    	<button type="button" style="padding:0px" class="btn btn-success" id="packageBtn">确认录入</button>
    	</th>
        <th>礼包名称</th>
        <th>备注</th> 
        <?php if ($numclos):?> 
        <?php for ($i=1;$i<=($numclos-2);$i++):?>
        <th>道具<?php echo $i?></th>
        <?php endfor;?>
        <?php endif;?> 
    </tr> 
    </thead>
    <tbody id="list">   
		<?php 
		 $arrout = explode("#",$object);
		 $i = 1;
		 $inname = '';
		 $giftout = array();
		 foreach($arrout as $instr){
		 		$dat = '';
		 		$inname = '';
		 		$inout = explode(",",$instr);
		 		echo "<tr><td><input name='checkcdk[{$i}]' id='checkboxid' type='checkbox' value={$i}></td>";
		 		$j = 1;
		 		foreach($inout as $var){		 			
		 			//$giftOut[] =$j===1? $var:;
		 			if($j===1){
		 				$inname=$var;
		 			}
		 			$dat .= "'".$var."',";
		 			echo "<td>".$var."</td>";
		 			$j++;
		 		}
		 		echo "</tr>";
		 		echo $inname."<br>";
		 		$giftout[$i] =$inname;
				$daout[$i] = substr($dat,0,-1);
		 		$i++; 
		 		
		 }
		 $giftjson = json_encode($giftout);
		 
		 $chagedat = json_encode($daout);
		 
		// echo $chagedat; 
		  
		?>
    </tbody>
    </table>
    <input type="hidden" name="giftname" value=<?php echo $giftjson?>>
    <input type="hidden" name="cheagedata" value=<?php echo $chagedat?>>
</form>
    <?php endif;?>
</div>
<!--  -->
<!--upload start-->      
<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addFileModalLabel" aria-hidden="true">
<!--<div class="modal " id="addMenuModal" style='display:none' tabindex="-1" role="dialog" aria-labelledby="addFileModalLabel" aria-hidden="true">
    --><div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addFileModalLabel"></h4>
            </div>
           <div class="modal-body">
                <div class="control-group">
                    <label class="col-md-3 control-label">
                    <input type="text" readonly="readonly" value="" id="state" style="margin:0px"/>                    
                    <a class="btn" id="selector">
                    <i class="icon-folder-open"></i></a></label>                    
                    <div class="controls" id="listsheet">                     
                    </div> 
                </div>                                
            </div>
            <div class="modal-footer" id="summodel"> 
                <button type="button" class="btn btn-success" id="addFileBtn">确认上传</button>
                <button type="button" class="btn btn-default" id="cancelBtn" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!--upload end -->


<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer(); ?>
<!-- 版权info END -->

