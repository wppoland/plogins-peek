=== Plogins Peek - Product Preview for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, quick view, product quick view, product modal, quick shop
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Schnelle Produkt-Schnellansicht für WooCommerce: ein AJAX-Produktmodal mit Galerie, Preis, SKU und In-den-Warenkorb-Funktion. Kein jQuery.

== Description ==

Peek fügt den Produktschleifen in deinem WooCommerce-Shop und auf den Archivseiten eine Schaltfläche für die Produkt-Schnellansicht hinzu. Ein Klick darauf öffnet ein barrierefreies AJAX-Produktmodal, sodass deine Kundschaft Produkte in der Vorschau ansehen, Optionen wählen und in den Warenkorb legen kann, ohne die Liste zu verlassen.

Das Modal zeigt das Beitragsbild und die Galerie-Miniaturbilder, den Titel, die SKU, den Preis, den Lagerstatus, die Kurzbeschreibung, das native In-den-Warenkorb-Formular (einschließlich variabler Produkte) und einen Link zur vollständigen Produktseite. Jeder Teil lässt sich im Einstellungsbildschirm ein- und ausschalten.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-peek/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-peek/
* <strong>Quellcode</strong> - https://github.com/wppoland/plogins-peek
* <strong>Fehlerberichte und Funktionswünsche</strong> - https://github.com/wppoland/plogins-peek/issues


= Built for speed and accessibility =

* <strong>Kein jQuery</strong> im eigenen Frontend-Code des Plugins – das Skript ist reines JavaScript, verzögert und im Footer geladen.
* <strong>Keine Layout-Verschiebung (CLS).</strong> Das Modal ist bis zum Öffnen vollständig ausgeblendet und scrollt intern, sodass es die Seite nie neu umbricht.
* <strong>Fokus-Falle und tastaturfreundlich.</strong> Beim Öffnen wandert der Fokus in den Dialog und bleibt dort gefangen, der Dialog schließt sich mit Escape oder per Klick auf den Hintergrund und kehrt beim Schließen zum auslösenden Button zurück. Der Dialog nutzt `role="dialog"` mit `aria-modal`.
* <strong>Varianten-fähig.</strong> Das In-den-Warenkorb-Formular unterstützt variable Produkte über WooCommerces eigenes Variations-Skript.

= Settings =

Eine Einstellungsseite (Menü „Peek“, für WooCommerce-Berechtigungen) lässt dich:

* Die Schnellansicht aktivieren oder deaktivieren.
* Beschriftung und Stil des auslösenden Buttons festlegen (Text, Icon oder Icon + Text).
* Wählen, wo geladen wird: nur auf Shop- und Produktarchiven oder auch in den Verwandt-/Up-Sell-Schleifen auf einzelnen Produktseiten.
* Die Modal-Oberfläche konfigurieren: Titel, Beschriftung des Schließen-Buttons, Lade- und Fehlertext, den Linktext „Produkt ansehen“ und die SKU-Beschriftung sowie Schalter für die Modal-Überschrift, den Schließen-Button und das Schließen per Klick auf den Hintergrund.
* Wählen, welche Teile im Modal erscheinen (Bild, Galerie mit konfigurierbarer Anzahl an Miniaturbildern, Titel, SKU, Preis, Lagerstatus, Kurzbeschreibung, In-den-Warenkorb, Link zur vollständigen Produktseite).

= Shortcode =

Platziere mit `[peek_quick_view id="123"]` oder dem kürzeren Alias `[peek id="123"]` an beliebiger Stelle einen Schnellansicht-Auslöser. Optionale Attribute: `text` (eigene Beschriftung) und `style` (`text`, `icon` oder `icon_text`). Das Modal und seine Assets werden automatisch geladen, wo immer der Shortcode erscheint.

Peek wird quelloffen entwickelt. Der Code, offene Issues und der Release-Verlauf liegen unter https://github.com/wppoland/plogins-peek – Fehlerberichte und Patches sind dort willkommen.

== Installation ==

1. Lade das Plugin nach `/wp-content/plugins/plogins-peek` hoch oder installiere es über Plugins → Installieren.
2. Aktiviere es. WooCommerce muss aktiv sein.
3. Öffne das Menü <strong>Peek</strong> in wp-admin, um Button-Beschriftung und Modal-Inhalt zu konfigurieren.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Ja. Peek erfordert eine aktive WooCommerce-Installation.

= Does it use jQuery? =

Das eigene Frontend-Skript des Plugins ist reines JavaScript ohne jQuery-Abhängigkeit. Hat ein Produkt Varianten, wird das mitgelieferte Variations-Skript von WooCommerce (das selbst jQuery nutzt) eingebunden, damit das Variationsformular wie erwartet funktioniert.

= Where does the quick-view button appear? =

Auf der Shop-Seite und in den Produktarchiv-Schleifen (Kategorien, Tags, Taxonomien), nach jedem Produkt. Einzelne Produktseiten werden nicht verändert.

= Does the modal support add to cart? =

Ja. Peek stellt das native In-den-Warenkorb-Formular von WooCommerce im Quick-Shop-Modal dar, inklusive Menge und Auswahl variabler Produkte.

= Does it work with variable products? =

Ja. Variable Produkte nutzen WooCommerces eigenes Variationsformular im Produktmodal, sodass die Kundschaft eine Variante wählen kann, bevor sie in den Warenkorb legt.

= Will it cause layout shift? =

Nein. Das Modal ist bis zum Öffnen ausgeblendet und legt sich über die Seite, sodass das Öffnen bestehende Inhalte nie neu umbricht.

= Can I place a quick-view button manually? =

Ja. Verwende `[peek_quick_view id="123"]` oder `[peek id="123"]`, um in eigenen Layouts einen Auslöser für die Produkt-Schnellansicht hinzuzufügen.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es netzwerkweit oder auf einzelnen Websites; jede Website behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Das Schnellansicht-Modal mit Produktgalerie, Preis und In-den-Warenkorb-Formular.
2. Der Peek-Einstellungsbildschirm.

== External Services ==

Peek stellt keine Verbindung zu externen Diensten her. Das Schnellansicht-Modal ruft sein Produktfragment von deiner eigenen Website über die `admin-ajax.php` von WordPress ab (die Aktion `peek_quick_view`), sodass keine Kunden- oder Produktdaten jemals deinen Server verlassen. Die einzigen von Peek gespeicherten Daten sind zwei von ihm angelegte WordPress-Optionen, `peek_settings` (deine Modal- und Button-Konfiguration) und `peek_db_version`, die beide beim Löschen des Plugins entfernt werden. Peek sendet keine E-Mails und lädt keine Skripte, Schriften oder Analyse-Tools von Dritten.

== Translations ==

Plogins Peek enthält deutsche, polnische und spanische Übersetzungen für die Plugin-Oberfläche. Die Textdomain ist `plogins-peek`, sodass Sprachpakete von WordPress.org diese mitgelieferten Übersetzungen ebenfalls überschreiben oder erweitern können.

== Changelog ==

= 1.0.2 =
* Deutsche, polnische und spanische Übersetzungen für die Plugin-Oberfläche mitgeliefert.

= 1.0.1 =
* Erste stabile Version.

= 0.3.1 =
* In Plogins Peek für WooCommerce umbenannt, für einen unverwechselbareren Plugin-Namen.

= 0.3.0 =
* Neu: Platzierung des Buttons in der Schleife, unterhalb der Karte oder als Overlay auf dem Miniaturbild (bei Hover/Fokus).
* Neu: Shortcode `[peek]` als Alias für `[peek_quick_view]`.

= 0.2.0 =
* Neu: Shortcode `[peek_quick_view]`, um an beliebiger Stelle einen Schnellansicht-Auslöser zu platzieren, mit optionalen Attributen `id`, `text` und `style`.
* Neu: Zeile mit Lagerstatus im Modal, mit Schalter.
* Neu: konfigurierbare Anzahl an Galerie-Miniaturbildern (0–12).
* Neu: Stil des auslösenden Buttons, Text, Icon oder Icon + Text (nur Icon behält einen barrierefreien Namen).
* Neu: Anzeigebereich, nur auf Shop/Archiven laden oder auch in Verwandt-/Up-Sell-Schleifen einzelner Produkte.
* Neu: Steuerelemente für die Modal-Oberfläche auf der Einstellungsseite (Titel, Beschriftung des Schließen-Buttons, Lade-/Fehlertext, Linktext „Produkt ansehen“, SKU-Beschriftung und Schalter für Modal-Überschrift, Schließen-Button und Schließen per Klick auf den Hintergrund).
* Neu: `uninstall.php` entfernt die Optionen des Plugins beim Löschen.
* Domain Path für Übersetzungen hinzugefügt.

= 0.1.0 =
* Erste Veröffentlichung: barrierefreies AJAX-Schnellansicht-Modal für WooCommerce-Shop- und Archiv-Schleifen, mit einer Einstellungsseite für die Button-Beschriftung und die Modal-Inhalte.
