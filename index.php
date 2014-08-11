<?PHP
/**
 * Index
 */

// set envs
include 'common/conf.ini.php';
error_reporting(FALSE);

// validate params
$instruction = trim($_GET['instruction']) ? : output('无效的指令', TRUE);
$deviceId = trim($_GET['deviceId']) ? : output('无效的设备ID', TRUE);
$deviceTime = (int) $_GET['deviceTime'] ? : output('无效的设备时间', TRUE);
$latitude = trim($_GET['latitude']);
$longitude = trim($_GET['longitude']);

// output data
$request = compact('deviceId', 'deviceTime', 'instruction', 'latitude', 'longitude');
$response = '';
foreach ($request as $k => $v) {
    $response .= $k . ': ' . $v . PHP_EOL;
}
output($response);
