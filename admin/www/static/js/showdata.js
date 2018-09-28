/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){

     
    //刷新
   $("#freshbtn").click(function(){
      
        $.ajax({
            type:'POST',
            url:'/loadrole/loadrole',
            data:"",
            dataType:'json',
            success:function(result){
                
                if(result.errcode == 0){
                    
                    $("#showTable").html("");
                    $("#loading").html("");
                    $("#showTable").html(result.msg);
                }else{
                    
                     $("#loading").html("");
                }
                  
            },
            beforeSend:function()
            {
                $("#loading").html("<img src='../img/spinner.gif' />");
            }
        });
    });
});
