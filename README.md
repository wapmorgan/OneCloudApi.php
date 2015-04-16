# OneCloudApi.php
API client for OneCloud
# Usage
```php
$api = new OneCloudApi('secret-key-here', 2 /*adjust second parameter if script prints errors about socket timeout; by default there 1 sec*/);

// Images List
$imagesList = $api->getImagesList();

// Create Image from a server
$image = $api->createImage('Image name', 'imageIdentificator', /*server id*/12345);

// Delete image
$image = $api->deleteImage(12);

// Servers List
$serversList = $api->getServersList();

// Server info
$serverInfo = $api->getServerInfo(12345);

// Create server
$serverInfo = $api->createServer('Server name', /*cpu*/ 2, /*ram*/ 512, /*hdd*/ 10, /*image id*/7, /*disk type*/'SAS', /*high performance node*/false);

// Change server configuration
$serverInfo = $api->changeServer(12345, /*cpu*/ 2, /*ram*/ 1024, /*hdd*/ 20, /*disk type*/'SAS', /*high performance node*/false);

// Delete server
$api->deleteServer(12345);

// Turn on server
$api->turnOnServer(12345);

// Turn off server
$api->turnOffServer(12345);

// Reboot server
$api->rebootServer(12345);

// Server operations history
$serverOperations = $api->getServerOperations(12345);

// Server operation info
$serverOperation = $api->getServerOperation(12345, 301234);

// Create network
$network = $api->createNetwork('Name');

// Add server in network
$api->addServerToNetwork(12345, 12);

// Remove server from network
$api->removeServerFromNetwork(12345, 12);

// Network info
$network = $api->getNetworkInfo(12);

// Delete network
$api->deleteNetwork(12);

```

# API changes

### 2015-04-16: 1.1

* new createImage(), deleteImage() actions
* new options $hddType and $isHighPerformance in createServer() and changeServer()
* new addServerToNetwork(), removeServerFromNetwork() methods

# API documentation
https://1cloud.ru/api
