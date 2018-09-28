/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function() {
  $("#liOption").multiselect2side({
	    selectedPosition: 'right',
	    moveOptions: false,
		labelsx: '待选区',
		labeldx: '已选区',
		platId: 'liOption'
   });
   $("#liEditOption").multiselect2side({ 
	    selectedPosition: 'right',
	    moveOptions: false,
		labelsx: '待选区',
		labeldx: '已选区',
		platId: 'liEditOption'
  });
    
   $("#liEditPlatOption").multiselect2side({
	    selectedPosition: 'right',
	    moveOptions: false,
		labelsx: '待选区',
		labeldx: '已选区',
		platId: 'liEditPlatOption'
 }); 
});
