<?PHP
/**
 * Common function
 */

/**
 * todo
 * parse_dict
 * 分析指令字典配置
 *
 * @param string $inistr
 * @return array
 */
function parse_dict($inistr) {
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
