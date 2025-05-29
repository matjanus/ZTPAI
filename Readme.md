Aplikacja ma za zadanie dać użytkownikowi możliwość tworzenia własnych gier typu fiszkido do nauki języka.
Z racji konstrukcji strony trywialnym jest dodanie większej ilości gier, ale na moment oddania są tylko fiszki.
Strona pozwala w łatwy sposób tworzyć własne zestawy słów, a potem używać ich do ćwiczenia przez samego siebie jak i przez innych,
gdyż możliwym jest udostępnienie danej fiszki innym użytkownikom, gdy jest ona publiczna lub protected.


Aby uruchomić cały program na początku należy uruchomić serwer Symfony, który jest odpoalany z poziomu urządzenia, spowodowane jest to tym,
iż niestety serwer Symfony, odpalony w kontenerze, działał bardzo wolno (trzeba było czekać kilka/kilkanaście sekund na jakikolwiek respond).
Z tego powodu, do uruchomienia należy mieć zainstalowany Symfony jak i php na urządzerniu, dla systemu Windows sprowadza się to do pobrania plików i dodania
ścieżki do nich do zmiennej PATH. Potencjalnie trzeba jeszcze do w pliku php.init ustawić dostęp do biblioteki postgresql. Po instalacji należy uruchomić serwer za pomocą komendy:


php -S 127.0.0.1:8000 -t public


z poziomu katalogu "backend"


Aby uruchomić resztę serwisów wystarczy z poziomu głównego katalogu aplikacji zbudować kontenery i je odpalić:


docker compose build


docker compose up


następnie z poziomu katalogu "backend" wykonać:


php bin/console make:migration


php bin/console doctrine:migrations:migrate

Aby na pewno wgrać poprawną migrację

Oraz trzeba wygenerować klucze do tokenów JWT czyli z poziomy "backend":

openssl genpkey -algorithm RSA -out config/jwt/private.pem -aes256

openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem


Następnie trzeba z poziomu pgadmin czyli:


http://localhost:5050/


zalogować się za pomocą:

email: admin@admin.pl
hasło: admin


Następnie połączyć się z bazą danych:

name: dowolne

W drugiej zakładce:

Host name: db

username: user

passwor: password


Następnie należy włączyć Qquery Tool za pomocą skrótu: Alt+Shift+Q

I przekopiować zawartość pliku init.sql i uruchomić

Następnie, by dodać przykładowe dane tz wypełnić bazę różnymi rekordami zrobić to samo z plikiem example.sql

uzytkownicy: admin, user1, user2

wszyscy mający hasło "admin"


Panel dokumentacji: http://127.0.0.1:8000/api/doc

Link do strony: http://localhost:5173/


Argumentacja  technologii:

Backend: Symfony

Popularny język stworzony do projektowania stron internetowych, dostęp do dużej bazy bibliotek/bundli,
wysoki poziombezpieczeństwa, rozwiający pod względem nauki języka

Frontend: React.js

Popularny co zachęca do nauki, wspiera tworzenie komponentów co ułatwia modularność, łatwe API, czytelny, rozwiający pod względem nauki języka

System baz danych: Postgresql

Popularny, dobrze działający system baz danych



