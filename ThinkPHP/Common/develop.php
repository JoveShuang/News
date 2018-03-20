<?php 

/**
 * iexn冠名
 */

/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5');  调用Admin模块的User接口
 * @param  string  $name 格式 [模块名]/接口名/方法名
 * @param  array|string  $vars 参数
 * @return mixed
 */
// function api($name, $vars = array()) {
//     $array      = explode('/', $name);
//     $method     = array_pop($array);
//     $class_name = array_pop($array);
//     $module     = $array?array_pop($array):'Common';
//     $callback   = $module.'\\Api\\'.$class_name.'Api::'.$method;
//     if (is_string($vars)) {
//         parse_str($vars, $vars);
//     }
//     return call_user_func_array($callback, $vars);
// }

/**
 * Important:
 * 获取多选的名称分组，支持使用 , ; | / 拆分
 * @param  string $keys [description]
 * @return [type]       [description]
 */
function getKeysArray($keys = '') {
    if(is_array($keys)) {
        return $keys;
    }
    return preg_split('/[,;|\/]+/', trim($keys));
}

/**
 * 驼峰法转小写下划线命名 
 * @param  [type]  $str  [description]
 * @return [type]        [description]
 */
function camelToLower($str = '') {
    return strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $str)); break;
}

/**
 * 驼峰法转大写下划线命名 
 * @param  [type]  $str  [description]
 * @return [type]        [description]
 */
function camelToUpper($str = '') {
    return strtoupper(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_', $str)); break;
}


/**
 * 空字符串时返回参数2，否则返回本身
 */
function reData($data = '', $return = '---', $dataOpt = null) {
    if (null === $dataOpt) {
        $dataOpt = $data;
    }
    return ''===$data?$return:$dataOpt;
}

/**
 * 数据为空时返回参数2，默认为false
 * 不为空时返回本身，参数3存在时返回参数3
 */
function reEmpty($data = '', $return = false, $dataOpt = null) {
    if (null === $dataOpt) {
        $dataOpt = $data;
    }
    return empty($data)?$return:$dataOpt;
}

function reNonf($data, $return = '---', $type = false) {
    $bool = true === $type?!!$data:$data;
    return (null === $bool || false === $bool)?$return:$data;
}


/**
 * 返回时间格式，参数2为返回格式标识，参数3为不是时间戳或时间戳为0的返回数据
 * 标识：
 *   1    Y-m-d H:i
 *   2    Y-m-d
 *   3    与当前时间月份：相同时 m月d日 H:i ；不相同时  Y年m月d日
 *   4    距离当前时间的时间差
 *   5    H:i:s
 *   支持自定义输出，如 $type='Y' 输出此时间年份。必须为date函数识别内容
 */
function dynamicTime($date = '', $type = 'normal', $ret = '---') {
    $typeIdent = [
        'normal'  => 1,
        'date'    => 2,
        'comment' => 3,
        'diff'    => 4,
        'time'    => 5,
    ];

    if (!(is_numeric($date) && 0 < $date)) {return $ret;}
    switch ($typeIdent[$type]) {
        case 1:
            return date('Y-m-d H:i', $date);
            break;
        case 2:
            return date('Y-m-d', $date);
            break;
        case 3:
            if (date('Y', $date) == date('Y', time())) {
                return date('m月d日 H:i', $date);
            } else {
                return date('Y年m月d日', $date);
            }
            break;
        case 4://返回距离当前时间差

            $_diff_time = array(
                'limit' => '100',
                'diff' => array(
                    'd' => array('diff' => 86400,'code' => '天前'),
                    'h' => array('diff' => 3600,'code' => '小时前'),
                    'i' => array('diff' => 60,'code' => '分钟前'),
                    's' => array('diff' => 1,'code' => '秒前'),
                ),
                'default' => array('diff' => 5,'code' => '刚刚'),
            );

            $timeDifference = $_SERVER['REQUEST_TIME']-$date;
            if($timeDifference < 0) {
                return $ret;
            }
            foreach ($_diff_time['diff'] as $timeKey => $timeValue) {
                $timeShow = intval($timeDifference / $timeValue['diff']);
                if(0 < $timeShow) {
                    if('d' == $timeKey && $_diff_time['limit'] < $timeShow) {
                        return date('Y-m-d', $date);
                    }
                    if('s' == $timeKey && $_diff_time['default']['diff'] >= $timeShow) {
                        return $_diff_time['default']['code'];
                    }
                    return $timeShow.$timeValue['code'];
                    break;
                }
            }
            return $_diff_time['default']['code'];
            // return intval($t/(3600*24)) != 0?intval($t/(3600*24)).'天前':(intval($t/3600) != 0?intval($t/3600).'小时前':(intval($t/60) != 0?intval($t/60).'分钟前':intval($t).'秒前'));
            break;
        case 5:// 返回此时间的  小时:分钟:秒
            return date('H:i:s', $date);
            break;
        default:
            return date($type, $date);
            break;
    }
}

/**
 * 去转义
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
function apiSlashes($str) {
    return stripslashes($str);
}

/**
 * html转译纯文字，去除标签及空格
 */
function htmlToText($detail = '') {
    $detail = filter_html($detail);
    $detail = mb_ereg_replace('^(　| )+', '', $detail); 
    $detail = mb_ereg_replace('(　| )+$', '', $detail); 
    $detail = mb_ereg_replace('　　', "\n　　", $detail); 
    return $detail;
}

/**
 * 生成二维码 需要第三方库phpqrcode
 */
function qrcode($content) {
    Vendor('phpqrcode.phpqrcode');
    $qr = new \QRcode();
    $qr->png($content, false, QR_ECLEVEL_H, 10, 2);
}

/**
 * true：获取毫秒表示时间字符串
 * 默认：毫秒时间戳
 * @return string
 */
function getMicrotime($boolean = false) {
    // $microtime = microtime();
    // $microtime = explode(' ', $microtime);
    // $mic  = substr($microtime[0], 2, 3);  //毫秒
    // if($boolean) {
    //  $time = date('His', $microtime[1]);  //时间
    // } else {
    //  $time = $microtime[1];
    // }
    $microtime = $_SERVER['REQUEST_TIME_FLOAT'];
    $microtime = explode('.', $microtime);
    $mic = $microtime[1];
    while(strlen($mic) < 4) {
        $mic = '0'.$mic;
    }
    if($boolean) {
        $time = data('His', $microtime[0]);
    } else {
        $time = $microtime[0];
    }
    return $time.$mic;
}

/**
 * 获取时间上下限时间戳数组，参数2为时间戳，默认为当前时间
 * 参数1： 1当天  2本月  3今年
 * timestring 最好传日期格式，传时间戳容易出问题
 */
function isTime($type = 1, $timeString = 0) {
    if($timeString === false) {
        return [
            "start" => false,
            "end"   => false
        ];
    }
    if(strtotime($timeString) === false) {
        if (!(is_numeric($timeString) && 0 < $timeString)) {$timeString = $_SERVER['REQUEST_TIME'];}
    } else {
        $timeString = strtotime($timeString);
    }
    $timeArray = array();
    switch ($type) {
        case 1://当天日期范围
            $timeArray['start'] = strtotime(date('Y-m-d 00:00:00', $timeString));
            $timeArray['end'] = $timeArray['start']+3600*24-1;
            break;
        case 2://当月日期范围
            $timeArray['start'] = strtotime(date('Y-m-01 00:00:00', $timeString));
            $timeArray['end'] = $timeArray['start']+3600*24*date('t', $timeString)-1;
            break;
        case 3://当年日期范围
            $timeArray['start'] = strtotime(date('Y-01-01 00:00:00', $timeString));
            $timeArray['end'] = $timeArray['start']+(!!date('L', $timeString)?366:365)*3600*24-1;
            break;
    }
    return $timeArray;
}

/**
 * 将中文1个文字当作1个字符， 返回字符个数
 */
function utf8_strlen($string = null) {
    // 将字符串分解为单元
    preg_match_all("/./us", $string, $match);
    // 返回单元个数
    return count($match[0]);
}

/**
* 将文字当作1个字符， 截取字符串
 *
 * @param null     $string  字符串
 * @param int      $start   开始位置
 * @param int      $length  截取长度
 * @param function $callback  返回结果处理，参数：处理之后+处理之前
 *
 * @return string  返回截取内容
 */
function utf8_substr($string = null, $start = 0, $length = 0, $callback = true) {
    preg_match_all("/./us", $string, $match);
    $count                  = count($match[0]);
    if ($start < 0) {$start = $count+($start%$count);}
    if ($length == 0) {$length = $count-1;}
    $str = '';
    do {
        $str .= $match[0][$start];
    } while ((--$length > 0) && ($start++ >= 0));
    if (!is_object($callback)) {
        $callback = function ($str, $string) {return $str;};
    }
    return $callback($str, $string);
}

/**
 * 使数组横竖位置颠倒
 * @param array $array
 * @return array
 */
function arrayZip($array = array()) {
    $result = array();
    foreach ($array as $key => $value) {
        if(is_array($value)) {
            foreach ($value as $k  => $v) {
                $result[$k][$key] = $v;
            }
        } else {
            foreach($result as $k=>$v) {
                $result[$k][$key] = $value;
            }
        }
    }
    return $result;
}

/**
 * 数组根据规则分组，参数2为规则函数，必传
 * 分组依据为规则函数的返回值，将此作为分组后的键名
 */
function arrayGroup($array = array(), $rule) {
    $result = array();
    foreach ($array as $key => $value) {
        $result[$rule($value, $key)][] = $value;
    }
    return $result;
}

/**
 * 将一数组整合到另一数组中(多条数据)，规则必传。
 * 当规则返回false时不添加，否则添加到对应键下
 * 规则如果是true，对应的键会全部添加进去
 * 规则如果是函数，支持两个参数：数组的键和键对应的数据。
 * 规则如果是函数，必须返回键对应数组，否则不会添加
 */
function arrayUnit($array, $temp, $rule) {
    
    foreach ($temp as $key => $value) {
        if(true === $rule) {
            $array[$key] = array_merge($array[$key], $value);
        } else {
            $unit = $rule($key, $value);
            if(false !== $unit && is_array($unit)) {
                foreach($unit as $k => $v) {
                    $array[$k] = array_merge($array[$k], $v);
                }
            }
        }
    }
    return $array;
}


function stime() {
    return $_SERVER['REQUEST_TIME'];
}

/**
 * 返回号码保护格式
 * @param  [type]  $ident 号码
 * @param  integer $type  1手机号  2身份证
 * @return [type]         [description]
 */
function protectedIdent($ident, $type = 1) {
    switch($type) {
        case 1: 
            $preg = '/(\d{2})[\d]*(\d{3})/';
            $plastic = '$1******$2';
        break;
        case 2: 
            $preg = '/(\d{3})[\d]*(\d{4})/';
            $plastic = '$1***********$2';
        break;
        default: 
            return false;
    }
    return preg_replace($preg,$plastic,$ident);
}

/**
 * 判断图像类型
 */
function getImageType($image) {
    if (function_exists('exif_imagetype')) {
        return exif_imagetype($image);
    } else {
        $info = getimagesize($image);
        return $info[2];
    }
}
function token($value, $rule, $data) {
    $rule = !empty($rule) ? $rule : '__token__';
    if (!isset($data[$rule]) || !Session::has($rule)) {
        // 令牌数据无效
        return false;
    }

    // 令牌验证
    if (isset($data[$rule]) && Session::get($rule) === $data[$rule]) {
        // 防止重复提交
        Session::delete($rule); // 验证完成销毁session
        return true;
    }
    // 开启TOKEN重置
    Session::delete($rule);
    return false;
}

/**
 * 获取真实ip地址
 * @return [type] [description]
 */
function getRealIp() { 
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
        $ip = getenv("HTTP_CLIENT_IP"); 
    else 
        if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
            $ip = getenv("HTTP_X_FORWARDED_FOR"); 
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
            $ip = getenv("REMOTE_ADDR"); 
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
            $ip = $_SERVER['REMOTE_ADDR']; 
        else 
            $ip = "unknown"; 
    return ($ip); 
}

/**
 * 生成一个可用的唯一码
 * @param integer $count 位数
 * @param string  $type  类型   NUM|UPPERNUM
 */
function setCode($count = 18, $type = 'NUM') {
    $charLibrary = [
        'uppercase' => "ABCDEFGHJKLMNPQRTUVWXY",
        'lowercase' => "abcdefghijkmnpqrtuvwxy",
        'number' => "1234567890",
    ];
    $useCharLibrary = '';
    switch($type) {
        case "NUM":
            $useCharLibrary .= $charLibrary['number'];
        break;
        case "UPPERNUM":
            $useCharLibrary .= $charLibrary['number'];
            $useCharLibrary .= $charLibrary['uppercase'];
        break;
    }
    $code = '';
    while($count--) {
        $code .= $useCharLibrary[rand(1, strlen($useCharLibrary)) - 1];
    }
    return $code;
}


/**
 * 时间格式 xx:xx 转为当天的时间秒数
 */
function hourtotime($hour) {
    if(!preg_match('/^\d{2}\:\d{2}$/', $hour)) {
        return 0;
    }
    $hour = explode(':', $hour);
    return ($hour[0] * 60 + $hour[1]) * 60;
}

/**
 * 当天时间秒数转为时间格式 xx:xx
 */
function timetohour($time) {
    $time = $time / 60;
    $minues = $time % 60;
    if(strlen($minues) < 2) {
        $minues = '0'. $minues;
    }
    $hour = (($time - $minues) / 60) % 24;
    if(strlen($hour) < 2) {
        $hour = '0'. $hour;
    }
    return $hour .':'. $minues;
}


/**
 * 是否为微信访问
 * @return boolean [description]
 */
function callOnWechat() {
    return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
}

/**
 * 检查当前设备是否为手机访问
 */
function callOnMobile() {
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';
    
    $mobile_os_list = ['Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ'];
    $mobile_token_list = ['Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod'];
    $found_mobile = checkSubstrs($mobile_os_list, $useragent_commentsblock) ||
    checkSubstrs($mobile_token_list, $useragent);
    $device = ['Android','iPhone','Windows','Mac'];
    preg_match('/'.implode('|', $device).'/', $useragent_commentsblock, $ua);
    if ($found_mobile) {
        // 当前使用手机
        return [
            'flag'          => true,
            'message'       => 'is mobile',
            'commentsblock' => $useragent_commentsblock,
            'specific'      => $ua[0],
        ];
    }
    // 当前使用电脑
    return [
        'flag'          => false,
        'message'       => 'is computer',
        'commentsblock' => $useragent_commentsblock,
        'specific'      => $ua[0],
    ];
}

/**
 * 检查一维数组中是否包含字符串
 */
function checkSubstrs($substrs,$text) {
    foreach($substrs as $substr) {
        if(false!==strpos($text,$substr)) {
            return true;
        }
    }
    return false;
}

/**
 * 上传图片
 */
// function uploadImg($savePath = 'head') {
//     return api('UpDownLoad/upload', array('save_path'=>$savePath));
// }

// function getFile($ids) {
//     return api('File/getFiles', $ids);
// }

/**
 * md5双层加密
 */
function _md5($str) {
    return md5(md5($str));
}

/**
 * 发送简单的get请求
 */
function getCurl($url, $getJson = true) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $file_contents = curl_exec($ch);
    curl_close($ch);

    // 请求失败
    if($file_contents === false) {
        return false;
    }
    if($getJson) {
        return json_decode($file_contents, true);
    }
    return $file_contents;
}

/**
 *  作用：将xml转为数组形式
 */
function xmlToArray($xml){
    return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}

/**
 * id队列去除无效字符串和空格 示例：1 ,2 ,3 ,4 ,5  => 1,2,3,4,5
 */
function trimIds($ids) {
    $ids = getKeysArray($ids);
    $res = [];
    foreach($ids as $item) {
        $item = trim($item);
        if($item === "") {
            continue;
        }
        $res[] = $item;
    }
    return implode(",",$res);
}

function headerJSON() {
    header("Content-Type:application/json;chatset=utf-8");
}

function headerHTML() {
    header("Content-Type:text/html;chatset=utf-8");
}

/**
 * 验证： hh:ii
 * @param  [type]  $time [description]
 * @return boolean       [description]
 */
function isHour($time) {
    if(!preg_match('/^\d{2}\:\d{2}$/', $time)) {
        return false;
    }
    $time = explode(':', $time);
    if($time[0] >= 24 || $time[1] >= 60) {
        return false;
    }
    return $time[0] * 3600 + $time[1] * 60;
}
function pauseHour($time) {
    if(!is_numeric($time)) {
        return '00:00';
    }
    $minues = intval($time / 60 % 60);
    $hour   = intval($time / 3600);
    
    if(strlen($minues) < 2) {
        $minues = '0'.$minues;
    }
    if(strlen($hour) < 2) {
        $hour = '0'.$hour;
    }
    return $hour.':'.$minues;
}

/**
 * 人性化显示米距离（返回带单位距离）
 * compatible: 是否兼容显示，true时如果距离错误，会返回0m
 * @return [type] [description]
 */
function meterToHm($meter, $compatible = false) {
    if(!is_numeric($meter)) {
        if($compatible) {
            return '0m';
        }
        return '---';
    }
    // 米转千米
    if($meter == ($meter % 1000)) {
        return round($meter, 1) . 'm';
    } else {
        return round($meter / 1000, 1) . 'km';
    }
}



////////////////
///////////// //
////////// // //
// 验证格式 // // //
////////// // //
///////////// //
////////////////

/**
 * 是否为身份证号
 */
function isIdCard($card_no) {
    if(empty($card_no)) return false;

    //权重
    $power = 0;

    //加权因子数组
    $power_arr = ["7","9","10","5","8","4","2","1","6","3","7","9","10","5","8","4","2"];

    //校验码数组
    $parity_arr = ["1","0","X","9","8","7","6","5","4","3","2"];
    
    //身份证号数组
    $card_no_arr = str_split($card_no);

    //身份证号长度
    $str_len = strlen($card_no);

    //省级行政区代码
    $province_code_arr = ['11', '12', '13', '14', '15', '21', '22', '23', '31', '32', '33', '34', '35', '36', '37', '41', '42', '43', '44', '45', '46', '50', '51', '52', '53', '54', '61', '62', '63', '64', '65', '71', '81', '82'];

    //获取身份证前两位
    $p_code = substr($card_no, 0, 2);
    if(!in_array($p_code, $province_code_arr)) {
        return false;
    }

    //获取身份证出生年份
    $card_year = substr($card_no, 6, 4);

    //当前年份
    $now_year = date('Y');

    //不能大于当前年份，并且相隔年份不能太大
    if($card_year > $now_year || intval($now_year - $card_year) > 200) {
        return false;
    }

    //获取身份证出生月份
    $card_month = substr($card_no, 10, 2);
    if(intval($card_month) > 12 || intval($card_month) < 1) {
        return false;
    }

    //获取身份证出生当月的天数
    $card_month_days = date('t', strtotime($card_year. '-' .$card_month));

    //获取身份证出生日期
    $card_date = intval(substr($card_no, 12, 2));
    if($card_date > $card_month_days || $card_date < 1) {
        return false;
    }

    //身份证性别代码
    $sex_code = substr($card_no, 14, 3);

    //循环身份证数组
    foreach ($card_no_arr as $key => $value) {
        if(($value < 0 || !is_numeric($value)) && $key < $str_len - 1) {
            return false;
        }
        
        //加权
        if($key < 17) {
            $power += intval($value) * intval($power_arr[$key]);   
        }
    }

    //取模（取余）
    $mod = intval($power) % 11;

    //验证身份证最后一位
    if($parity_arr[$mod] != $card_no_arr[$str_len - 1]) {
        return false;
    }

    return true;
}

/**
 * 判断是否存在上传的此文件域
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
// function isPhotos($str) {
//     return !empty($_FILES[$str]);
// }

/**
 * 验证日期格式： yyyy-mm-dd，包括是否为正确的日期
 * 必须为此格式，缺一不可
 * @param  [type]  $str [description]
 * @return boolean      [description]
 */
function isDateString($str) {
    if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $str)) {
        return false;
    }
    $str = explode('-',$str);
    if($str[1] < 1 || $str[1] > 12) {
        return false;
    }
    $dates = [
        "01" => "31", "02" => "28", "03" => "31", "04" => "30",
        "05" => "31", "06" => "30", "07" => "31", "08" => "31",
        "09" => "30", "10" => "31", "11" => "30", "12" => "31",
    ];
    if( $str[0] % 400 === 0
        || ( $str[0] % 4 === 0 && $str[0] % 100 !== 0 ) ) {
        $dates["2"]++;
    }
    if($dates[$str[1]] < $str[2] || $str[2] < 1) {
        return false;
    }
    return true;
}

/**
 * 验证数据是否为字符串，长度是否为规定长度
 * @param  [type]  $str         [description]
 * @param  boolean $lengthStart [description]
 * @param  boolean $lengthEnd   [description]
 * @return boolean              [description]
 */
function isString($str, $lengthStart = false, $lengthEnd = false) {
    if(!is_string($str)) {
        return false;
    }
    $strlen = strlen($str);
    if(is_numeric($lengthStart) && $strlen < $lengthStart) {
        return false;
    }
    if(is_numeric($lengthEnd) && $strlen > $lengthEnd) {
        return false;
    }
    return true;
}

/**
 * 是否为纯数字，判断传递是否为id
 * @param  [type]  $param       [description]
 * @param  integer $lengthStart [description]
 * @param  string  $lengthEnd   [description]
 * @return boolean              [description]
 */
function isIdent($param, $lengthStart = 1, $lengthEnd = '') {
    return !!preg_match('/^\d{'.$lengthStart.','.$lengthEnd.'}$/', $param);
}

/**
 * 检测是否满足规则，返回true false
 * @param  [type]  $value [description]
 * @param  [type]  $rule  [description]
 * @param  array   $data  [description]
 * @return boolean        [description]
 */
function is($value, $rule, $data = []) {
    switch ($rule) {
        case 'require':
            // 必须
            $result = !empty($value) || '0' == $value;
            break;
        case 'accepted':
            // 接受
            $result = in_array($value, ['1', 'on', 'yes']);
            break;
        case 'date':
            // 是否是一个有效日期
            $result = false !== strtotime($value);
            break;
        case 'alpha':
            // 只允许字母
            $result = regex($value, '/^[A-Za-z]+$/');
            break;
        case 'alphaNum':
            // 只允许字母和数字
            $result = regex($value, '/^[A-Za-z0-9]+$/');
            break;
        case 'alphaDash':
            // 只允许字母、数字和下划线 破折号
            $result = regex($value, '/^[A-Za-z0-9\-\_]+$/');
            break;
        case 'chs':
            // 只允许汉字
            $result = regex($value, '/^[\x{4e00}-\x{9fa5}]+$/u');
            break;
        case 'chsAlpha':
            // 只允许汉字、字母
            $result = regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z]+$/u');
            break;
        case 'chsAlphaNum':
            // 只允许汉字、字母和数字
            $result = regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]+$/u');
            break;
        case 'chsDash':
            // 只允许汉字、字母、数字和下划线_及破折号-
            $result = regex($value, '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9\_\-]+$/u');
            break;
        case 'activeUrl':
            // 是否为有效的网址
            $result = checkdnsrr($value);
            break;
        case 'ip':
            // 是否为IP地址
            $result = filter($value, [FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6]);
            break;
        case 'url':
            // 是否为一个URL地址
            $result = filter($value, FILTER_VALIDATE_URL);
            break;
        case 'float':
            // 是否为float
            $result = filter($value, FILTER_VALIDATE_FLOAT);
            break;
        case 'number':
            $result = is_numeric($value);
            break;
        case 'integer':
            // 是否为整型
            $result = filter($value, FILTER_VALIDATE_INT);
            break;
        case 'email':
            // 是否为邮箱地址
            $result = filter($value, FILTER_VALIDATE_EMAIL);
            break;
        case 'boolean':
            // 是否为布尔值
            $result = in_array($value, [true, false, 0, 1, '0', '1'], true);
            break;
        case 'array':
            // 是否为数组
            $result = is_array($value);
            break;
        case 'file':
            $result = $value instanceof File;
            break;
        case 'image':
            $result = $value instanceof File && in_array(getImageType($value->getRealPath()), [1, 2, 3, 6]);
            break;
        case 'token':
            $result = token($value, '__token__', $data);
            break;
        case 'id':
            // 单个ID
            $result = regex($value, '/^[1-9][0-9]*$/');
            break;
        case 'ids':
            // ID序列
            $result = regex($value, '/^[1-9][0-9]*(,[1-9][0-9]*)*$/');
            break;
        case 'mobile':
            // 手机号
            $result = regex($value, '/^0?(13[0-9]|15[012356789]|18[02356789]|14[57]|17[0-9])[0-9]{8}$/');
            break;
        case 'email':
            // 邮箱
            $result = regex($value, '/[\\w!#$%&\'*+/=?^_`{|}~-]+(?:\\.[\\w!#$%&\'*+/=?^_`{|}~-]+)*@(?:[\\w](?:[\\w-]*[\\w])?\\.)+[\\w](?:[\\w-]*[\\w])?/');
            break;
        case 'qq':
            // qq
            $result = regex($value, '/^\d{5,10}$/');
            break;
        case 'password':
            // 登录密码
            $result = regex($value, '/^(?=.*\\d)(?=.*[a-zA-Z]).{6,16}$/');
            break;
        case 'price':
            // 价格数字字符串，可以为负
            $result = regex($value, '/^(\-)?\d+(\.\d{0,2})?$/');
            break;
        default:
            // 正则验证
            $result = regex($value, $rule);
    }
    return $result;
}

function regex($name, $rule) {
    if (0 !== strpos($rule, '/') && !preg_match('/\/[imsU]{0,4}$/', $rule)) {
        // 不是正则表达式则两端补上/
        $rule = '/^' . $rule . '$/';
    }
    return 1 === preg_match($rule, (string) $name);
}

function filter($value, $rule) {
    if (is_string($rule) && strpos($rule, ',')) {
        list($rule, $param) = explode(',', $rule);
    } elseif (is_array($rule)) {
        $param = isset($rule[1]) ? $rule[1] : null;
        $rule  = $rule[0];
    } else {
        $param = null;
    }
    return false !== filter_var($value, is_int($rule) ? $rule : filter_id($rule), $param);
}


/**
 * 增加调试参数数组
 */
// function debugger($key, $value) {
//     $GLOBALS["debugger"][$key] = $value;
// }

// api使用

// function out($errmsg = "app error", $errcode = '00', $params = [], $outtype = "JSON", $errtype = "NOTICE") {
//     headerJSON();
//     $etype = [
//         "NOTICE"  => "E_USER_NOTICE",
//         "WARNING" => "E_USER_WARNING",
//         "ERROR"   => "E_USER_ERROR"
//     ];
//     trigger_error($errmsg);
// }

// set_error_handler("enter");

/**
 * 输出错误信息数据为对应可接受的接口形式
 * @param  [type] $errno   错误等级
 * @param  [type] $errstr  输出错误字符串
 * @param  [type] $errfile 报错文件
 * @param  [type] $errline 报错行数
 * @param  array  $params  附加参数，此参数为调用out函数内的所有可用变量数组
 *     必须：
 *       $etype 可用错误类型数组，固定
 *       $errcode 接口错误码
 *       $outtype 接口抛出形式，默认为JSON
 * @return [type]          无返回数据，直接输出接口信息
 */
// function enter($errno, $errstr, $errfile, $errline, $params = []) {
//     // 除了主动使用trigger_error抛出的错误，其他错误不出json
//     if(in_array($errno, [256,512,1024]) === false) {
//         if(in_array($errno, [1]) === false) {
//             return true;
//         }
//         return false;
//     }
    
//     $errcode = isset($params["errcode"]) ? $params["errcode"] : "00";
//     $ret = [
//         "flag" => "error",
//         "message" => $errstr,
//         "errcode" => err($errcode),
//         // "errline" => $errline,
//         // "errfile" => $errfile,
//         "data" => $params["params"] ?: json_decode("{}"),
//         "query" => [
//             "get" => I("get."),
//             "post" => I("post.")
//         ]
//     ];
//     if(!empty($GLOBALS["debugger"])) {
//         $ret["debugger"] = $GLOBALS["debugger"];
//     }
//     print json_encode($ret); exit;
// }

/**
 * 16位错误码
 *
 * 必须配置静态函数 errAuth，该函数直接返回数组：
 *
 * function errAuth() {
 *     return [
 *         "模块名" => [
 *             "类名" => "错误码(建议4位)"
 *         ],
 *         // 例如
 *         "Api" => [
 *             "User" => "1001"
 *         ],
 *         "ApiMch" => [
 *             "User" => "2001"
 *         ],
 *         // 预置错误码，可选
 *         "_set" => [
 *             "cart" => "22A", // 配置后， err('cart') 相当于 err('22A')
 *         ]
 *     ];
 * }
 */
// function err($code, $showAction = false) {

//     $showAction = !!$showAction ? ':'.ACTION_NAME : '';

//     $length = 2;  // 传递位数  0101901

//     if(!function_exists("errAuth")) {
//         E("未配置errAuth函数，请按照err说明配置静态函数errAuth");
//     }

//     $errAuth = errAuth();
//     if(!is_array($errAuth)) {
//         E("errAuth配置错误，检查是否返回内容为数组");
//     }

//     // 在此数组内的模块才能显示后面的内容
//     $used = array_keys($errAuth);
//     if(in_array(MODULE_NAME, $used) === false) {
//         return $code;
//     }

//     // 规定： F开头是系统预设，不能使用到自定义中

//     $predefined = $errAuth["_set"] ?: [];
//     if(isset($predefined[$code])) {
//         $code = $predefined[$code];
//     }

//     // 16禁止数字字符串
//     if(+$code != strtoupper(dechex(intval($code, 16)))) {
//         E("错误码包含无效字符:{$code}");
//     }

//     if(strlen($code) > 3) {
//         E("错误码不能超过3位:{$code}");
//     }

//     while(strlen($code) < $length - 1) {
//         $code = '0' . $code;
//     }

//     if(!isset($errAuth[MODULE_NAME]) || !isset($errAuth[MODULE_NAME][CONTROLLER_NAME])) {
//         E("缺少errAuth配置子项，请检查是否包含".MODULE_NAME.".".CONTROLLER_NAME."的配置错误码");
//     }

//     $code = $errAuth[MODULE_NAME][CONTROLLER_NAME].$code;

//     if(strlen($code) < 7) {
//         $code .= "1";
//     }

//     return $code.$showAction;
    
// }