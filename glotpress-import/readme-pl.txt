=== Plogins Peek - Quick View for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, quick view, product quick view, product modal, quick shop
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Szybki szybki podgląd produktu dla WooCommerce: moduł produktu AJAX z galerią, ceną, SKU i dodatkiem do koszyka. Bez jQuery.

== Description ==

Peek dodaje przycisk szybkiego podglądu produktu do Twojego sklepu WooCommerce i archiwizuje pętle produktów. Kliknięcie go otwiera dostępny moduł produktu AJAX, dzięki czemu kupujący mogą przeglądać produkty, wybierać opcje i dodawać je do koszyka bez opuszczania aukcji.

Modal pokazuje wyróżnione obrazy i miniatury galerii, tytuł, SKU, cenę, stan magazynowy, krótki opis, natywny formularz dodawania do koszyka (w tym produkty zmienne) oraz link do pełnej strony produktu. Każdą część można przełączać na ekranie ustawień.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-peek/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-peek/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-peek
* <strong>Raporty o błędach i prośby o nowe funkcje</strong> - https://github.com/wppoland/plogins-peek/issues


= Built for speed and accessibility =

* <strong>Brak jQuery</strong> we własnym kodzie wtyczki, skrypt to waniliowy JS, odroczony i załadowany w stopce.
* <strong>Brak zmiany układu (CLS).</strong> Modal jest całkowicie ukryty do momentu otwarcia i przewija się wewnętrznie, więc nigdy nie zmienia rozmiaru strony.
* <strong>Zablokowany fokus i przyjazny dla klawiatury.</strong> Fokus przenosi się do okna dialogowego po otwarciu, jest zalewkowany, gdy jest otwarte, zamyka się po kliknięciu ucieczki lub tła i powraca do przycisku wyzwalacza po zamknięciu. W oknie dialogowym używane są `role="dialog"` z `aria-modal`.
* <strong>Uwzględnia różnice.</strong> Formularz dodawania do koszyka obsługuje produkty zmienne za pośrednictwem własnego skryptu odmian WooCommerce.

= Settings =

Strona ustawień możliwości WooCommerce (menu Peek) umożliwia:

* Włącz lub wyłącz szybki podgląd.
* Ustaw etykietę i styl przycisku wyzwalającego (tekst, ikona lub ikona + tekst).
* Wybierz miejsce ładowania: tylko archiwa sklepów i produktów, czy też pętle powiązane/sprzedaży dodatkowej na stronach pojedynczych produktów.
* Skonfiguruj modalne chromowanie: tytuł, etykietę przycisku zamykania, tekst ładowania i błędu, tekst linku „wyświetl produkt” i etykietę SKU, a także przełączniki nagłówka modalnego, przycisku zamykania i zamykania kliknięciem tła.
* Wybierz, które części mają być renderowane w trybie modalnym (obraz, galeria z konfigurowalną liczbą miniatur, tytuł, SKU, cena, stan magazynowy, krótki opis, dodanie do koszyka, link do pełnego produktu).

= Shortcode =

Umieść wyzwalacz szybkiego podglądu w dowolnym miejscu za pomocą `[peek_quick_view id="123"]` lub krótszego aliasu `[peek id="123"]`. Opcjonalne atrybuty: `text` (etykieta niestandardowa) i `style` (`text`, `icon` lub `icon_text`). Modal i jego zasoby ładują się automatycznie wszędzie tam, gdzie pojawia się krótki kod.

Peek jest rozwijany na otwartej przestrzeni. Kod, otwarte problemy i historia wydań są dostępne na https://github.com/wppoland/plogins-peek, raporty o błędach i poprawki są tam mile widziane.

== Installation ==

1. Prześlij wtyczkę do `/wp-content/plugins/plogins-peek` lub zainstaluj poprzez Wtyczki → Dodaj nową.
2. Aktywuj. WooCommerce musi być aktywny.
3. Odwiedź menu <strong>Peek</strong> w wp-admin, aby skonfigurować etykietę przycisku i zawartość modalną.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Tak. Peek wymaga aktywnej instalacji WooCommerce.

= Does it use jQuery? =

Własnym skryptem front-end wtyczki jest waniliowy JavaScript bez zależności od jQuery. Gdy produkt ma odmiany, dołączony skrypt odmian WooCommerce (który sam korzysta z jQuery) jest umieszczany w kolejce, dzięki czemu formularz odmian działa zgodnie z oczekiwaniami.

= Where does the quick-view button appear? =

Na stronie sklepu i w pętlach archiwum produktów (kategorie, tagi, taksonomie), po każdym produkcie. Nie zmienia pojedynczych stron produktów.

= Does the modal support add to cart? =

Tak. Peek renderuje natywny formularz dodawania do koszyka WooCommerce w trybie szybkiego sklepu, w tym opcje dotyczące ilości i zmiennych produktów.

= Does it work with variable products? =

Tak. Produkty zmienne korzystają z formularza odmian WooCommerce w module produktu, dzięki czemu kupujący mogą wybrać odmianę przed dodaniem do koszyka.

= Will it cause layout shift? =

Nie. Modal jest ukryty do momentu otwarcia i nakłada się na stronę, więc otwarcie go nigdy nie powoduje ponownego przepływu istniejącej zawartości.

= Can I place a quick-view button manually? =

Tak. Użyj `[peek_quick_view id="123"]` lub `[peek id="123"]`, aby dodać wyzwalacz szybkiego podglądu produktu w niestandardowych układach.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest kompatybilna z WordPress Multisite. Aktywuj go w sieci lub aktywuj na poszczególnych stronach; każda witryna przechowuje własne ustawienia i dane.

== Screenshots ==

1. Moduł szybkiego podglądu pokazujący galerię produktów, cenę i formularz dodawania do koszyka.
2. Ekran ustawień podglądu.

== External Services ==

Peek nie łączy się z żadnymi usługami zewnętrznymi. Modal szybkiego podglądu pobiera fragment produktu z Twojej własnej witryny za pośrednictwem WordPressa za pośrednictwem `admin-ajax.php` (akcja `peek_quick_view`), dzięki czemu żadne dane dotyczące kupujących ani produktów nigdy nie opuszczają Twojego serwera. Jedyne przechowywane dane Peek to dwie tworzone przez niego opcje WordPress, „peek_settings” (konfiguracja modalna i przycisku) oraz „peek_db_version”, obie usunięte po usunięciu wtyczki. Peek nie wysyła żadnych wiadomości e-mail ani nie ładuje skryptów, czcionek ani analiz innych firm.

== Changelog ==

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.3.1 =
* Zmieniono nazwę na Plogins Peek dla WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.3.0 =
* Nowość: umieszczenie przycisku pętli pod kartą lub nakładka na miniaturę (najechanie/fokus).
* Nowość: krótki kod `[peek]` jako alias dla `[peek_quick_view]`.

= 0.2.0 =
* Nowość: krótki kod `[peek_quick_view]` umożliwiający umieszczenie wyzwalacza szybkiego podglądu w dowolnym miejscu, z opcjonalnymi atrybutami `id`, `text` i `style`.
* Nowość: wiersz stanu zapasów w trybie modalnym z przełącznikiem.
* Nowość: konfigurowalna liczba miniatur galerii (0–12).
* Nowość: styl przycisku spustowego, tekst, ikona lub ikona + tekst (tylko ikona zachowuje dostępną nazwę).
* Nowość: zakres wyświetlania, ładowanie tylko w sklepie/archiwum lub także pętle związane z pojedynczym produktem/sprzedażą dodatkową.
* Nowość: modalne elementy sterujące Chrome na stronie ustawień (tytuł, etykieta przycisku zamykania, tekst ładowania/błędu, tekst łącza do widoku produktu, etykieta SKU oraz przełączniki nagłówka modalnego, przycisku zamykania i zamykania kliknięciem tła).
* Nowość: `uninstall.php` usuwa opcje wtyczki podczas usuwania.
* Dodano ścieżkę domeny dla tłumaczeń.

= 0.1.0 =
* Wersja pierwsza: dostępny moduł szybkiego podglądu AJAX dla pętli sklepu i archiwum WooCommerce, ze stroną ustawień etykiety przycisku i zawartością modalu.
