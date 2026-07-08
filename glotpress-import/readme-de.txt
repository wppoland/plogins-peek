=== Plogins Peek - Quick View for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, quick view, product quick view, product modal, quick shop
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Schnelle Produkt-Schnellansicht für WooCommerce: ein AJAX-Produktmodal mit Galerie, Preis, SKU und Add-to-Cart. Kein jQuery.

== Description ==

Peek fügt deinem WooCommerce-Shop eine Produkt-Schnellansichtsschaltfläche hinzu und archiviert Produktschleifen. Wenn du darauf klicken, wird ein zugängliches AJAX-Produktmodal geöffnet, sodass Käufer eine Vorschau der Produkte anzeigen, Optionen auswählen und in den Warenkorb legen können, ohne das Angebot zu verlassen.

Das Modal zeigt die vorgestellten Bilder und Miniaturansichten der Galerie, Titel, SKU, Preis, Lagerstatus, Kurzbeschreibung, das native Add-to-Cart-Formular (einschließlich variabler Produkte) und einen Link zur vollständigen Produktseite. Jeder Teil kann über den Einstellungsbildschirm umgeschaltet werden.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-peek/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-peek/
* <strong>Quellcode</strong> – https://github.com/wppoland/plogins-peek
* <strong>Fehlerberichte und Funktionsanfragen</strong> – https://github.com/wppoland/plogins-peek/issues


= Built for speed and accessibility =

* <strong>Kein jQuery</strong> im eigenen Front-End-Code des Plugins, das Skript ist Vanilla JS, verzögert und wird in der Fußzeile geladen.
* <strong>Keine Layoutverschiebung (CLS).</strong> Das Modal ist bis zum Öffnen vollständig ausgeblendet und scrollt intern, sodass die Seite nie neu umfließt.
* <strong>Fokus-gefangen und tastaturfreundlich.</strong> Der Fokus bewegt sich beim Öffnen in den Dialog, bleibt beim Öffnen gefangen, schließt sich beim Escape- oder Hintergrundklick und kehrt beim Schließen zur Auslöseschaltfläche zurück. Der Dialog verwendet „role="dialog"` mit `aria-modal`.
* <strong>Variationsbewusst.</strong> Das Add-to-Cart-Formular unterstützt variable Produkte über WooCommerces eigenes Variationsskript.

= Settings =

Auf einer Einstellungsseite für die WooCommerce-Funktionalität (Peek-Menü) kannst du:

* Aktivieren oder deaktiviere die Schnellansicht.
* Lege die Beschriftung und den Stil der Auslöseschaltfläche fest (Text, Symbol oder Symbol + Text).
* Wähle, wo geladen werden soll: nur Shop- und Produktarchive oder auch die zugehörigen/Up-Selling-Schleifen auf einzelnen Produktseiten.
* Konfiguriere das modale Chrome: Titel, Beschriftung der Schaltfläche „Schließen“, Lade- und Fehlertext, Linktext „Produkt anzeigen“ und SKU-Beschriftung sowie Umschalter für die modale Überschrift, die Schaltfläche „Schließen“ und das Schließen per Hintergrundklick.
* Wähle aus, welche Teile im Modal angezeigt werden sollen (Bild, Galerie mit konfigurierbarer Anzahl von Miniaturansichten, Titel, SKU, Preis, Lagerstatus, Kurzbeschreibung, Add-to-Cart, Link zum vollständigen Produkt).

= Shortcode =

Platziere einen Schnellansicht-Trigger an einer beliebigen Stelle mit „[peek_quick_view id="123"]“ oder dem kürzeren Alias ​​„[peek id="123"]“. Optionale Attribute: „text“ (benutzerdefinierte Beschriftung) und „style“ („text“, „icon“ oder „icon_text“). Das Modal und seine Assets werden automatisch geladen, wann immer der Shortcode erscheint.

Peek wird im Freien entwickelt. Der Code, offene Probleme und der Release-Verlauf sind live unter https://github.com/wppoland/plogins-peek zu sehen, Fehlerberichte und Patches sind dort willkommen.

== Installation ==

1. Lade das Plugin nach „/wp-content/plugins/plogins-peek“ hoch oder installiere es über Plugins → Neu hinzufügen.
2. Aktiviere es. WooCommerce muss aktiv sein.
3. Besuche das <strong>Peek</strong>-Menü in wp-admin, um die Schaltflächenbeschriftung und den modalen Inhalt zu konfigurieren.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Ja. Peek erfordert eine aktive WooCommerce-Installation.

= Does it use jQuery? =

Das eigene Front-End-Skript des Plugins ist Vanilla-JavaScript ohne jQuery-Abhängigkeit. Wenn ein Produkt Variationen hat, wird das gebündelte Variationsskript von WooCommerce (das selbst jQuery verwendet) in die Warteschlange gestellt, damit das Variationsformular wie erwartet funktioniert.

= Where does the quick-view button appear? =

Auf der Shop-Seite und den Produktarchivschleifen (Kategorien, Tags, Taxonomien) nach jedem Produkt. Einzelne Produktseiten werden dadurch nicht verändert.

= Does the modal support add to cart? =

Ja. Peek stellt das native Add-to-Cart-Formular von WooCommerce innerhalb des Quick-Shop-Modals dar, einschließlich Mengen- und variabler Produktauswahl.

= Does it work with variable products? =

Ja. Variable Produkte verwenden WooCommerces eigenes Variationsformular innerhalb des Produktmodals, sodass Käufer eine Variation auswählen können, bevor sie sie in den Warenkorb legen.

= Will it cause layout shift? =

Nein. Das Modal ist ausgeblendet, bis es geöffnet wird, und überlagert die Seite, sodass beim Öffnen niemals vorhandene Inhalte neu angezeigt werden.

= Can I place a quick-view button manually? =

Ja. Verwende „[peek_quick_view id="123"]“ oder „[peek id="123"]“, um in benutzerdefinierten Layouts einen Auslöser für die Produktschnellansicht hinzuzufügen.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es im Netzwerk oder auf einzelnen Websites. Jede Site behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Das Schnellansichtsmodal mit der Produktgalerie, dem Preis und dem Formular zum Hinzufügen zum Warenkorb.
2. Der Peek-Einstellungsbildschirm.

== External Services ==

Peek stellt keine Verbindung zu externen Diensten her. Das Quick-View-Modal ruft sein Produktfragment von deiner eigenen Website über WordPress „admin-ajax.php“ (die „peek_quick_view“-Aktion) ab, sodass keine Käufer- oder Produktdaten jemals deinen Server verlassen. Die einzigen gespeicherten Daten von Peek sind zwei von ihm erstellte WordPress-Optionen, „peek_settings“ (deine Modal- und Schaltflächenkonfiguration) und „peek_db_version“, die beide entfernt werden, wenn das Plugin gelöscht wird. Peek sendet keine E-Mails und lädt keine Skripte, Schriftarten oder Analysen von Drittanbietern.

== Changelog ==

= 1.0.1 =
* Erste stabile Version.

= 0.3.1 =
* Für einen eindeutigeren Plugin-Namen in Plogins Peek für WooCommerce umbenannt.

= 0.3.0 =
* Neu: Platzierung der Loop-Schaltfläche, unterhalb der Karte oder Overlay auf dem Miniaturbild (Hover/Fokus).
* Neu: Shortcode „[peek]“ als Alias ​​für „[peek_quick_view]“.

= 0.2.0 =
* Neu: „[peek_quick_view]“-Shortcode zum Platzieren eines Schnellansicht-Triggers an einer beliebigen Stelle, mit optionalen Attributen „id“, „text“ und „style“.
* Neu: Bestandsstatuszeile im Modal, mit Umschaltfunktion.
* Neu: konfigurierbare Anzahl der Miniaturansichten der Galerie (0–12).
* Neu: Stil der Auslöseschaltfläche, Text, Symbol oder Symbol + Text (nur Symbol behält einen zugänglichen Namen bei).
* Neu: Anzeigeumfang, Laden nur auf Shop/Archive, oder auch auf einzelne Produkte bezogene/Up-Selling-Schleifen.
* Neu: Modale Chrome-Steuerelemente auf der Einstellungsseite (Titel, Beschriftung der Schaltfläche „Schließen“, Lade-/Fehlertext, Linktext zum Anzeigen des Produkts, Beschriftung der Artikelnummer und Umschalter für die modale Überschrift, die Schaltfläche „Schließen“ und das Schließen per Hintergrundklick).
* Neu: „uninstall.php“ entfernt die Optionen des Plugins beim Löschen.
* Domänenpfad für Übersetzungen hinzugefügt.

= 0.1.0 =
* Erstveröffentlichung: Zugängliches AJAX-Schnellansichtsmodal für WooCommerce-Shop- und Archivschleifen, mit einer Einstellungsseite für die Schaltflächenbezeichnung und modale Inhalte.
