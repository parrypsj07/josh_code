<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
$get_token = file_get_contents("accesstoken_test.txt");
$get_accesstoken = json_decode($get_token,true);
$auth_token = $get_accesstoken['token'];
$time = $get_accesstoken['time'];
$currenttime = time();
if(($currenttime-$time) > 3000) {     
	$auth_token =  crm_access_token();
	$newarr['time']=time();
	$newarr['token']=$auth_token;
	$newjson = json_encode($newarr);
	file_put_contents("accesstoken_test.txt", $newjson);


}


$json = file_get_contents('php://input');
file_put_contents("ticket_data.txt", $json);
$data = json_decode($json,true);
$header = array('Authorization: Zoho-oauthtoken '.$auth_token);
$resetdata = Get_data($data ,$header);
echo "Success";
$to = "parmeet.singh64@gmail.com";
$email_from="maninder.unicloud@gmail.com";
$headers = "From: $email_from \r\n";
$email_subject="Test email";
$email_body="success";
mail($to,$email_subject,$email_body,$headers);
 //echo '<pre>'; print_r($resetdata);die();

function Get_data($data='',$token='')
{
	foreach ($data as $key => $response)
	{
		$dealarr['Deal_Name']= $response['name'];
		$dealarr['Deal_Name']= $response['name'];
		$dealarr['Deal_Email']= $response['email'];
		$dealarr['Owner']= "4769355000004252001";
		$dealarr['Closing_Date'] ="2020-08-01";
		$dealarr['Stage'] = "Evaluation"; 
		$dealarr['Role'] = $response['role'];   
		$dealarr['Account_Number'] = $response['pt_account_number'];   
		$dealarr['Account_ID'] = $response['pt_account_id'];   
		$dealarr['Contact_ID'] = $response['pt_contact_id'];   
		$dealarr['Identity_Cleared'] = $response['identity_cleared'];   
		$dealarr['Identity_Photo_cleared'] = $response['identity_photo_cleared'];   
		$dealarr['proof_of_address'] = $response['proof_of_address'];   
		$dealarr['cip_Status'] = $response['cip']['status'];   
		$dealarr['cip_exception'] = $response['cip']['exception'];   
		$dealarr['cip_exception_details'] = $response['cip']['exception_details'];   
		$dealarr['aml_Status'] = $response['aml']['status'];   
		$dealarr['aml_exception'] = $response['aml']['exception'];   
		$dealarr['aml_exception_details'] = $response['aml']['exception_details'];
		$dealarr['PT_Account_Status'] = $response['pt_account_status'];
		$dealarr['KYC_Bot_Message'] = $response['kyc_bot_message'];
		$dealarr['KYC_Custom_Message'] = $response['kyc_custom_message'];
		$dealarr['duplicate_check_fields'] =  ["Account_Number"];


		$Create_Deals = Create_Deals($dealarr,$token);
		foreach ($response['documents'] as $key => $value)
		{
			$documentarr['Name']  = $value['name'];
			$documentarr['Document_Deal_Name']= $Create_Deals;
			$documentarr['Document_Type']  = $value['type'];
			$documentarr['Owner']= "4769355000004252001";
			$documentarr['Document_Status']  = $value['status'];
			$documentarr['Document_failure_details']  = $value['failure_details'];
			$documentarr['Document_exception']  = $value['exception'];
			$documentarr['duplicate_check_fields'][] =  "Name";
			$Create_documants  =Create_documants($documentarr,$token);
 			//print_r($Create_documants);
			//print_r($Create_documants);
			//print_r($Create_documants);

		}
	}
}



function crm_access_token(){
	$url = "https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.848d5107fa22d18d0cc47662c5ec47f5.1b32410b50c5f617a66d9d70f5b31dd6&client_id=1000.Y9AODMV5YG56HKD7ZQPM2C45PBVJER&client_secret=4e34a48206d72440f3ba541c9ee94a941cb2e04000&grant_type=refresh_token";
	$resp = curlRequest($url,"","POST",array());
	$resp_decode = json_decode($resp,true);
	return $resp_decode['access_token'] ?? null;

} 



function Create_Deals($arra,$header){
	$query121['data'][] = $arra;
	$con_url = "https://www.zohoapis.com/crm/v2/Deals/upsert";
	$con_result = curlRequest($con_url,json_encode($query121),"POST",$header); 
	$con_result  = json_decode($con_result,true);
	$deal_id = $con_result['data']['0']['details']['id'];
	return $deal_id;
}

function Create_documants($documentarr ,$token ){
	$query11['data'][] = $documentarr;
	$con_url = "https://www.zohoapis.com/crm/v2/Document_Infomation/upsert";
	$con_result = curlRequest($con_url,json_encode($query11),"POST",$token); 
	$con_result  = json_decode($con_result,true);
	$deal_id['message'] = $con_result['data']['0']['message'];
	$deal_id['id'] = $con_result['data']['0']['details']['id'];
	return  $deal_id;

}


function curlRequest($url=nulls, $query = null, $method, $header = null){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$server_output = curl_exec($ch);
	return $server_output;
	curl_close($ch);
}


?>