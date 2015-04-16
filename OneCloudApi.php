<?php
class OneCloudApi {
    const CPU_1 = 1;
    const CPU_2 = 2;
    const CPU_3 = 3;
    const CPU_4 = 4;
    const CPU_5 = 5;
    const CPU_6 = 6;
    const CPU_7 = 7;
    const CPU_8 = 8;

    const RAM_512M = 512;
    const RAM_1024 = 1024;
    const RAM_2048 = 2048;
    const RAM_3072 = 3072;
    const RAM_4096 = 4096;
    const RAM_5120 = 5120;
    const RAM_6144 = 6144;
    const RAM_7168 = 7168;
    const RAM_8192 = 8192;
    const RAM_9216 = 9216;
    const RAM_10240 = 10240;
    const RAM_11264 = 11264;
    const RAM_12288 = 12288;
    const RAM_13312 = 13312;
    const RAM_14336 = 14336;
    const RAM_15360 = 15360;
    const RAM_16384 = 16384;

    private $key;
    private $timeout;
    public $port = 443;

    public function __construct($key, $timeout = 5) {
        $this->key = $key;
        $this->timeout = $timeout;
    }

    public function getImagesList() {
        return $this->apiCall(array('image'));
    }

    public function createImage($name, $techName, $serverId) {
        return $this->apiCall(array('image'), 'POST', array(
            'Name' => $name,
            'TechName' => $techName,
            'ServerID' => $serverId,
        ));
    }

    public function deleteImage($id) {
        return $this->apiCall(array('image', $id), 'DELETE');
    }

    public function getNetworksList() {
        return $this->apiCall(array('network'));
    }

    public function getNetworkInfo($id) {
        return $this->apiCall(array('network', $id));
    }

    public function createNetwork($name) {
        return $this->apiCall(array('network'), 'POST', array(
            'Name' => $name,
        ));
    }

    public function deleteNetwork($id) {
        return $this->apiCall(array('network', $id), 'DELETE');
    }

    public function getServersList() {
        return $this->apiCall(array('server'));
    }

    public function getServerInfo($id) {
        return $this->apiCall(array('server', $id));
    }

    public function createServer($name, $cpu, $ram, $hdd, $imageId, $hddType = 'SAS', $isHighPerformance = false) {
        return $this->apiCall(array('server'), 'POST', array(
            'Name' => $name,
            'CPU' => $cpu,
            'RAM' => $ram,
            'HDD' => $hdd,
            'ImageID' => $imageId,
            'HDDType' => $hddType,
            'isHighPerformance' => $isHighPerformance,
        ));
    }

    public function changeServer($id, $cpu, $ram, $hdd, $hddType = 'SAS', $isHighPerformance = false) {
        return $this->apiCall(array('server', $id), 'PUT', array(
            'CPU' => $cpu,
            'RAM' => $ram,
            'HDD' => $hdd,
            'HDDType' => $hddType,
            'isHighPerformance' => $isHighPerformance,
        ));
    }

    public function deleteServer($id) {
        return $this->apiCall(array('server', $id), 'DELETE');
    }

    public function turnOnServer($id) {
        return $this->apiCall(array('server', $id, 'action'), 'POST', array(
            'Type' => 'PowerOn',
        ));
    }

    public function turnOffServer($id) {
        return $this->apiCall(array('server', $id, 'action'), 'POST', array(
            'Type' => 'PowerOff',
        ));
    }

    public function rebootServer($id) {
        return $this->apiCall(array('server', $id, 'action'), 'POST', array(
            'Type' => 'PowerReboot',
        ));
    }

    public function addServerToNetwork($id, $networkId) {
        return $this->apiCall(array('server', $id, 'action'), 'POST', array(
            'Type' => 'AddNetwork',
            'NetworkID' => $networkId,
        ));
    }

    public function removeServerFromNetwork($id, $networkId) {
        return $this->apiCall(array('server', $id, 'action'), 'POST', array(
            'Type' => 'RemoveNetwork',
            'NetworkID' => $networkId,
        ));
    }

    public function getServerOperations($id) {
        return $this->apiCall(array('server', $id, 'action'));
    }

    public function getServerOperation($id, $actionId) {
        return $this->apiCall(array('server', $id, 'action', $actionId));
    }

    protected function apiCall(array $path, $method = 'GET', array $data = array()) {
        if (($sock = fsockopen('ssl://api.1cloud.ru', $this->port, $errno, $errstr, 3)) === false)
        {
            throw new Exception($errstr, $errno);
        }
        stream_set_timeout($sock, $this->timeout);
        fwrite($sock, $method.' /'.implode('/', $path).' HTTP/1.1'."\r\n".
                      'Content-Type: application/json'."\n".
                      'Authorization: Bearer '.$this->key."\n".
                      'Host: api.1cloud.ru'."\n".
                      'User-Agent: OneCloudApi.php'."\n".
                      'Accept: */*'."\n");
        if (!empty($data)) {
            $data = json_encode($data);
            fwrite($sock, 'Content-Length: '.strlen($data)."\n".
                          "\n".
                          $data);
        } else {
            fwrite($sock, "\n");
        }
        $response = null;
        $microtime = microtime(true);
        $length = 0;
        $i = 0;
        while (!feof($sock) && ((microtime(true) - $microtime) < $this->timeout)) {
            $response[$i] = fgets($sock);
            if (trim($response[$i]) == '') {
                $response = fread($sock, $length);
                break;
            }
            if (strcasecmp(strstr($response[$i], ':', true), 'Content-Length') === 0) {
                $length = trim(strstr($response[$i], ':'), ":\r\n");
                if ($length == 0)
                    break;
            }
            $i++;
        }
        fclose($sock);
        return is_string($response) ? json_decode($response) : null;
    }
}
