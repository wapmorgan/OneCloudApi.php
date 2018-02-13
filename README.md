# OneCloudApi.php
API client for OneCloud.

[![Composer package](http://composer.network/badge/wapmorgan/onecloud-api)](https://packagist.org/packages/wapmorgan/onecloud-api)
[![Latest Stable Version](https://poser.pugx.org/wapmorgan/onecloud-api/v/stable)](https://packagist.org/packages/wapmorgan/onecloud-api)
[![Total Downloads](https://poser.pugx.org/wapmorgan/onecloud-api/downloads)](https://packagist.org/packages/wapmorgan/onecloud-api)
[![License](https://poser.pugx.org/wapmorgan/onecloud-api/license)](https://packagist.org/packages/wapmorgan/onecloud-api)

# API

- `constructor OneCloudApi($secret_key[, $timeout = 1])`

   Adjust second parameter if script prints errors about socket timeout; by default there 1 sec;

### Images

- `getImagesList(): array`

  Returns images list
- `createImage($imageName, $imageIdentificator, $serverId): boolean`

  Create Image from a server
- `deleteImage($imageIdentificator)`

  Delete image
  
### Servers

- `getServersList(): array`

  Servers List
- `getServerInfo($serverId): array`

  Server info
- `createServer($serverName, $cpuCount, $ram, $hdd, $imageIdentificator, $diskType /*SAS or SSD*/, $isHighPerformance /* true or false */): array`

  Create server
- `changeServer($serverId, $cpuCount, $ram, $hdd, $diskType, $isHighPerformance): array`

  Change server configuration
- `deleteServer($serverId): boolean`

  Delete server
- `turnOnServer($serverId): boolean`, `turnOffServer($serverId): boolean`, `rebootServer($serverId): boolean`

  Turn on server, Turn off server, Reboot server
- `getServerOperations($serverId): array`

  Server operations history
- `getServerOperation($serverId, $operationId): array`

  Server operation info
- `createNetwork($networkName): array`

  Create network
- `addServerToNetwork($serverId, $networkId): boolean`

  Add server in network
- `removeServerFromNetwork($serverId, $networkId): boolean`

  Remove server from network
- `getNetworkInfo($networkId): array`

  Network info
- `deleteNetwork($networkId): boolean`

  Delete network

# API changes

### 2015-04-16: 1.1

* new createImage(), deleteImage() actions
* new options $hddType and $isHighPerformance in createServer() and changeServer()
* new addServerToNetwork(), removeServerFromNetwork() methods

# API documentation
https://1cloud.ru/api
