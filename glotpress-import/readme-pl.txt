=== Plogins Peek - Product Preview for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, quick view, product quick view, product modal, quick shop
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Szybki podgląd produktu dla WooCommerce: okno modalne produktu w technologii AJAX z galerią, ceną, SKU i dodawaniem do koszyka. Bez jQuery.

== Description ==

Peek dodaje przycisk szybkiego podglądu produktu do pętli produktów w Twoim sklepie i na stronach archiwów WooCommerce. Kliknięcie go otwiera dostępne okno modalne produktu w technologii AJAX, dzięki czemu klienci mogą podejrzeć produkt, wybrać opcje i dodać go do koszyka bez opuszczania listy.

Okno modalne pokazuje zdjęcie główne i miniatury galerii, tytuł, SKU, cenę, stan magazynowy, krótki opis, natywny formularz dodawania do koszyka (w tym produkty z wariantami) oraz link do pełnej strony produktu. Każdy element można włączyć lub wyłączyć na ekranie ustawień.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-peek/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-peek/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-peek
* <strong>Zgłoszenia błędów i propozycje funkcji</strong> - https://github.com/wppoland/plogins-peek/issues


= Built for speed and accessibility =

* <strong>Bez jQuery</strong> we własnym kodzie front-endu wtyczki — skrypt to czysty JavaScript, ładowany z opóźnieniem w stopce.
* <strong>Bez przeskoków układu (CLS).</strong> Okno modalne jest w pełni ukryte do czasu otwarcia i przewija się wewnętrznie, więc nigdy nie przebudowuje strony.
* <strong>Przechwytywanie fokusu i obsługa z klawiatury.</strong> Po otwarciu fokus przechodzi do okna dialogowego i pozostaje w nim uwięziony, okno zamyka się klawiszem Escape lub kliknięciem tła i po zamknięciu wraca do przycisku wyzwalającego. Okno dialogowe używa `role="dialog"` z atrybutem `aria-modal`.
* <strong>Obsługa wariantów.</strong> Formularz dodawania do koszyka obsługuje produkty z wariantami dzięki własnemu skryptowi wariantów WooCommerce.

= Settings =

Strona ustawień (menu Peek, dostępna dla uprawnień WooCommerce) pozwala:

* Włączyć lub wyłączyć szybki podgląd.
* Ustawić etykietę i styl przycisku wyzwalającego (tekst, ikona lub ikona + tekst).
* Wybrać, gdzie się ładuje: tylko na stronie sklepu i w archiwach produktów, czy również w pętlach produktów powiązanych/sprzedaży dodatkowej na stronach pojedynczych produktów.
* Skonfigurować elementy interfejsu okna modalnego: tytuł, etykietę przycisku zamykania, tekst ładowania i błędu, tekst linku „wyświetl produkt” oraz etykietę SKU, a także przełączniki nagłówka okna, przycisku zamykania i zamykania kliknięciem tła.
* Wybrać, które elementy są renderowane w oknie modalnym (zdjęcie, galeria z konfigurowalną liczbą miniatur, tytuł, SKU, cena, stan magazynowy, krótki opis, dodawanie do koszyka, link do pełnej strony produktu).

= Shortcode =

Umieść wyzwalacz szybkiego podglądu w dowolnym miejscu za pomocą `[peek_quick_view id="123"]` lub krótszego aliasu `[peek id="123"]`. Opcjonalne atrybuty: `text` (własna etykieta) oraz `style` (`text`, `icon` lub `icon_text`). Okno modalne i jego zasoby ładują się automatycznie wszędzie tam, gdzie pojawia się shortcode.

Peek jest rozwijany otwarcie (open source). Kod, otwarte zgłoszenia i historia wydań znajdują się na https://github.com/wppoland/plogins-peek — zgłoszenia błędów i poprawki są tam mile widziane.

== Installation ==

1. Wgraj wtyczkę do `/wp-content/plugins/plogins-peek` lub zainstaluj przez Wtyczki → Dodaj nową.
2. Włącz ją. WooCommerce musi być aktywne.
3. Wejdź w menu <strong>Peek</strong> w wp-admin, aby skonfigurować etykietę przycisku i zawartość okna modalnego.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Tak. Peek wymaga aktywnej instalacji WooCommerce.

= Does it use jQuery? =

Własny skrypt front-endu wtyczki to czysty JavaScript, bez zależności od jQuery. Gdy produkt ma warianty, dołączany jest wbudowany skrypt wariantów WooCommerce (który sam korzysta z jQuery), aby formularz wariantów działał zgodnie z oczekiwaniami.

= Where does the quick-view button appear? =

Na stronie sklepu i w pętlach archiwów produktów (kategorie, tagi, taksonomie), po każdym produkcie. Nie zmienia stron pojedynczych produktów.

= Does the modal support add to cart? =

Tak. Peek renderuje natywny formularz dodawania do koszyka WooCommerce wewnątrz okna szybkich zakupów, w tym wybór ilości i wariantów produktu.

= Does it work with variable products? =

Tak. Produkty z wariantami korzystają z własnego formularza wariantów WooCommerce wewnątrz okna modalnego produktu, dzięki czemu klienci mogą wybrać wariant przed dodaniem do koszyka.

= Will it cause layout shift? =

Nie. Okno modalne jest ukryte do czasu otwarcia i nakłada się na stronę, więc jego otwarcie nigdy nie przebudowuje istniejącej treści.

= Can I place a quick-view button manually? =

Tak. Użyj `[peek_quick_view id="123"]` lub `[peek id="123"]`, aby dodać wyzwalacz szybkiego podglądu produktu we własnych układach.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest zgodna z WordPress Multisite. Włącz ją w całej sieci lub na poszczególnych witrynach; każda witryna zachowuje własne ustawienia i dane.

== Screenshots ==

1. Okno szybkiego podglądu pokazujące galerię produktu, cenę i formularz dodawania do koszyka.
2. Ekran ustawień Peek.

== External Services ==

Peek nie łączy się z żadnymi usługami zewnętrznymi. Okno szybkiego podglądu pobiera fragment produktu z Twojej własnej witryny przez `admin-ajax.php` WordPressa (akcja `peek_quick_view`), dzięki czemu żadne dane klientów ani produktów nigdy nie opuszczają Twojego serwera. Jedyne dane przechowywane przez Peek to dwie tworzone przez niego opcje WordPressa — `peek_settings` (konfiguracja okna modalnego i przycisku) oraz `peek_db_version` — obie usuwane po skasowaniu wtyczki. Peek nie wysyła żadnych e-maili ani nie ładuje skryptów, czcionek czy narzędzi analitycznych innych firm.

== Translations ==

Plogins Peek zawiera polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki. Domena tekstowa to `plogins-peek`, więc pakiety językowe z WordPress.org mogą też nadpisywać lub rozszerzać dołączone tłumaczenia.

== Changelog ==

= 1.0.2 =
* Dodano dołączone polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki.

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.3.1 =
* Zmieniono nazwę na Plogins Peek dla WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.3.0 =
* Nowość: umieszczanie przycisku w pętli — pod kartą lub jako nakładka na miniaturę (po najechaniu/uzyskaniu fokusu).
* Nowość: shortcode `[peek]` jako alias dla `[peek_quick_view]`.

= 0.2.0 =
* Nowość: shortcode `[peek_quick_view]` umożliwiający umieszczenie wyzwalacza szybkiego podglądu w dowolnym miejscu, z opcjonalnymi atrybutami `id`, `text` i `style`.
* Nowość: wiersz stanu magazynowego w oknie modalnym, z przełącznikiem.
* Nowość: konfigurowalna liczba miniatur galerii (0–12).
* Nowość: styl przycisku wyzwalającego — tekst, ikona lub ikona + tekst (sama ikona zachowuje dostępną nazwę).
* Nowość: zakres wyświetlania — ładowanie tylko w sklepie/archiwach lub także w pętlach produktów powiązanych/sprzedaży dodatkowej na stronach pojedynczych produktów.
* Nowość: elementy interfejsu okna modalnego na stronie ustawień (tytuł, etykieta przycisku zamykania, tekst ładowania/błędu, tekst linku do widoku produktu, etykieta SKU oraz przełączniki nagłówka okna, przycisku zamykania i zamykania kliknięciem tła).
* Nowość: `uninstall.php` usuwa opcje wtyczki podczas jej kasowania.
* Dodano ścieżkę domeny (Domain Path) dla tłumaczeń.

= 0.1.0 =
* Pierwsze wydanie: dostępne okno modalne szybkiego podglądu w technologii AJAX dla pętli sklepu i archiwów WooCommerce, ze stroną ustawień etykiety przycisku i zawartości okna modalnego.
