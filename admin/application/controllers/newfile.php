<?php 
	ini_set('display_errors', '1');
	 
	include_once '../../include/lib/log.php';
	include_once "../../include/lib/mysql.lib.php";
	include_once "core.function.php";
	// 秘钥
	$TokenKey = 'E0369064274BD63E4264534B5AB89B9B';
	
	$dataOutStr = '{"platformID":"1","serverID":"5","roleIndex":"1460000002411","account":"04171123081422332919","fee":"19800","cbi":"4","transID":"com.minosgame.diao198","transReceipt":"MIIT\/gYJKoZIhvcNAQcCoIIT7zCCE sCAQExCzAJBgUrDgMCGgUAMIIDnwYJKoZIhvcNAQcBoIIDkASCA4wxggOIMAoCARQCAQEEAgwAMAsCAQ4CAQEEAwIBazALAgEZAgEBBAMCAQMwDQIBCgIBAQQFFgMxNyswDQIBDQIBAQQFAgMBrbMwDgIBAQIBAQQGAgRMYayLMA4CAQkCAQEEBgIEUDI0OTAOAgELAgEBBAYCBAcOt5YwDgIBEAIBAQQGAgQxIoPYMA8CAQMCAQEEBwwFMS4xLjEwDwIBEwIBAQQHDAUxLjEuMTAQAgEPAgEBBAgCBhet5yeOQTAUAgEAAgEBBAwMClByb2R1Y3Rpb24wGAIBBAIBAgQQijA6vl7olmxMEdnUJ4sKPTAcAgECAgEBBBQMEmNvbS5taW5vc2dhbWUuZGlhbzAcAgEFAgEBBBRS8nBWnaI9IObMcR6aHZk3URJJoDAeAgEIAgEBBBYWFDIwMTctMTEtMjlUMTY6Mzg6NTJaMB4CAQwCAQEEFhYUMjAxNy0xMS0yOVQxNjozODo1MlowHgIBEgIBAQQWFhQyMDE3LTExLTIyVDE3OjI2OjU3WjBMAgEHAgEBBETy DyMUw6ea9fFOfWdjOj32wmmJApVnwmpJW8xQI3tkw5fJGzpYHjWNNbN8q9M0522\/7hRcSo\/ypYhdaH hrc5kQKr7DBXAgEGAgEBBE9cSrWK0qsj1RxlQammIXYXcqtQyWSpjM7FZCrM BWDcqlQu91sdqmZMyNZU0wwl6nbWmYqoDtW\/eLJMghicyanz3AwnyY4z9aAXhd6v6NdMIIBWQIBEQIBAQSCAU8xggFLMAsCAgasAgEBBAIWADALAgIGrQIBAQQCDAAwCwICBrACAQEEAhYAMAsCAgayAgEBBAIMADALAgIGswIBAQQCDAAwCwICBrQCAQEEAgwAMAsCAga1AgEBBAIMADALAgIGtgIBAQQCDAAwDAICBqUCAQEEAwIBATAMAgIGqwIBAQQDAgEBMAwCAgavAgEBBAMCAQAwDAICBrECAQEEAwIBADAPAgIGrgIBAQQGAgRNyT2sMBkCAganAgEBBBAMDjYwMDAwMzc5ODQ5Mzc2MBkCAgapAgEBBBAMDjYwMDAwMzc5ODQ5Mzc2MB8CAgaoAgEBBBYWFDIwMTctMTEtMjlUMTY6Mzg6NTJaMB8CAgaqAgEBBBYWFDIwMTctMTEtMjlUMTY6Mzg6NTJaMCACAgamAgEBBBcMFWNvbS5taW5vc2dhbWUuZGlhbzE5OKCCDmUwggV8MIIEZKADAgECAggO61eH554JjTANBgkqhkiG9w0BAQUFADCBljELMAkGA1UEBhMCVVMxEzARBgNVBAoMCkFwcGxlIEluYy4xLDAqBgNVBAsMI0FwcGxlIFdvcmxkd2lkZSBEZXZlbG9wZXIgUmVsYXRpb25zMUQwQgYDVQQDDDtBcHBsZSBXb3JsZHdpZGUgRGV2ZWxvcGVyIFJlbGF0aW9ucyBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTAeFw0xNTExMTMwMjE1MDlaFw0yMzAyMDcyMTQ4NDdaMIGJMTcwNQYDVQQDDC5NYWMgQXBwIFN0b3JlIGFuZCBpVHVuZXMgU3RvcmUgUmVjZWlwdCBTaWduaW5nMSwwKgYDVQQLDCNBcHBsZSBXb3JsZHdpZGUgRGV2ZWxvcGVyIFJlbGF0aW9uczETMBEGA1UECgwKQXBwbGUgSW5jLjELMAkGA1UEBhMCVVMwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQClz4H9JaKBW9aH7SPaMxyO4iPApcQmyz3Gn xKDVWG\/6QC15fKOVRtfX yVBidxCxScY5ke4LOibpJ1gjltIhxzz9bRi7GxB24A6lYogQ IXjV27fQjhKNg0xbKmg3k8LyvR7E0qEMSlhSqxLj7d0fmBWQNS3CzBLKjUiB91h4VGvojDE2H0oGDEdU8zeQuLKSiX1fpIVK4cCc4Lqku4KXY\/Qrk8H9Pm\/KwfU8qY9SGsAlCnYO3v6Z\/v\/Ca\/VbXqxzUUkIVonMQ5DMjoEC0KCXtlyxoWlph5AQaCYmObgdEHOwCl3Fc9DfdjvYLdmIHuPsB8\/ijtDT iZVge\/iA0kjAgMBAAGjggHXMIIB0zA\/BggrBgEFBQcBAQQzMDEwLwYIKwYBBQUHMAGGI2h0dHA6Ly9vY3NwLmFwcGxlLmNvbS9vY3NwMDMtd3dkcjA0MB0GA1UdDgQWBBSRpJz8xHa3n6CK9E31jzZd7SsEhTAMBgNVHRMBAf8EAjAAMB8GA1UdIwQYMBaAFIgnFwmpthhgi zruvZHWcVSVKO3MIIBHgYDVR0gBIIBFTCCAREwggENBgoqhkiG92NkBQYBMIH MIHDBggrBgEFBQcCAjCBtgyBs1JlbGlhbmNlIG9uIHRoaXMgY2VydGlmaWNhdGUgYnkgYW55IHBhcnR5IGFzc3VtZXMgYWNjZXB0YW5jZSBvZiB0aGUgdGhlbiBhcHBsaWNhYmxlIHN0YW5kYXJkIHRlcm1zIGFuZCBjb25kaXRpb25zIG9mIHVzZSwgY2VydGlmaWNhdGUgcG9saWN5IGFuZCBjZXJ0aWZpY2F0aW9uIHByYWN0aWNlIHN0YXRlbWVudHMuMDYGCCsGAQUFBwIBFipodHRwOi8vd3d3LmFwcGxlLmNvbS9jZXJ0aWZpY2F0ZWF1dGhvcml0eS8wDgYDVR0PAQH\/BAQDAgeAMBAGCiqGSIb3Y2QGCwEEAgUAMA0GCSqGSIb3DQEBBQUAA4IBAQANphvTLj3jWysHbkKWbNPojEMwgl\/gXNGNvr0PvRr8JZLbjIXDgFnf4 LXLgUUrA3btrj \/DUufMutF2uOfx\/kd7mxZ5W0E16mGYZ2 FogledjjA9z\/Ojtxh umfhlSFyg4Cg6wBA3LbmgBDkfc7nIBf3y3n8aKipuKwH8oCBc2et9J6Yz PWY4L5E27FMZ\/xuCk\/J4gao0pfzp45rUaJahHVl0RYEYuPBX\/UIqc9o2ZIAycGMs\/iNAGS6WGDAfK PdcppuVsq1h1obphC9UynNxmbzDscehlD86Ntv0hgBgw2kivs3hi1EdotI9CO\/KBpnBcbnoB7OUdFMGEvxxOoMIIEIjCCAwqgAwIBAgIIAd68xDltoBAwDQYJKoZIhvcNAQEFBQAwYjELMAkGA1UEBhMCVVMxEzARBgNVBAoTCkFwcGxlIEluYy4xJjAkBgNVBAsTHUFwcGxlIENlcnRpZmljYXRpb24gQXV0aG9yaXR5MRYwFAYDVQQDEw1BcHBsZSBSb290IENBMB4XDTEzMDIwNzIxNDg0N1oXDTIzMDIwNzIxNDg0N1owgZYxCzAJBgNVBAYTAlVTMRMwEQYDVQQKDApBcHBsZSBJbmMuMSwwKgYDVQQLDCNBcHBsZSBXb3JsZHdpZGUgRGV2ZWxvcGVyIFJlbGF0aW9uczFEMEIGA1UEAww7QXBwbGUgV29ybGR3aWRlIERldmVsb3BlciBSZWxhdGlvbnMgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDKOFSmy1aqyCQ5SOmM7uxfuH8mkbw0U3rOfGOAYXdkXqUHI7Y5\/lAtFVZYcC1 xG7BSoU L\/DehBqhV8mvexj\/avoVEkkVCBmsqtsqMu2WY2hSFT2Miuy\/axiV4AOsAX2XBWfODoWVN2rtCbauZ81RZJ\/GXNG8V25nNYB2NqSHgW44j9grFU57Jdhav06DwY3Sk9UacbVgnJ0zTlX5ElgMhrgWDcHld0WNUEi6Ky3klIXh6MSdxmilsKP8Z35wugJZS3dCkTm59c3hTO\/AO0iMpuUhXf1qarunFjVg0uat80YpyejDi l5wGphZxWy8P3laLxiX27Pmd3vG2P kmWrAgMBAAGjgaYwgaMwHQYDVR0OBBYEFIgnFwmpthhgi zruvZHWcVSVKO3MA8GA1UdEwEB\/wQFMAMBAf8wHwYDVR0jBBgwFoAUK9BpR5R2Cf70a40uQKb3R01\/CF4wLgYDVR0fBCcwJTAjoCGgH4YdaHR0cDovL2NybC5hcHBsZS5jb20vcm9vdC5jcmwwDgYDVR0PAQH\/BAQDAgGGMBAGCiqGSIb3Y2QGAgEEAgUAMA0GCSqGSIb3DQEBBQUAA4IBAQBPz 9Zviz1smwvj 4ThzLoBTWobot9yWkMudkXvHcs1Gfi\/ZptOllc34MBvbKuKmFysa\/Nw0Uwj6ODDc4dR7Txk4qjdJukw5hyhzs r0ULklS5MruQGFNrCk4QttkdUGwhgAqJTleMa1s8Pab93vcNIx0LSiaHP7qRkkykGRIZbVf1eliHe2iK5IaMSuviSRSqpd1VAKmuu0swruGgsbwpgOYJd W NKIByn\/c4grmO7i77LpilfMFY0GCzQ87HUyVpNur cmV6U\/kTecmmYHpvPm0KdIBembhLoz2IYrF Hjhga6\/05Cdqa3zr\/04GpZnMBxRpVzscYqCtGwPDBUfMIIEuzCCA6OgAwIBAgIBAjANBgkqhkiG9w0BAQUFADBiMQswCQYDVQQGEwJVUzETMBEGA1UEChMKQXBwbGUgSW5jLjEmMCQGA1UECxMdQXBwbGUgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkxFjAUBgNVBAMTDUFwcGxlIFJvb3QgQ0EwHhcNMDYwNDI1MjE0MDM2WhcNMzUwMjA5MjE0MDM2WjBiMQswCQYDVQQGEwJVUzETMBEGA1UEChMKQXBwbGUgSW5jLjEmMCQGA1UECxMdQXBwbGUgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkxFjAUBgNVBAMTDUFwcGxlIFJvb3QgQ0EwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDkkakJH5HbHkdQ6wXtXnmELes2oldMVeyLGYne Uts9QerIjAC6Bg  FAJ039BqJj50cpmnCRrEdCju QbKsMflZ56DKRHi1vUFjczy8QPTc4UadHJGXL1XQ7Vf1 b8iUDulWPTV0N8WQ1IxVLFVkds5T39pyez1C6wVhQZ48ItCD3y6wsIG9wtj8BMIy3Q88PnT3zK0koGsj zrW5DtleHNbLPbU6rfQPDgCSC7EhFi501TwN22IWq6NxkkdTVcGvL0Gz PvjcM3mo0xFfh9Ma1CWQYnEdGILEINBhzOKgbEwWOxaBDKMaLOPHd5lc\/9nXmW8Sdh2nzMUZaF3lMktAgMBAAGjggF6MIIBdjAOBgNVHQ8BAf8EBAMCAQYwDwYDVR0TAQH\/BAUwAwEB\/zAdBgNVHQ4EFgQUK9BpR5R2Cf70a40uQKb3R01\/CF4wHwYDVR0jBBgwFoAUK9BpR5R2Cf70a40uQKb3R01\/CF4wggERBgNVHSAEggEIMIIBBDCCAQAGCSqGSIb3Y2QFATCB8jAqBggrBgEFBQcCARYeaHR0cHM6Ly93d3cuYXBwbGUuY29tL2FwcGxlY2EvMIHDBggrBgEFBQcCAjCBthqBs1JlbGlhbmNlIG9uIHRoaXMgY2VydGlmaWNhdGUgYnkgYW55IHBhcnR5IGFzc3VtZXMgYWNjZXB0YW5jZSBvZiB0aGUgdGhlbiBhcHBsaWNhYmxlIHN0YW5kYXJkIHRlcm1zIGFuZCBjb25kaXRpb25zIG9mIHVzZSwgY2VydGlmaWNhdGUgcG9saWN5IGFuZCBjZXJ0aWZpY2F0aW9uIHByYWN0aWNlIHN0YXRlbWVudHMuMA0GCSqGSIb3DQEBBQUAA4IBAQBcNplMLXi37Yyb3PN3m\/J20ncwT8EfhYOFG5k9RzfyqZtAjizUsZAS2L70c5vu0mQPy3lPNNiiPvl4\/2vIB x9OYOLUyDTOMSxv5pPCmv\/K\/xZpwUJfBdAVhEedNO3iyM7R6PVbyTi69G3cN8PReEnyvFteO3ntRcXqNx IjXKJdXZD9Zr1KIkIxH3oayPc4FgxhtbCS SsvhESPBgOJ4V9T0mZyCKM2r3DYLP3uujL\/lTaltkwGMzd\/c6ByxW69oPIQ7aunMZT7XZNn\/Bh1XZp5m5MkL72NVxnn6hUrcbvZNCJBIqxw8dtk2cXmPIS4AXUKqK1drk\/NAJBzewdXUhMYIByzCCAccCAQEwgaMwgZYxCzAJBgNVBAYTAlVTMRMwEQYDVQQKDApBcHBsZSBJbmMuMSwwKgYDVQQLDCNBcHBsZSBXb3JsZHdpZGUgRGV2ZWxvcGVyIFJlbGF0aW9uczFEMEIGA1UEAww7QXBwbGUgV29ybGR3aWRlIERldmVsb3BlciBSZWxhdGlvbnMgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkCCA7rV4fnngmNMAkGBSsOAwIaBQAwDQYJKoZIhvcNAQEBBQAEggEAgHGFq9ZkrgMnjFZabg3UBv8BUTLIkGY5R05S dxgqgMWGi3fFE8PsG0WN8UZ 9B7A3rSYZtCuk80\/R3ZgIxGUakdoBxQmiCs3WFoBtAji4Q7CBKll6YzBYomEBeEp CSUMD6x5PCywfr0a4q7pyjzFHjZUQqo0mfEaX1mFOGHxsd8CCf\/goNUWPVWXXR3sOGU8ij nZ0DqGmnvp9AtN9HxeSqBRdQz kz18ksQLdJffYylrfkqZ2y\/eO2MnTLQNYoREE5O7rHmwRz6pm\/fGXRpwKmNDs56ROWEBsS5NY5zwWOp\/vqxV9MvwZBLbfEceZjejbSbf4mf73Vl1CK1cB w==","sign":"2adf216a96ce4c21590df9b03b8af0f4"}'; 
	
	$dataOut = json_decode($dataOutStr);

	$platformID = $dataOut['platformID'];

	$serverID = $dataOut['serverID'];

	$roleIndex = $dataOut['roleIndex'];

	$account = $dataOut['account'];

	$fee = $dataOut['fee']; // 费用
	
	$cbi = $dataOut['cbi']; // 档位

	$transID = $dataOut['transID'];

	$transReceipt = str_replace(' ','+',$dataOut['transReceipt']);	

	$sign = $dataOut['sign'];

	$signStr = $platformID.$serverID.$roleIndex.$account.$fee.$cbi.$transID.$transReceipt; 

	$signEncrypt = md5($signStr.$TokenKey); 

	if($signEncrypt == $sign)
	{		
		//$url = 'https://sandbox.itunes.apple.com/verifyReceipt';
	    $url = 'https://buy.itunes.apple.com/verifyReceipt';

		$jsonTransReceipt = ['receipt-data'=>$transReceipt];

		$iosRet = send_request($url,json_encode($jsonTransReceipt),'utf-8');
		
		$InPayData = json_decode(trim($iosRet),true); 
		
		_log('验证通过'.$InPayData['status'],'ios-sdk');
 
		if((int)$InPayData['status']==0)
		{
			$db = new  Mysqldb();
			
			$ioscbi = $InPayData['receipt']['in_app'][0]['product_id']; // 档位 对应价格
			$ssid =  $InPayData['receipt']['in_app'][0]['transaction_id']; // 流水号
			$tcd = $InPayData['receipt']['in_app'][0]['original_transaction_id']; // 订单号
			//verifycbi();
			// 档位验证
			$data = [
			'platId'=>$platformID,	
			'server_id'=>$serverID,
			'RoleIndex'=>$roleIndex,
			'cbi'=>$cbi,
			'uid'=>$account,
			'fee'=>$dataOut['fee'],
			'ssid'=>$ssid,
			'tcd'=>trim($tcd)			
			];
			
			$Inlogdb = $data +  ['sign'=>$dataOut['sign'],
			'signlist'=>json_encode($dataOut),'status'=>1,'createtime'=>date('Y-m-d H:i:s',time())];

			// 提前记录订单日志
			$setLogRet = setPayLog($Inlogdb,$db);
			// 验证订单档位
			$cbiverifyRet = verifycbi($db,$cbi,$fee,$ioscbi);
			
			if ($cbiverifyRet)
			{ 
				if($setLogRet)
				{ 
					
					$sendret = send($data,$db,'发送成功!','发送失败','SaveMoneryInform');
					
					_log(' ret info '.$sendret,'1sdk-log');
					
					if($sendret==true)
					{
						// 变更订单状态成功
						editPayLog(2,$data['tcd'],$db,'set pay log Error:line:1!');						 
						exit('{"status":0,"result":"支付成功!"}');
					}
					// 变更订单状态失败
					editPayLog(3,$data['tcd'],$db,'set pay log Error:line:2');					 
					exit('{"status":1201,"result":"秘钥验证成功!与服务端通讯失败!"}');				 
				} 
				_log('iosRet::1202'.$iosRet,'ios-sdk');
				exit('{"status":1202,"result":"日志记录失败!"}');
			} 
			_log('iosRet::1203'.$iosRet.'#iosdata-'.json_encode($Inlogdb),'ios-sdk');
			exit('{"status":1203,"result":"档位验证失败!"}');
		}  
		_log('iosRet::'.$iosRet,'ios-sdk');
		exit($iosRet);
	}
	exit('{"status":1205,"result":"sign验证失败!"}');
