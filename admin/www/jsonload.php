<?PHP
  
$serverlogicOut = array(
	'logic'=>null,
	'login_in'=>null,
	'login_out'=>null,
);
$loginNOte = array(
	array(
		'node'=>null,
	),
	array(
		'node'=>null,
	),
	array(
		'node'=>null,
	),
	array(
		'node'=>null,
	),
);
$centerNote_array = array(
		
);
//  属性数组
$Noteattribute_array = array(
	'center'=>array('addr'=>'127.0.0.1','port'=>5001),
	'logic'=>array('addr'=>'127.0.0.1','port'=>5001),
	'login_in'=>array('addr'=>'127.0.0.1','port'=>5001),
	'login_out'=>array('addr'=>'127.0.0.1','port'=>5001),
);
  
$string ="<?xml version='1.0' encoding='utf-8'?>
<root server_id='01' maxnum='3000'>
</root>";
$xml = simplexml_load_string($string);
 
$center = $xml->addChild('center');
$i=0;
foreach ($loginNOte as $InnoteData)
{ 
	foreach ($InnoteData as $notekey=>$notevar)
	{
		$centerNote = $center->addChild($notekey); 
			//$xml->addChild('&#x0A;');
			foreach ($Noteattribute_array['center'] as $nkey => $nval) {    //  设置属性值
				
				if ($nkey =='port'){$nval +=$i;}
				
				$centerNote->addAttribute($nkey, $nval);
				
			} 
			
	}
	$i++;
}

$server = $xml->addChild('server');

foreach ($serverlogicOut as $key=>$var){
	
	$login = $server->addChild($key);
	$j = 0;
	foreach ($loginNOte as $InnoteData)
	{
		foreach ($InnoteData as $notekey=>$notevar)
		{ 
			$Note = $login->addChild($notekey,null);
			//$xml->addChild('&#x0A;');
			if (isset($Noteattribute_array[$key]) && is_array($Noteattribute_array[$key]))
			{
				
				foreach ($Noteattribute_array[$key] as $nkey => $nval) {    //  设置属性值
					
					if ($nkey =='port'){$nval +=$j;}
					
					$Note->addAttribute($nkey, $nval);
				} 
			}
			
		}
		$j ++ ;
	} 
}
$devOut = array(
	'db'=>array('db_addr'=>'127.0.0.1','db_user'=>'minosdbsa','db_password'=>'123456','db_name'=>'minosdb'),
	'censusdb'=>array('db_addr'=>'172.168.100.83','db_user'=>'minosdbsa','db_password'=>'123456','db_name'=>'minos_census_db'),
	'res'=>array('configrespath'=>"C:\MinosServer\res\\")
);
$db = $xml->addChild('db');
//'&#x0A;'
$censusdb = $xml->addChild('censusdb');
 
$res = $xml->addChild('res');
foreach ($devOut as $devkey=>$devdata){
	
	foreach ($devdata as $Indevkey => $IndevVal)
	{
		${$devkey}->addAttribute($Indevkey, $IndevVal);
	}
}
echo $xml->asXML('test3.xml');






