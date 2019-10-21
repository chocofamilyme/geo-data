# Бибилиотека для генерации географических данных по IP адресу

## Конфигурации

Сервис гео-данных принимает объект конфигурации как один из аргументов. Этот объект должен содержать в себе следующие
 параметры:

| Ключ              | Значение              | Описание  |
|-------------------|:----------------------|:----------|
| cache_prefix_key  | По умолчанию `geo_`   | Префикс для ключа кэша |
| cache_time        | По умолчанию 259200 (3 дня) | Время жизни кэша |

Кэш используется чтобы не было лишних запросов для получения данных по IP, так как эти данные постоянные.

## Применение
Гео-данные можно получить следующим образом:
```php
...
$geoDataService = new GeoDataService($this->config, $this->cache);
$geoData        = $geoDataService->getGeoData($ipAddress);

$country = $geoData['country'];
$city    = $geoData['city'];
$region  = $geoData['region'];
$lat     = $geoData['lat'];
$lon     = $geoData['lon'];
...
```

Если вы не хотите чтобы возвращались все данные, можете указать нужные вам ключи в виде массива как второй аргумент 
функции `getGeoData()`:

```php
...
$geoDataService = new GeoDataService($this->config, $this->cache);
$filter         = ['country', 'city'];
$geoData        = $geoDataService->getGeoData($ipAddress, $filter);

// $geoData будет содержать только ключи country и city
$authHistoryModel->save($geoData);
...
```
