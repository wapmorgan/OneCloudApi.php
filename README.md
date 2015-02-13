# OneCloudApi.php
API client for OneCloud
# Usage
```php
$api = new OneCloudApi('secret-key-here', 2 /*adjust second parameter if script prints errors about socket timeout; by default there 1 sec*/);

// Images List
$imagesList = $api->getImagesList();

// Servers List
$serversList = $api->getServersList();

// Server info
$serverInfo = $api->getServerInfo(12345);

// Create server
$serverInfo = $api->createServer('Server name', /*cpu*/ 2, /*ram*/ 512, /*hdd*/ 10, /*image id*/7);

// Change server configuration
$serverInfo = $api->changeServer(12345, /*cpu*/ 2, /*ram*/ 1024, /*hdd*/ 20);

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
```

# API documentation
https://1cloud.ru/api
