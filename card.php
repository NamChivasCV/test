<?php

error_reporting(0);
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/core/init.php');
$iduser = $data_user['username'];
$name = addslashes($data_user['name']);

function curl($url, $post = false, $ref = '', $cookie = false, $follow = false, $cookies = false, $header = true, $headers = false)
{
    $ch = curl_init($url);
    if ($ref != '') {
        curl_setopt($ch, CURLOPT_REFERER, $ref);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($cookies) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
    }
    if ($post) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_POST, 1);
    }
    if ($follow) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if ($header)     curl_setopt($ch, CURLOPT_HEADER, 1);
    if ($headers)        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_ENCODING, '');
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $result[0] = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $result[1] = substr($result[0], $header_size);
    curl_close($ch);
    return $result;
}
function curl1($url, $post = false, $ref = '', $cookie = false, $cookies = false, $header = true, $headers = false, $follow = false)
{
    $ch = curl_init($url);
    if ($ref != '') {
        curl_setopt($ch, CURLOPT_REFERER, $ref);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($cookies) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
    }
    if ($post) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_POST, 1);
    }
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $result[0] = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $result[1] = substr($result[0], $header_size);
    curl_close($ch);
    return $result;
}


function getcookie($content)
{
    preg_match_all('/Set-Cookie: (.*);/U', $content, $temp);
    $cookie = $temp[1];
    $cookies = "";
    $a = array();
    foreach ($cookie as $c) {
        $pos = strpos($c, "=");
        $key = substr($c, 0, $pos);
        $val = substr($c, $pos + 1);
        $a[$key] = $val;
    }
    foreach ($a as $b => $c) {
        $cookies .= "{$b}={$c};";
    }
    return $cookies;
}

function number_in_string($string)
{

    try {

        if (!is_string($string))
            throw new Exception("Error : Param Type");


        preg_match_all("/\d+/", $string, $matches);

        // Return the all coincidences
        return $matches[0];
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function card_type($stt)
{
    switch ($stt) {
        case "Vinaphone":
            $text = "VNP";
            break;
        case "Mobifone":
            $text = "VMS";
            break;
        case "Viettel":
            $text = "VTT";
            break;
        case "Gate":
            $text = "Gate";
            break;
        default:
            // code...
            break;
    }
    return $text;
}





if (!$user) {
    echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Bạn chưa đăng nhập"));
} else {


    if (!$_POST['type']) {
        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Chưa chọn loại nhà mạng"));
    } elseif (!$_POST['code']) {
        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Chưa nhập mã thẻ"));
    } elseif ($_POST['menhgia'] == "chuachon") {
        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "VUI LÒNG CHỌN MỆNH GIÁ THẺ"));
    } elseif (!$_POST['serial']) {
        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Chưa nhập mã seri"));
    } else if ($_POST['type'] == "Viettel" && (strlen($_POST['serial']) != 11 && strlen($_POST['serial']) != 14)) {
        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Độ dài không hợp lệ, Seri thẻ Viettel phải có độ dài 11 hoặc 14"));
    } else if ($_POST['type'] == "Viettel" && (strlen($_POST['code']) != 13 && strlen($_POST['code']) != 15)) {
        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Độ dài không hợp lệ , Mã thẻ Viettel phải có độ dài 13 hoặc 15"));
    } else if ($_POST['type'] == "Mobifone" && (strlen($_POST['code']) != 12)) {
        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Độ dài không hợp lệ, Mã thẻ Mobifone phải có độ dài là 12"));
    } else if ($_POST['type'] == "Vinaphone" && (strlen($_POST['code']) != 14)) {
        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Độ dài không hợp lệ , Mã thẻ Vinaphone phải có độ dài là 14"));
    } else {


        function remove_n($num)
        {
            return preg_replace('/[^0-9]/', '', $num);
        }

        function curl_post($url, $data)
        {
            $dataPost = '';

            if (is_array($data))
                $dataPost = http_build_query($data);
            else
                $dataPost = $data;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPost);
            $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            curl_setopt($ch, CURLOPT_REFERER, $actual_link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            return $result;
        }


        function xss_clean($data)
        {
            // Fix &entity\n;
            $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
            $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
            $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
            $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

            // Remove any attribute starting with "on" or xmlns
            $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

            // Remove javascript: and vbscript: protocols
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
            $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

            // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
            $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

            // Remove namespaced elements (we do not need them)
            $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

            do {
                // Remove really unwanted tags
                $old_data = $data;
                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
            } while ($old_data !== $data);

            // we are done...
            return $data;
        }


        $TxtSeri = xss_clean($_POST['serial']);

        $TxtCard  = xss_clean($_POST['type']);
        $menhgia  = xss_clean($_POST['menhgia']);
        $TxtMaThe = xss_clean($_POST['code']);

        $count = $db->fetch_row("SELECT COUNT(id) FROM history_card WHERE seri = '{$TxtSeri}' AND code = '{$TxtMaThe}' LIMIT 1");



        if ($count == 0) {

            // nap the thanh cong

            $info_card = 0;
            if ($info_card == 10000) {
                $vnd = 10000;
            } elseif ($info_card == 20000) {
                $vnd = 20000;
            } elseif ($info_card == 30000) {
                $vnd = 30000;
            } elseif ($info_card == 50000) {
                $vnd = 50000;
            } elseif ($info_card == 100000) {
                $vnd = 100000;
            } elseif ($info_card == 200000) {
                $vnd = 200000;
            } elseif ($info_card == 300000) {
                $vnd = 300000;
            } elseif ($info_card == 500000) {
                $vnd = 500000;
            } elseif ($info_card == 1000000) {
                $vnd = 1000000;
            } else {
                $vnd = 0;
            }
            if ($info_card == 50000) {
                $db->query("UPDATE `accounts` SET `point` = `point` + '1' WHERE `username` = '{$iduser}'");
            } elseif ($info_card == 100000) {
                $db->query("UPDATE `accounts` SET `point` = `point` + '2' WHERE `username` = '{$iduser}'");
            } elseif ($info_card == 200000) {
                $db->query("UPDATE `accounts` SET `point` = `point` + '4' WHERE `username` = '{$iduser}'");
            } elseif ($info_card == 500000) {
                $db->query("UPDATE `accounts` SET `point` = `point` + '10' WHERE `username` = '{$iduser}'");
            } elseif ($info_card == 1000000) {
                $db->query("UPDATE `accounts` SET `point` = `point` + '30' WHERE `username` = '{$iduser}'");
            } else {
                #nothing    
            }
            $partner_id = '4562835561'; //API key, lấy từ website thesieure.vn thay vào trong cặp dấu '
            $partner_key = 'd24784e8a420f635d28a109c8b421b16'; //API secret lấy từ website thesieure.vn thay vào trong cặp dấu '
            $telco = $TxtCard;
            $amount = $menhgia;
            $serial = $TxtSeri;
            $code = $TxtMaThe;


            $request_id = rand(100000000, 999999999);  /// Cái này có thể mà mã order của bạn, nếu không có thì để nguyên ko cần động vào.
            $sign = md5($partner_id . $partner_key . $telco . $code . $serial . $amount . $request_id);

            $dataPost = array(
                'partner_id' => $partner_id,
                'request_id' => $request_id,
                'telco' => $telco,
                'amount' => $amount,
                'serial' => $serial,
                'code' => $code,
                'sign' => $sign
            );

            $post = curl_post('https://cardviet.vn/chargingws', $dataPost);
            $obj = json_decode($post);

            if ($obj->status == 99) {
                $now = getdate();
                $db->query("INSERT INTO `history_card` (username,seri,code,name,menhgia,type_card,count_card,time) VALUES ('$iduser','$TxtSeri','$TxtMaThe','$name','$menhgia','$TxtCard','$vnd','$date_current')"); // lịch sử
                echo json_encode(array('status' => "success", 'title' => "Thành công", 'msg' => " Thẻ đang được xử lý ( thời gian xử lý 2 đến 5 phút ), Quý khách sẽ được cộng tiền ngay khi thẻ xử lý xong. Trân trọng !."));
            } else if ($obj->status == 3) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => ' Sai seri hoặc mã thẻ, vui lòng nhập lại'));
            } else if ($obj->status == NULL) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => 'Ko tra ve callback'));
            }
        } else {
            $err = isset($msg) ? $msg : 'Thẻ bị trùng, vui lòng nhập thẻ khác';
            echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => 'Thẻ bị trùng, vui lòng nhập thẻ khác'));
        }
    }
}
