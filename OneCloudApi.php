<?php
class OneCloudApi {
    private $key;
    private $timeout;
    public $port = 443;

    public function __construct($key, $timeout = 1) {
        $this->key = $key;
        $this->timeout = $timeout;
    }

    public function getImagesList() {
        return $this->apiCall(array('image'));
    }

    public function getServersList() {
        return $this->apiCall(array('server'));
    }

    public function getServerInfo($id) {
        return $this->apiCall(array('server', $id));
    }

    public function createServer($name, $cpu, $ram, $hdd, $imageId) {
        return $this->apiCall(array('server'), 'POST', array(
            'Name' => $name,
            'CPU' => $cpu,
            'RAM' => $ram,
            'HDD' => $hdd,
            'ImageID' => $imageId,
        ));
    }

    public function changeServer($id, $cpu, $ram, $hdd) {
        return $this->apiCall(array('server', $id), 'PUT', array(
            'CPU' => $cpu,
            'RAM' => $ram,
            'HDD' => $hdd,
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
            }
            $i++;
        }
        fclose($sock);
        return json_decode($response);
    }

}
