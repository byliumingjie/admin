<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LoadRole_Controller extends Module_Lib{
    public function index() {
        
        $this->load_view("show","");
    }
    
    public function loadrole() {
        
        $platformid = Helper_Lib::getCookie("gzoneid");
        if(empty($platformid))
        {
            //无效id，无法加载数据
            $this->outputJson(-1,"加载失败");
        }
        
        $roleconfigarray = RoleConfig_Service::getRoleServer($platformid);
        if(empty($roleconfigarray))
        {
            $this->outputJson(-1,"加载失败");
        }
        
        $htmlpage = "<thead>
                        <tr>
                            <th>区服</th>
                            <th>角色总数</th>					
                        </tr>
                    </thead>
                    <tbody>";
        for($i = 0; $i < count($roleconfigarray);++$i)
        {
            $perNum = RoleConfig_Service::getRoleNumber($roleconfigarray[$i]);
             $htmlpage .= '<tr id='.$roleconfigarray[$i]['sid'].'>';
             $htmlpage .='<td data-name="sid" style="text-align: center;">'.$roleconfigarray[$i]['sid'].'</td>';
             $htmlpage .='<td data-name="pernumber" style="text-align: center;">'.$perNum["total"].'</td></tr>';
             
            
        }
        $htmlpage .="</tbody>";
        //无数据直接返回
        $this->outputJson(0,$htmlpage);
    }
}