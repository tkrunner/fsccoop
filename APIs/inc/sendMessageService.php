<?php
class SendMessageService
{
    // version 1.0

    public static function utf8_to_tis620($string)
    {
        $str = $string;
        $res = "";
        for ($i = 0; $i < strlen($str); $i++) {
            if (ord($str[$i]) == 224) {
                $unicode = ord($str[$i + 2]) & 0x3F;
                $unicode |= (ord($str[$i + 1]) & 0x3F) << 6;
                $unicode |= (ord($str[$i]) & 0x0F) << 12;
                $res .= chr($unicode - 0x0E00 + 0xA0);
                $i += 2;
            } else {
                $res .= $str[$i];
            }
        }
        return $res;
    }

    // $proxy = 'localhost:7777';
    // $proxy_userpwd = 'username:password';
    public static function sendMessage($account, $password, $mobile_no, $message, $schedule = '', $category = '', $sender_name = '', $proxy = '', $proxy_userpwd = '')
    {
        $option = '';
        if ($category == '') {
            $category = 'General';
        }
        $option = "SEND_TYPE=$category";
        if ($sender_name != '') {
            $option .= ",SENDER=$sender_name";
        }

        $params = array(
            'ACCOUNT' => $account,
            'PASSWORD' => $password,
            'MOBILE' => $mobile_no,
            'MESSAGE' => self::utf8_to_tis620($message)
        );
        if ($schedule) {
            $params['SCHEDULE'] = $schedule;
        }
        if ($option) {
            $params['OPTION'] = $option;
        }

        $curl_options = array(
            CURLOPT_URL => 'https://sc4msg.com/bulksms/SendMessage',
            CURLOPT_PORT => 443,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLVERSION => 6,
            CURLOPT_RETURNTRANSFER => true,
        );
        if ($proxy != '') {
            $curl_options[CURLOPT_PROXY] = $proxy;
            if ($proxy_userpwd != '') {
                $curl_options[CURLOPT_PROXYUSERPWD] = $proxy_userpwd;
            }
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return array('result' => false, 'error' => $error);
        } else {
            // STATUS=0
            // MESSAGE_ID=10331032,10331033
            // TASK_ID=109692
            // END=OK
            $results = explode("\n", trim($response));
            $index = count($results) - 1;
            if (trim($results[$index]) == 'END=OK') {
                $results[0] = trim($results[0]);
                if ($results[0] == 'STATUS=0') {
                    $msg_id = explode("=", $results[1]);
                    $task_id = explode("=", $results[2]);
                    return array(
                        'result'     => true,
                        'task_id'    => $task_id[1],
                        'message_id' => $msg_id[1]
                    );
                } else {
                    return array(
                        'result' => false,
                        'error'  => $results[0]
                    );
                }
            } else {
                return array(
                    'result' => false,
                    'error'  => "Incorrect Response: {$response}"
                );
            }
        }
    }
}
