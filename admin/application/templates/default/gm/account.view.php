<?php 
$insert_html.= Page_Lib::loadJs('account','','tool');
$insert_html.= Page_Lib::loadJs('server.list');
$insert_html.= Page_Lib::loadJs('statdata');
echo Page_Lib::head($insert_html);
?>
<!-- 站内导航 BEGIN-->
<div id="content-header">
        <h1>玩家信息查询     <?php if (isset($response) && !empty($response)){echo $response;}?></h1> 
        <div class="btn-group">               
        </div>
</div>	
<!-- top start -->
 <div id="breadcrumb">
    <a href="/index/index" title="跳到首页" class="tip-bottom"><i class="icon-home"></i> 首页</a>
    <a href="#" class="current">玩家信息查询</a> 
    <a href="#" title="角色基本信息&nbsp(支持在线玩家信息查询,玩家离线可更改信息,直接变更玩家信息一般是不赞成
    的，以为会影响很多，另外信息要以递增发生最好不小于当前的信息数据"
    data-placement="bottom" data-trigger="focus"  
    class="tip-bottom"><i class="icon-question-sign"></i></a>
 </div>
<div class="widget-content">
<?php echo   DevToolbox_Lib::accountHtml();?>
 
<div class="widget-content">
<button class="btn btn-primary " id="LevelPropbtn">信息变更</button>
<button class="btn btn-primary " id="coseGuide">关闭引导</button>
</div> 
 		<?php  
 				// true 在线 false 离线		 
 				 $ifOnline = isset($roleInfo['data']['memory_info']['is_on_enter_game'])?1:0;
 				 
                 $playerName = $roleInfo['data']['db_card_info']['show_info']['nick_name'];
                 $onlineState = 0;
                 $playerId = $roleInfo['data']['db_card_info']['show_info']['player_id'];
                 $createTime = $roleInfo['data']['login_time_info']['create_time'];
                 $gold = $roleInfo['data']['game_coin'];
                 $diamond = $roleInfo['data']['diamond_coin'];
                 $money = $roleInfo['data']['recharge_money']['total_money'];
                 $recharge_money = (!isset($money) && empty($money))?0:$money; 
                 $lastOfflineTime = $roleInfo['data']['login_time_info']['last_offline_time'];
                 $currentOnlineTime = $roleInfo['data']['login_time_info']['current_login_time'];
                 $level = $roleInfo['data']['db_card_info']['show_info']['level'];
                 $pill = $roleInfo['data']['endurance'];
                 $sugar = $roleInfo['data']['diamond_coin'];
                 $vip = $roleInfo['data']['db_card_info']['show_info']['vip_lvl'];
                 $ArenaRanking = $roleInfo['data']['arena_info']['history_top_rank']; // 竞技场排名arena_info
                 $history_top_rank = (!isset($ArenaRanking) && empty($ArenaRanking))?0:$ArenaRanking;
                 $BattleValue = $roleInfo['data']['db_card_info']['player_info']['fight']; // 战斗值
                 $RoleAccount = $roleInfo['data']['account'];
                ?>
                <table class="table table-striped table-bordered table-hover">
                    
                    <h4>角色基本信息&nbsp(支持在线玩家信息查询,玩家离线可更改信息)
                    <?php echo isset($RoleAccount)?'账号:'.$RoleAccount:NULL;?></h4>                  
                    <tr>
                        <td class="success" >角色名</td>
                        
                        <?php
                        $onlineName = "";
                        if (count($roleInfo)>0 && !empty($roleInfo))
                        {
                        $onlineName = ($ifOnline==1)?
                        "<font style='color: #48a260;'>(在线)</font>"
						:"<font style='color: #99a09b;'>(离线)</font>";
                        }
                        echo"<td class='success' id='userName'>".$playerName."&nbsp;".$onlineName;
                         
                        ?> 
                        </td> 
                        <td class="success">最近登录时间</td>
                        <td class="success"><?php 
                        //$Inseconds = intval($lastOfflineTime / 1000);
                        $lastTime = date("Y-m-d H:i:s",$currentOnlineTime);
                        $currentOnlineTime = date("Y-m-d H:i:s",$lastOfflineTime);
                        echo  $lastTime ; 
                        ?></td>
                        <td style="display: none" id="server"><?php echo $server?></td>
                        <td style="display: none" id="Roleaccount"><?php echo $RoleAccount?></td>
                    </tr> 
                    <tr>
                        <td class="success" >角色ID</td>
                        <td class="success" id="userid" ><?php echo $playerId;?></td>                         
                        <td class="success">创建时间</td>
                        <td>
                        <?php
                        //$seconds = intval($createTime / 1000);
                        $times = date("Y-m-d H:i:s",$createTime);
                        echo $times;
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="success">角色等级</td>
                        <td><?php  echo $level;?></td>
                        <td class="success">药丸数量</td>                         
                        <td> 
                        	<a id="pill" >
                        	 <i title='更改药丸数量'>
                        	 <b class='promptGraph'><?php  echo $pill;?></b></i>
                        	</a> 
                        </td>                         
                    </tr>
                    <tr>
                        <td class="success" >战斗力</td>
                        <td class="success" id="userid" ><?php echo $BattleValue;?></td>                         
                        <td class="success">竞技场排名</td>
                        <td class="success"><?php echo $history_top_rank;?></td>
                         
                    </tr>
                  
                   <tr>
                       <td class="success">总充值数量</td>		
                       <td><?php  echo $recharge_money;?></td>
                       <td class="success">金币数量</td>
                       <td> 
	                        <a id="gold" title='更改金币数量' >
	                        	<b class='promptGraph'><?php  echo $gold;?></b>
	                        </a>
                       </td>
                    </tr>
                    <tr>
                       <td class="success">棒棒糖数量</td>		
                       <td>
                        <a id="sugar"   title='更改棒棒糖数量' >
	                           	<b class='promptGraph'><?php  echo $sugar;?></b>
	                        </a>
                       </td> 
                       <td class="success">vip等级</td>
                       <td> 
	                        <a id="vip"   title='更改vip等级'>
	                           	<b class='promptGraph'><?php  echo $vip;?></b>
	                        </a>
                       </td>
                    </tr>
                     
                </table>                
                <?php ///endif;?>
    <!-- /////////////////////|TAB页表格 正文  Begin|/////////////////////////////////// -->
    <!-- /////////////////////|TAB页表格 正文  Begin|/////////////////////////////////// -->      
        <div class="widget-box">
            <div class="widget-title">
            <span class="icon">
                <i class="icon-th"></i>
                </span>
                <ul class="nav nav-tabs">
                    <li class="active">
                    <a data-toggle="tab" href="#tab1">表情包列表</a></li>
                    <li ><a data-toggle="tab" href="#tab2">属性列表</a></li>
                    <li ><a data-toggle="tab" href="#tab3">角色当前穿戴装备列表</a></li>
                    <li ><a data-toggle="tab" href="#tab9">背包装备列表</a></li>
                    <li ><a data-toggle="tab" href="#tab4">道具列表</a></li>
                    <li ><a data-toggle="tab" href="#tab5">材料列表</a></li>
                    <li ><a data-toggle="tab" href="#tab6">好友列表</a></li>
                    <li ><a data-toggle="tab" href="#tab7">上阵技能列表</a></li>
                    <li ><a data-toggle="tab" href="#tab10">已学习技能列表</a></li>
                    <li ><a data-toggle="tab" href="#tab8">章节记录</a></li>                    
                </ul>
            </div>
        </div>
    <div class="widget-content tab-content">
    <!-- tab 1 表情包列表  -->
    <div id="tab1" class="tab-pane active">
        <table class="table table-bordered table-striped">                 
        <?php 
        
            //目前表情包
        	$current_face_type = $roleInfo['data']['face_info']['current_face']['face_duration_type'];
        	// 所拥有的表情列表
        	$face_list = $roleInfo['data']['face_info']['have_faces'];
        	//var_dump($face_list);
        	
        	if ($current_face_type==-1)
        	{
        		$current_face_stat = '永久';
        		if(isset($faceConfig))
        		{        			
        			foreach ($faceConfig as $var){
        				if ($roleInfo['data']['face_info']['current_face']['face_id']==$var['id']){
        					
        					$current_face_name = $var['name'];
        				}
        			}
        		}
        		$current_face = '';
        	}
                //if (is_array($pet)): ?>        
                <thead>
                    <tr class="success">
                        <th>名称</th>
                        <th>剩余时间</th>                      
                    </tr>
                </thead>
                <tbody>                
                <?php //$petcfg = Config::get("key.pet.xml");?>                
                    <?php //foreach ($pet['result'] as $inpet): ?>                     	                    
                        <tr class="guard" style="cursor:pointer">
                           <td style=" text-align: center;"><?php echo $current_face_name."(当前)";//$petcfg[$inpet['petId']]['name'];?></td>
                           <td style=" text-align: center;"><?php echo $current_face_stat;;//$inpet['petLevel']?></td>                                                   
                        </tr>
                        <!-- 所拥有的表情-->
                        
                        <?php if (isset($face_list) && !empty($face_list)):?>
                        
                        <?php foreach ($face_list as $Infacvar):?> 
                        <tr>
                        	<td style=" text-align: center;"><?php 
                        	foreach ($faceConfig as $varcfg ){ 
                        		if ($Infacvar['face_id'] == $varcfg['id']){
                        			echo $varcfg['name']; 
                        		}
                        	}
                        	?></td>
                        	<td style=" text-align: center;"><?php 
                        	if ($Infacvar['face_duration_type'] ==-1){                        		
                        		echo "永久";
                        	}else{
                        	echo date('Y-m-d H:i:s',$Infacvar['face_unixtime_time_out_stamp']);                        	
							}?></td>
                        </tr>
                        <?php endforeach;?>
                        <?php endif;?>
                    <?php //endforeach;?>                    
                </tbody>
            <?php //endif; ?>
        </table>
         <?php //if( (empty($pet['result']))):?>
    	<!-- <span>没有查询到信息</span> -->
    	<?php  //endif; ?>
    </div> 
    <!-- 2页表格 正文 属性列表-->
           <div id="tab2" class="tab-pane">
               <table class="table table-bordered table-striped">   
            <?php 
            $player_info = $roleInfo['data']['db_card_info']['player_info'];
            
            $fight = $player_info['fight']; // 战斗值
            $hp = $player_info['hp']; 	    // 生命值
            $attack = $player_info['atk']; 	// 攻击力
            $Block = $player_info['block'];	// 格挡
            $Crit = $player_info['crit'];	// 暴击
            $defense = $player_info['def']; // 防御def
            $speed = $player_info['speed'];	// 速度 
            ?>      
            <?php //if (is_array($hero) && !empty($hero['result'])): ?>
                        <thead>
                                <tr class="success">
                                    <td>生命力</td>
									<td>攻击</td>
									<td>防御</td>
									<td>速度</td>
									<td>格挡</td>
									<td>暴击</td>
									<td>战斗值</td>  
                                </tr>
                            </thead>
                            <tbody>
                             <?php //foreach ($hero['result'] as $inhero): ?> 
                            <tr class="heroAll" style="cursor:pointer">
                                <td style=" text-align: center;">
                                    <?php echo $hp;?>
                                </td>
                                 <td style=" text-align: center;">
                                    <?php echo $attack;?>
                                </td>
                                 <td style=" text-align: center;">
                                    <?php echo $defense;?>
                                </td>  
                                <td><?php echo $speed;?></td>
                                <td><?php echo $Block;?></td>
                                <td><?php echo $Crit;?></td> 
                                <td><?php echo $fight;?></td>
                            </tr>
                            <?php //endforeach; ?>
                    <?php  //endif; ?>
                    </tbody>        
        </table>
    </div>

    <!-- 3页表格 正文  角色当前穿戴装备列表-->
    <div id="tab3" class="tab-pane">
        <table class="table table-striped table-bordered table-hover"> 
            <?php 
            $role_equip = $roleInfo['data']['equip_info']['role_equip']; 
            
            ?>
            
                        <thead>
                           <tr class="success">
                           	   <th>Id</th>
                               <th>名称</th>                                   
                               <th>等级</th>                    
                           </tr>
                        </thead>
                         <tbody>
                         <?php foreach ($equipConfig as  $invar):?>
                         <?php foreach ($role_equip as $inrolevar):?>
                         <?php 
                         if ($invar['id'] == $inrolevar['equip_id']){
                         	$equip_id = $inrolevar['equip_id'];
                         	$name = $invar['name']; 
                          	$level = $inrolevar['level'];
                         ?>
                            <tr>
                               <td style=" text-align: center;"><?php echo $equip_id ;?></td>
                               <td><?php echo $name;?></td>
                               <td><?php echo $level;?></td>
                            </tr> 
                            <?php }?>
                            <?php endforeach;?>
                        <?php endforeach; ?>
                        </tbody> 
        </table>
    </div>    
    <!-- 背包装备信息 -->
        <div id="tab9" class="tab-pane">
        <table class="table table-striped table-bordered table-hover"> 
            <?php  
            $bag_equip = $roleInfo['data']['bag_equip_info']['bag_equip'];
            ?>
            
                        <thead>
                           <tr class="success">
                           	   <th>Id</th>
                               <th>名称</th>                                                      
                           </tr>
                        </thead>
                         <tbody>
                         <?php foreach ($equipConfig as  $invar):?>
                         <?php foreach ($bag_equip as $inrolevar):?>
                         <?php 
                         if ($invar['id'] == $inrolevar['equip_id']){
                         	$equip_id = $inrolevar['equip_id'];
                         	$name = $invar['name']; 
                          	$level = $inrolevar['level'];
                         ?>
                            <tr>
                               <td style=" text-align: center;"><?php echo $equip_id ;?></td>
                               <td><?php echo $name;?></td>
                            </tr> 
                            <?php }?>
                            <?php endforeach;?>
                        <?php endforeach; ?>
                        </tbody> 
        </table>
    </div>  
    <!-- 4页表格 正文 道具列表-->
           <div id="tab4" class="tab-pane">
               <table class="table table-bordered ">              
            <?php  
            $bag_item_info = $roleInfo['data']['bag_item_info']['bag_item'];
            //var_dump($itemConfig);
            ?>
                        <thead>
                                <tr class="success">
                                    <th>道具Id</th>
                                    <th>道具名称</th>
                                    <th>数量</th>                                                                        
                                </tr>
                        </thead>
                        <tbody >
                        <?php foreach ($bag_item_info as  $invar):?>
                        <?php foreach ($itemConfig as $itemvar):?>
                        <?php 
                        if ($itemvar['id']>=20001 && $itemvar['id']<=24999){
                        if ($invar['item_id'] == $itemvar['id']){
                        	
                         $Itemid =$invar['item_id'];
                         $name = $itemvar['name'];
                         $item_count = $invar['item_count'];
                        ?>
                            <tr class="Equip" style="cursor: pointer">
                                <td style=" text-align: center;"><?php echo $Itemid; ?></td>
                                <td style=" text-align: center;"><?php echo $name; ?></td>
                                <td style=" text-align: center;"><?php echo $item_count; ?></td>                               
                            </tr>
                            <?php }}?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php //endif; ?>  
                    </tbody>         
            </table>
        </div>

    <!-- 5页表格 正文 材料列表-->
           <div id="tab5" class="tab-pane">
               <table class="table table-bordered table-striped"> 
            
            
                        <thead>
                                <tr class="success">
                                    <th>id</th>
                                    <th>名称</th>
                                    <th>数量</th>
                                </tr>
                        </thead> 
                        <tbody >
                        <?php foreach ($bag_item_info as  $invar):?>
                        <?php foreach ($itemConfig as $itemvar):?>
                        <?php 
                        if ($itemvar['id']>=25000 && $itemvar['id']<=29999){
                        if ($invar['item_id'] == $itemvar['id']){
                        	
                         $Itemid =$invar['item_id'];
                         $name = $itemvar['name'];
                         $item_count = $invar['item_count'];
                        ?>
                            <tr class="Equip" style="cursor: pointer">
                                <td style=" text-align: center;"><?php echo $Itemid; ?></td>
                                <td style=" text-align: center;"><?php echo $name; ?></td>
                                <td style=" text-align: center;"><?php echo $item_count; ?></td>                               
                            </tr>
                            <?php }}?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php //endif; ?>  
                    </tbody>         
                 <?php //endif; ?>
            </table>
    </div>

    <!-- 6页表格 好友列表 -->
           <div id="tab6"class="tab-pane">
           	<?php 
           	$friend_info = $roleInfo['data']['friend_info']['friends'];
           	?>
               <table class="table table-bordered table-striped">   
                        <thead>
                         <tr class="success">.
                          	 <th>角色ID</th>
                             <th>名称</th>
                             <th>等级</th>
                         </tr>
                        </thead>
                        <?php foreach ($friend_info as $invar):?>
                        <?php 
                        
                        $roleId = $invar['card_info']['role_index'];
                        $nick_name = $invar['card_info']['show_info']['nick_name'];
                        $rolelevel = $invar['card_info']['show_info']['level'];
                        ?>
                        <tr>
                            <td style=" text-align: center;"><?php echo $roleId;?></td>
                            <td style=" text-align: center;"><?php echo $nick_name;?></td>
                            <td style=" text-align: center;"><?php echo $rolelevel;?></td>
                        </tr>
                        <?php endforeach;?>
                </table>
        </div> 
    <!-- 7页表格 技能列表 -->
           <div id="tab7" class="tab-pane">
           <?php 
           $current_skills= $roleInfo['data']['book_skills']['current_skills'];
           //var_dump($skillConfig);
           ?>
               <table class="table table-bordered table-striped">   
                        <thead>
                         <tr class="success">
                             <th>Id</th>
                             <th>名称</th>
                         </tr>
                        </thead>
                        <?php foreach ($current_skills as $inskillId):?>
                        <?php foreach ($skillConfig as $Inskill):?>
                        <?php if (!empty($inskillId) && $inskillId==$Inskill['id']){
                        $name = $Inskill['name'];
                        $id = $Inskill['id'];
                        ?>
                        <tr>
                            <td style=" text-align: center;"><?php echo $id;?></td>
                            <td style=" text-align: center;"><?php echo $name;?></td>
                        </tr>
                        <?php }?>
                        <?php endforeach;?>
                        <?php endforeach;?>
                </table>
        </div>  
        <!-- 已学习的技能 tab10 -->
        <div id="tab10" class="tab-pane">
           <?php 
           $have_skills= $roleInfo['data']['book_skills']['have_skills'];
           //var_dump($skillConfig);
           ?>
               <table class="table table-bordered table-striped">   
                        <thead>
                         <tr class="success">
                             <th>Id</th>
                             <th>名称</th>
                             <th>等级</th>
                             <th>熟练度</th>
                         </tr>
                        </thead>
                        <?php foreach ($have_skills as $invar):?>
                        <?php foreach ($skillConfig as $Inskill):?>
                        <?php if (!empty($invar) && $invar['id']==$Inskill['id']){
                        $name = $Inskill['name'];
                        $id = $Inskill['id'];
                        $level = $invar['level'];
                        $skilled = $invar['skilled'];
                        ?>
                        <tr>
                            <td style=" text-align: center;"><?php echo $id;?></td>
                            <td style=" text-align: center;"><?php echo $name;?></td>
                            <td style=" text-align: center;"><?php echo $level;?></td>
                            <td style=" text-align: center;"><?php echo $skilled;?></td>
                        </tr>
                        <?php }?>
                        <?php endforeach;?>
                        <?php endforeach;?>
                </table>
        </div>  
      <!-- 8页表格 战斗记录 -->
           <div id="tab8" class="tab-pane">
           <?php 
           $risk_info =  $roleInfo['data']['risk_info']['chaps'];
           ?>
               <table class="table table-bordered table-striped">   
                        <thead>
                         <tr class="success">
                             <th>章节名称</th>
                             <th>章节节点</th>
                         </tr>
                        </thead>
                        <?php foreach ($risk_info as $invar):?>
                        <?php 
                        
                        //var_dump($invar);
                        $chapId = $invar['id']; //章节
                        // dup 关卡
                        
                        $dupOut = $invar['dup'];
                        $dupnmae = null;
                        foreach ($dupOut as $indupvar){
                        	 
                        	$dupnmae .= ($dupnmae==null)
                        	?                        	
                        	$dupConfig[$indupvar['id']] 
                        	: 
                        	' | '.$dupConfig[$indupvar['id']];
                        }
                        $chapName = null;
                        if (!empty($chapId) &&  isset($chapConfig[$chapId])){
                        	$chapName = $chapConfig[$chapId];
                        }
                        ?>
                        <tr>
                            <td style=" text-align: center;"><?php echo $chapName;?></td>
                            <td style=" text-align: center;"><?php echo $dupnmae;?></td>
                        </tr>
                        <?php endforeach;?>
                </table>
        </div> 
<script language="javascript">
    $(function() {
        $("#MyGuardList tr.guard").each(function() {
            $(this).click(function() {
                $(this).next().toggle(500);
            });
        });
        $("#myHero tr.hero").each(function() {
            $(this).click(function() {
                $(this).next().toggle(500);
            });
        });
        
        $("#myHeroAll tr.heroAll").each(function() {
            $(this).click(function() {
                $(this).next().toggle(500);
            });
        });
         $("#myEquip tr.Equip").each(function() {
            $(this).click(function() {
                $(this).next().toggle(500);
            });
        });
         $("#myProp tr.Prop").each(function() {
            $(this).click(function() {
                $(this).next().toggle(500);
            });
        });
         $("#myMatrial tr.Matrial").each(function() {
            $(this).click(function() {
                $(this).next().toggle(500);
            });
        });       
    });
</script> 

<!-- gold Edit-->   
<div class="modal fade" id="goldMode" tabindex="-1" role="dialog" aria-labelledby="goldMode" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addServerModalLabel">金币更改</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="editAccountGoldForm" onsubmit="return false;">
                	<input type="hidden" name="status" value='1'>
                	<input type="hidden" name="server">
                 	<div class="control-group">
                        <label class="col-md-3 control-label">角色ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="userid" readonly="true" /></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-3 control-label">角色名：</label>
                        <div class="controls"><input type="text" class="form-control" name="name" readonly="true" /></div>
                    </div> 
                    <div class="controls">
                        	增加金币&nbsp&nbsp<input style="margin: 0px"name="goldType" type="radio" value="3001" />
                        	减少金币&nbsp&nbsp<input style="margin: 0px"name="goldType" type="radio" value="3003" />
                     </div>
                    <div class="control-group">
                        <label class="col-md-3 control-label">金币数量：</label>
                        <div class="controls"><input type="text" class="form-control" name="amount"/></div>
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="goldAccountBtn">确认更改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
 <!-- sugar edit begin -->
 <div class="modal fade" id="sugarMode" tabindex="-1"  aria-labelledby="sugarMode" aria-hidden="true">
  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addServerModalLabel">棒棒糖变更</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="editsugarForm" onsubmit="return false;">
                	 <input type="hidden" name="status" value='2'>
                	 <input type="hidden" name="server">
                	 <div class="control-group">
                        <label class="col-md-3 control-label">角色ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="userid" readonly="true" /></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-3 control-label">角色名：</label>
                        <div class="controls"><input type="text" class="form-control" name="name" readonly="true" /></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-3 control-label"> </label>
                        <div class="controls">
                        	增加棒棒糖&nbsp&nbsp<input style="margin: 0px"name="sugarType" type="radio" value="3002" />
                        	减少棒棒糖&nbsp&nbsp<input style="margin: 0px"name="sugarType" type="radio" value="3004" />
                        </div>                        
                    </div>                     
                    <div class="control-group">
                        <label class="col-md-3 control-label">棒棒糖数量：</label>
                        <div class="controls"><input type="text" class="form-control" name="amount"/></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="sugarBtn">确认更改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- pill Edit-->      
<div class="modal fade" id="pillMode" tabindex="-1" role="dialog" aria-labelledby="pillMode" aria-hidden="true">
  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addServerModalLabel">药丸变更</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="editpillForm" onsubmit="return false;">
                	  
                	 <input type="hidden" name="status" value='3'>
                	 <input type="hidden" name="server">
                	 <div class="control-group">
                        <label class="col-md-3 control-label">角色ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="userid" readonly="true" /></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-3 control-label">角色名：</label>
                        <div class="controls"><input type="text" class="form-control" name="name" readonly="true" /></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-3 control-label"> </label>
                        <div class="controls">
                        	增加药丸&nbsp&nbsp<input style="margin: 0px"name="pillType" type="radio" value="3002" />
                        	减少药丸&nbsp&nbsp<input style="margin: 0px"name="pillType" type="radio" value="3004" />
                        </div>                        
                    </div>                     
                    <div class="control-group">
                        <label class="col-md-3 control-label">药丸数量：</label>
                        <div class="controls"><input type="text" class="form-control" name="amount"/></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="pillBtn">确认更改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>

 <!-- vip edit begin -->
 <div class="modal fade" id="vipMode" tabindex="-1"  aria-labelledby="vipMode" aria-hidden="true">
  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addServerModalLabel">vip变更</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="editvipForm" onsubmit="return false;">
                	 <input type="hidden" name="status" value='4'>
                	 <input type="hidden" name="server">
                	 <div class="control-group">
                        <label class="col-md-3 control-label">角色ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="userid" readonly="true" /></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-3 control-label">角色名：</label>
                        <div class="controls"><input type="text" class="form-control" name="name" readonly="true" /></div>
                    </div> 
                    <div class="control-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="controls">
                        	增加vip等级&nbsp&nbsp<input style="margin: 0px"name="vipType" type="radio" value="3002" />
                        	减少vip等级&nbsp&nbsp<input style="margin: 0px"name="vipType" type="radio" value="3004" />
                        </div>                        
                    </div>                     
                    <div class="control-group">
                        <label class="col-md-3 control-label">vip等级：</label>
                        <div class="controls"><input type="text" class="form-control" name="amount"/></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="vipBtn">确认更改</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消关闭</button>
            </div>
        </div>
    </div>
</div>
<!-- item upadte add Propmodal Begin -->
<div class="modal fade" id="addPropmodal" tabindex="-1" role="dialog" aria-labelledby="addPropmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addPropmodalLabel">道具/装备/材料(添加/扣除)</h4>
                
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="PropsForm" onsubmit="return false;">                      
                	<input type="hidden" name="server">  
                	
                	<div class="control-group">
                        <label class="col-md-3 control-label">角色ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="userid" readonly="true" /></div>
                    </div>                        
	                <div class="control-group">
                        <label class="col-md-3 control-label">道具ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="propsid"  /></div>
	                </div>                		      
	                <div class="control-group">
                        <label class="col-md-3 control-label"> </label>
                        <div class="controls">
                         	增加道具&nbsp&nbsp<input style="margin: 0px"name="propsType" type="radio" value="2001" />
                        	扣除道具&nbsp&nbsp<input style="margin: 0px"name="propsType" type="radio" value="2002" />
                        </div>
	                </div>
                
                <div class="control-group">
                        <label class="col-md-3 control-label">数量：</label>
                        <div class="controls"><input type="text" class="form-control" name="propsNum"  /></div>
                </div>
                    <input type="hidden" name="id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="PropsBtn">确认</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
</div>
<!-- 等级变更 -->
<div class="modal fade" id="levelPropmodal" tabindex="-1" role="dialog" aria-labelledby="levelPropmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addPropmodalLabel">信息变更</h4>
                
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="LevelPropsForm" onsubmit="return false;">
                	<input type="hidden" name='ifonline' id='ifonline' value='<?php echo $ifOnline;?>'/>                      
                	<!-- <input type="hidden" name="ServerId"> --> 
                	<div class="control-group">
                        <label class="col-md-3 control-label">区服：</label>
                        <div class="controls"><input type="text" class="form-control" name="ServerId" /></div>
                    </div>  
                	<div class="control-group">
                        <label class="col-md-3 control-label">账号：</label>
                        <div class="controls"><input type="text" class="form-control" name="RoleAccount" /></div>
                    </div>
                	<div class="control-group">
                        <label class="col-md-3 control-label">角色ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="PlayerId"  /></div>
                    </div>                        
	                <div class="control-group">
                        <label class="col-md-3 control-label">等级：</label>
                        <div class="controls"><input type="text" class="form-control" name="uplevel" /></div>
	                </div>                		      
	                <div class="control-group">
                        <label class="col-md-3 control-label">体力：</label>
                        <div class="controls"><input type="text" class="form-control" name="strength" /></div>
	                </div> 
	                <div class="control-group">
                        <label class="col-md-3 control-label">金币：</label>
                        <div class="controls"><input type="text" class="form-control" name="gold" /></div>
	                </div> 
	                <div class="control-group">
                        <label class="col-md-3 control-label">棒棒糖：</label>
                        <div class="controls"><input type="text" class="form-control" name="sugar" /></div>
	                </div> 
                    <input type="hidden" name="id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="levelPropsBtn">确认</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
</div>
<!-- 体力变更 -->
<div class="modal fade" id=BodyStrengthmodal tabindex="-1" role="dialog" aria-labelledby="BodyStrengthmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addPropmodalLabel">体力变更</h4>
                
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="BodyStrengthForm" onsubmit="return false;">                      
                	<input type="hidden" name="ServerId">  
                	<div class="control-group">
                        <label class="col-md-3 control-label">账号：</label>
                        <div class="controls"><input type="text" class="form-control" name="RoleAccount" /></div>
                    </div> 
                    
                	<div class="control-group">
                        <label class="col-md-3 control-label">角色ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="PlayerId"  /></div>
                    </div>                        
	                <div class="control-group">
                        <label class="col-md-3 control-label">体力：</label>
                        <div class="controls"><input type="text" class="form-control" name="strength" /></div>
	                </div>                		      
	                 
                    <input type="hidden" name="id"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="BodyStrengthBtn">确认</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
</div>
<!-- add notice Begin -->
<div class="modal fade" id="addnoticemodal" tabindex="-1" role="dialog" aria-labelledby="addnoticemodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="addPropmodalLabel">公告发布</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="#" method="POST" id="noticeForm" onsubmit="return false;">                      
                	<input type="hidden" name="server"> 
                	<div class="control-group">
                        <label class="col-md-3 control-label">角色ID：</label>
                        <div class="controls"><input type="text" class="form-control" name="userid" readonly="true" /></div>
                    </div>  
                                     
	                <div class="control-group">
                        <label class="col-md-3 control-label">公告内容：</label>
                        <div class="controls">
                        	<textarea  name="message"></textarea>
                        </div>                  
	                </div> 
	                <div class="control-group">
                        <label class="col-md-3 control-label">公告循环次数：</label>
                        <div class="controls"><input type="text" class="form-control" name="loopTimes"  /></div>
	                </div>   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="noticeBtn">确认</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
  </div>
</div>
<!--upload end -->

<!-- 版权info BEGIN -->
<?php echo Page_Lib::footer('',true);?>
<!-- 版权info END -->

