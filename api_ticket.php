<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);
$get_token = file_get_contents("accesstoken.txt");
$get_accesstoken = json_decode($get_token,true);

$auth_token = $get_accesstoken['token'];
$time = $get_accesstoken['time'];
$currenttime = time();
if(($currenttime-$time) > 3000) {     
    $token =  crm_access_token();
    $newarr['time']=time();
    $newarr['token']=$token;
    $newjson = json_encode($newarr);
    file_put_contents("accesstoken.txt", $newjson);
}
 $getticket =  Ticketdata($auth_token);
 echo "<pre>"; print_r($getticket);
 function Ticketdata($auth_token=null){
    $data = '{
  "subCategory" : "Sub General",
  "productId" : "",
  "contactId" : "570499000000523001",
  "subject" : "Test Data ",
  "departmentId" : "570499000000006907",
  "channel" : "Email",
  "accountId" : "570499000000575049",
  "description" : "Hi This is Description",
  "language" : "English",
  "priority" : "Low",
  "classification" : "",
  "phone" : "1 888 900 9646",
  "category" : "general",
  "email" : "flavienpicazo@gmail.com",
  "status" : "Open"
}';

$insert_deskdata = Craete_tickets($data,$auth_token);
// return  $insert_deskdata;
if (!empty($insert_deskdata)) {
     $Curlsata['Owner'] = "4769355000002092001";
    $Curlsata['Account_Name'] = "4769355000001408089";
    $Curlsata['Contact_Name'] = "4769355000001662022";
    $Curlsata['Deal_Name'] = "Test Data";
    $Curlsata['Closing_Date'] ="2020-07-24";
    $Curlsata['Stage'] = "Closed Won";
     $query15['data'][] = $Curlsata; 
     
    $create_deals = create_deals($query15,$auth_token);
    return $create_deals;
}
//     // return $insert_deskdata;
}

function crm_access_token(){
        $url = "https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.1cc8d39ec2c0026f33b09288d54de71b.bbd727745d460b5de02e82cb3f361bf0&client_id=1000.Y9AODMV5YG56HKD7ZQPM2C45PBVJER&client_secret=4e34a48206d72440f3ba541c9ee94a941cb2e04000&grant_type=refresh_token";
        $resp = curlRequest($url,"","POST",array());
        $resp_decode = json_decode($resp,true);
        return $resp_decode['access_token'] ?? null;
        
    } 
    function Craete_tickets($data,$auth_token){
        $header = array('orgId :737179893', 'Authorization: Zoho-oauthtoken '.$auth_token);
        $url = "https://desk.zoho.com/api/v1/tickets";
         $con_result = curlRequest($url,$data,"POST",$header); 
         $con_result  = json_decode($con_result,true);
 RETURN $con_result;
    }

    function create_deals($query15,$auth_token){
         $header = array('Authorization: Zoho-oauthtoken '.$auth_token);
        $con_url = "https://www.zohoapis.com/crm/v2/Deals";
        $con_result = curlRequest($con_url,json_encode($query15),"POST",$header); 
        return $con_result  = json_decode($con_result,true);
         
    }
    // function get_departmant_id($departmant_Name,$header){
    //         $curl = "https://desk.zoho.com/api/v1/departments";
    //      $con_result = curlRequest($curl,"","GET",$header); 
    //      $con_result  = json_decode($con_result,true);
    //     $board_Result =  searchForId($departmant_Name, $con_result['data']);
    //     return $board_Result['id'];
    //     // $account_id = $con_result['data']['0']['details']['id'];
    //     //    $account_id;

    // }

 // function searchForId($id =null, $array=null) {

 //       foreach ($array as $key => $val) {

 //           if ($val['name'] === $id) {
 //                $data['key']= $key;
 //                $data['id']= $val['id'];
 //               return $data;
 //           }
 //       }
 //       return null;
 //    }




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