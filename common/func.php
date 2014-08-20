<?PHP
/**
 * Common function
 */

/**
 * cn2num
 * 汉字数字转阿拉伯数字
 *
 * @param string $str
 * @return float
 */
function cn2num($str) {
    $dicts = array(
        '零' => '0',
        '一' => '1',
        '二' => '2',
        '三' => '3',
        '四' => '4',
        '五' => '5',
        '六' => '6',
        '七' => '7',
        '八' => '8',
        '九' => '9',
        '十' => '',
        '百' => '00',
        '点' => '.',
    );

    if (is_numeric($str)) {
        return (float) $str;
    }

    $newStr = '';
    for ($i=0, $n=strlen($str); $i<$n; ++$i) {
        if (ord($str[$i]) < 128) {
            $char = $str[$i];
        } else {
            $char = substr($str, $i, 3);
            $i += 2;
        }
        $newStr .= isset($dicts[$char]) ? $dicts[$char] : $char;
    }
    return (float) $newStr;
}

/**
 * parse_dict
 * 分析指令字典配置
 *
 * @param string $path
 * @return array
 */
function parse_dict($path) {
    // read dictionary contents
    $str = file_get_contents($path);
    $arr = explode(PHP_EOL, $str);
    $contents = array();
    foreach ($arr as $line) {
        if (empty($line)) continue;
        $tmp = explode('=', $line);
        $contents[] = array('key' => trim($tmp[0]), 'route' => trim($tmp[1]));
    }

    // parse text
    /// processing logic
    foreach ($contents as $i => &$item) {
        //// eg: music|core.stream:play
        if (FALSE !== strpos($item['route'], '|')) {
            $routes = explode('|', $item['route']);
            foreach ($routes as $v) {
                $contents[] = array('key' => $item['key'], 'route' => $v);
            }
            unset($contents[$i]);
            continue;
        }

        //// eg: 关闭|停止
        if (FALSE !== strpos($item['key'], '|')) {
            $keys = explode('|', $item['key']);
            foreach ($keys as $v) {
                $contents[] = array('key' => $v, 'route' => $item['route']);
            }
            unset($contents[$i]);
            continue;
        }

        //// eg: 天气&今天
        if (FALSE !== strpos($item['key'], '&')) {
            $keys = str_replace('&', ',', $item['key']);
            $contents[] = array('key' => $keys, 'route' => $item['route']);
            unset($contents[$i]);
            continue;
        }
    }

    /// processing route
    $dicts = array();
    foreach ($contents as $i => $item) {
        if (FALSE !== strpos($item['key'], ',')) {
            $dicts[$i]['key'] = explode(',', $item['key']);
        } else {
            $dicts[$i]['key'] = array($item['key']);
        }
        
        preg_match('/([a-z]+)\.?([a-z]+)?:?([a-z]+)?/', $item['route'], $matches);
        if ( ! empty($matches)) {
            $dicts[$i]['app'] = $matches[1];
            $dicts[$i]['mod'] = isset($matches[2]) ? $matches[2] : 'main';
            $dicts[$i]['val'] = isset($matches[3]) ? $matches[3] : NULL;
        }
    }

    return $dicts;
}

/**
 * output
 * JSON输出
 *
 * @param string $msg
 * @param bool $isError
 * @return void
 */
function output($msg, $isError = FALSE) {
    $response = array('error' => '', 'message' => '');
    if ($isError) {
        $response['error'] = $msg;
    } else {
        $response['message'] = $msg;
    }
    echo json_encode($response);
    exit;
}
