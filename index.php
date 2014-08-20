<?PHP
// set envs
include 'common/conf.ini.php';
error_reporting(FALSE);

// validate params
$instruction = trim($_GET['instruction']) ? : output('无效的指令', TRUE);
$deviceId = trim($_GET['deviceId']) ? : output('无效的设备ID', TRUE);
$deviceTime = (int) $_GET['deviceTime'] ? : output('无效的设备时间', TRUE);
$latitude = trim($_GET['latitude']);
$longitude = trim($_GET['longitude']);

// dispatch
$isErr = TRUE;
$msg = 'Hey! 我家有只小毛驴呀我从来也不骑~';

foreach ($dicts as $item) {
    foreach ($item['key'] as $key) {
        if (FALSE === strpos($instruction, $key)) continue 2;
    }

    if ( ! include('app/' . $item['app'] . '.class.php')) break;
    try {
        $msg = $item['app']::$item['mod']($item['val'] ? : str_replace($item['key'], '', $instruction));
        $isErr = FALSE;
        break;
    } catch (Exception $e) {
        $msg = $e->getMessage();
    }
}

output($msg, $isErr);
