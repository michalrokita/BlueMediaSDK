# BlueMediaSDK (unofficial)
[![Build Status](https://img.shields.io/travis/michalrokita/BlueMediaSDK.svg?style=for-the-badge&logo=travis)](https://travis-ci.org/michalrokita/BlueMediaSDK)
[![PHP Version](https://img.shields.io/packagist/php-v/michalrokita/blue-media-sdk.svg?style=for-the-badge)](https://github.com/michalrokita/BlueMediaSDK)

Przy ostatnim projekcie, klient poprosił mnie o zaimplementowanie płatności BlueMedia w naszej platformie opartej o PHP. Z uwagi na to, że powyższa firma oferuje tylko SDK na platformy mobilne stwierdziłem, że sam napiszę proste SDK, które będzie pokrywać dokumentację BlueMedia API.

| Funkcjonalność  | Dostępna |
|--|:--:|
| Odbiór notyfikacji ITN | ✅ |
|Potwierdzenie otrzymania ITN| ✅ |
| Odbiór notyfikacji ISTN | ✅ |
|Potwierdzenie otrzymania ISTN| ✅ |
|Utworzenie sesji płatniczej bez koszyka|❌|
|Utworzenie sesji płatniczej z koszykiem|❌|
|Obsługa przekierowania z Blue Media|❌|
|Utworzenie sesji płatniczej z koszykiem|❌|
|Odpytanie BM o listę kanałów płatności|❌|

## Dokumentacja

### 1. Konfiguracja BMService
```php
  $serviceUrl = 'https://valid-url.com';
  $serviceId = 'test';
  $sharedKey = 'test';
  
  $bmService = BMFactory::build($url, $serviceId, $sharedKey);
```

### 2. Odbiór notyfikacji ITN
Notyfikacja ITN zawiera serviceID, parametry transakcji oraz hash. Metoda getNotification weryfikuje hash notyfikacji i jeżeli jest prawidłowy to zwraca **tylko** parametry transakcji, jako tablice. W celu prawidłowego przebiegu całej operacji, Blue Media oczekuje odpowiedzi z odpowiednim XMLem (patrz pkt 2.1).
```php
  $notification = $bmService->receiver()->getNotification();
```

### 2.1 Potwierdzenie otrzymania notyfikacji ITN
Po tym jak już przeprowadzimy odpowiednie operacje w kodzie na podstawie danych z notyfikacji, musimy powiadomić Blue Media, że otrzymaliśmy wiadomość. W tym celu trzeba wywołać poniższy kod:
```php
  $bmService->receiver()->confirmReceivingNotification();
```
Metoda ta sporządzi odpowiednio spreparowany kod XML biorąc pod uwagę wcześniej odebraną notyfikację, a nastepnie zwróci go z odpowiednim nagłówkiem komendą `echo`. Z tego też powodu nie należy ustalać nagłówka samemu.
