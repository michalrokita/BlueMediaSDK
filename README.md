# BlueMediaSDK (unofficial)
[![Build Status](https://img.shields.io/travis/michalrokita/BlueMediaSDK.svg?style=for-the-badge&logo=travis)](https://travis-ci.org/michalrokita/BlueMediaSDK)
[![PHP Version](https://img.shields.io/packagist/php-v/michalrokita/blue-media-sdk.svg?style=for-the-badge)](https://github.com/michalrokita/BlueMediaSDK)

Przy ostatnim projekcie, klient poprosił mnie o zaimplementowanie płatności BlueMedia w naszej platformie opartej o PHP. Z uwagi na to, że powyższa firma oferuje tylko SDK na platformy mobilne stwierdziłem, że sam napiszę proste SDK, które będzie pokrywać dokumentację BlueMedia API.

| Funkcjonalność  | Dostępna |
|--|:--:|
| Odbiór notyfikacji ITN / IPN / ISTN | ✅ |
|Potwierdzenie otrzymania ITN / IPN / ISTN | ✅ |
|Utworzenie linku do sesji płatniczej bez koszyka|✅|
|Utworzenie sesji płatniczej z koszykiem|❌|
|Obsługa przekierowania z Blue Media|✅|
|Odpytanie BM o listę kanałów płatności|✅|

## Instalacja (Composer)
```
  composer require michalrokita/blue-media-sdk:dev-master
```

## Dokumentacja

### 1. Konfiguracja BMService
```php
  $serviceUrl = 'https://valid-url.com';
  $serviceId = 'test';
  $sharedKey = 'test';
  
  $bmService = BMFactory::build($url, $serviceId, $sharedKey);
```

### 2. Odbiór notyfikacji ITN / IPN / ISTN
Notyfikacja ITN zawiera serviceID, parametry transakcji oraz hash. Metoda getNotification weryfikuje hash notyfikacji i jeżeli jest prawidłowy to zwraca **tylko** parametry transakcji, jako tablice. W celu prawidłowego przebiegu całej operacji, Blue Media oczekuje odpowiedzi z odpowiednim XMLem (patrz pkt 2.1).
```php
  $notification = $bmService->receiver()->getNotification();
```

### 2.1 Potwierdzenie otrzymania notyfikacji ITN / IPN / ISTN
Po tym jak już przeprowadzimy odpowiednie operacje w kodzie na podstawie danych z notyfikacji, musimy powiadomić Blue Media, że otrzymaliśmy wiadomość. W tym celu trzeba wywołać poniższy kod:
```php
  $bmService->receiver()->confirmReceivingNotification();
```
Metoda ta sporządzi odpowiednio spreparowany kod XML biorąc pod uwagę wcześniej odebraną notyfikację, a nastepnie zwróci go z odpowiednim nagłówkiem komendą `echo`. Z tego też powodu nie należy ustalać nagłówka samemu.

### 3. Generowanie linku do sesji płatniczej bez koszyka
Na początku należy zainicjalizować nową instancje klasy Transaction, która przetrzymuje dane na temat zawieranej transakcji. Przy tworzeniu instancji wymagane są tylko parametry niezbędne do utworzenia sesji wg specyfikacji Blue Media. Opcjonalne parametry można dodać do instancji wykorzystując wbudowane setter-y.

Jakoże Blue Media przyjmuję kwotę jako float skonwertowany na stringa, to do konstruktora Transaction należy podać float-a. Nie będziemy wykonywać żadnych operacji na tej liczbie ani też nie potrzebujemy dokładności, dlatego możemy sobie pozwolić na float.
```php
  $transaction = new Transaction('orderID', 100.00);
```
*Pamiętaj o przestrzeni nazw:* `michalrokita\BlueMediaSDK\Transactions`
--> `use michalrokita\BlueMediaSDK\Transactions\Transaction;`

Następnie, aby wygenerować link należy wywołać poniższy kod:
```php
  $paymentLink = $bmService->payment()->generatePaymentLink($transaction);
```

### 4. Obsługa przekierowania użytkownika z Blue Media
W celu pobrania danych o zamówieniu i automatycznej weryfikacji hasha w tle wywołaj poniższy kod. W przypadku błędu autoryzacji hasha metoda zwróci wyjątek.
```php
  $serviceId = $bmService->callback()->getServiceId();
  $orderId = $bmService->callback()->getOrderId();
  $amount = $bmService->callback()->getAmount();
```
