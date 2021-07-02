# Backend

Wykorzystane technologie: 
* PHP 7.4
* Symfony 5 
* MySQL

Wykorzystane zewnętrzne biblioteki: 
* [Tom Select](https://github.com/orchidjs/tom-select)

## Wymagania

* PHP >= 7.4
* MySQL
* Symfony

## Instalacja

1. Sklonowanie repozytorium
```
git clone git@github.com:wojtekogieglo/symfony-referee-system.git
```

2. Przejście do katalogu z projektem i zmodyfikowanie pliku .env w celu połączenia z serwerem bazy danych.
3. Instalacja zależności poprzez wydanie następującego polecenia w konsoli
```
composer install
```
4. Utworzenie bazy danych
```
php bin/console doctrine:database:create
```
5. Utworzenie schematu bazy danych
```
php bin/console doctrine:migrations:migrate
```
6. Uruchomienie serwera 
```
symfony server:start
```

Aplikacja jest domyślnie dostępna pod adresem [127.0.0.1:8000](http://127.0.0.1:8000).

## Funkcjonalności

W systemie istnieją 3 moduły:
* User
* Product
* Like

### User

W module User można dodawać nowe osoby, edytować je, wyświetlić wszystkie (razem z filtrowaniem) oraz usunąć. 
Usunięcie osoby nie powoduje usunięcia jej z bazy danych tylko zmiany jej statusu z aktywny/zbanowany na usunięty. 
Operacje można odwrócić aktywując daną osobę - zmieniając jej status na aktywny.

### Product

W module Product można dodawać nowe produkty, edytować je, wyświetlić wszystkie (razem z filtrowaniem) oraz usunąć.
Usunięcie pojedyńczego produktu spowoduje całkowite usunięcie go z bazy danych razem ze wszystkimi powiązaniami.

### Like

W module like możemy dodać osoby, które lubią produkt. Każda osoba może lubić wiele produktów. 
Możemy dodawać lajki, edytować, usuwać oraz wyświetlać wszystkie (brak filtrowania).

