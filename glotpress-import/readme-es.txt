=== Plogins Peek - Product Preview for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, quick view, product quick view, product modal, quick shop
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Vista rápida de producto para WooCommerce: un modal de producto AJAX con galería, precio, SKU y añadir al carrito. Sin jQuery.

== Description ==

Peek añade un botón de vista rápida de producto a los bucles de producto de tu tienda y de los archivos de WooCommerce. Al hacer clic en él se abre un modal de producto AJAX accesible, para que los clientes puedan previsualizar productos, elegir opciones y añadir al carrito sin salir del listado.

El modal muestra la imagen destacada y las miniaturas de la galería, el título, el SKU, el precio, el estado del stock, la descripción corta, el formulario nativo de añadir al carrito (incluidos los productos variables) y un enlace a la página completa del producto. Cada parte se puede activar o desactivar desde la pantalla de ajustes.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-peek/docs/
* <strong>Página del plugin</strong> - https://plogins.com/es/plogins-peek/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-peek
* <strong>Informes de errores y peticiones de funciones</strong> - https://github.com/wppoland/plogins-peek/issues


= Built for speed and accessibility =

* <strong>Sin jQuery</strong> en el código del frontend del propio plugin: el script es JavaScript puro, diferido y cargado en el pie de página.
* <strong>Sin saltos de diseño (CLS).</strong> El modal permanece totalmente oculto hasta que se abre y se desplaza internamente, de modo que nunca redistribuye la página.
* <strong>Con foco atrapado y compatible con el teclado.</strong> Al abrir, el foco entra en el diálogo y queda atrapado mientras está abierto; se cierra con Escape o al hacer clic en el fondo y, al cerrarse, vuelve al botón que lo activó. El diálogo usa `role="dialog"` con `aria-modal`.
* <strong>Compatible con variaciones.</strong> El formulario de añadir al carrito admite productos variables mediante el propio script de variaciones de WooCommerce.

= Settings =

Una página de ajustes (menú «Peek», con permisos de WooCommerce) te permite:

* Activar o desactivar la vista rápida.
* Definir la etiqueta y el estilo del botón que la activa (texto, icono o icono + texto).
* Elegir dónde se carga: solo en la tienda y los archivos de producto, o también en los bucles de relacionados/venta adicional de las páginas de producto individuales.
* Configurar la interfaz del modal: título, etiqueta del botón de cerrar, texto de carga y de error, el texto del enlace «ver producto» y la etiqueta del SKU, además de interruptores para el encabezado del modal, el botón de cerrar y el cierre al hacer clic en el fondo.
* Elegir qué partes se muestran en el modal (imagen, galería con un número de miniaturas configurable, título, SKU, precio, estado del stock, descripción corta, añadir al carrito, enlace a la página completa del producto).

= Shortcode =

Coloca un activador de vista rápida en cualquier lugar con `[peek_quick_view id="123"]`, o con el alias más corto `[peek id="123"]`. Atributos opcionales: `text` (etiqueta personalizada) y `style` (`text`, `icon` o `icon_text`). El modal y sus recursos se cargan automáticamente dondequiera que aparezca el shortcode.

Peek se desarrolla de forma abierta (código abierto). El código, las incidencias abiertas y el historial de versiones están en https://github.com/wppoland/plogins-peek; allí son bienvenidos los informes de errores y los parches.

== Installation ==

1. Sube el plugin a `/wp-content/plugins/plogins-peek` o instálalo desde Plugins → Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Entra en el menú <strong>Peek</strong> de wp-admin para configurar la etiqueta del botón y el contenido del modal.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Sí. Peek requiere una instalación activa de WooCommerce.

= Does it use jQuery? =

El script del frontend del propio plugin es JavaScript puro, sin dependencia de jQuery. Cuando un producto tiene variaciones, se encola el script de variaciones incluido en WooCommerce (que a su vez usa jQuery) para que el formulario de variaciones funcione como se espera.

= Where does the quick-view button appear? =

En la página de la tienda y en los bucles de archivo de productos (categorías, etiquetas, taxonomías), después de cada producto. No modifica las páginas de producto individuales.

= Does the modal support add to cart? =

Sí. Peek muestra el formulario nativo de añadir al carrito de WooCommerce dentro del modal de compra rápida, incluidas la cantidad y las opciones de producto variable.

= Does it work with variable products? =

Sí. Los productos variables usan el propio formulario de variaciones de WooCommerce dentro del modal del producto, así que los clientes pueden elegir una variación antes de añadirla al carrito.

= Will it cause layout shift? =

No. El modal permanece oculto hasta que se abre y se superpone a la página, de modo que abrirlo nunca redistribuye el contenido existente.

= Can I place a quick-view button manually? =

Sí. Usa `[peek_quick_view id="123"]` o `[peek id="123"]` para añadir un activador de vista rápida de producto en diseños personalizados.


= Does this plugin work on WordPress Multisite? =

Sí. Este plugin es compatible con WordPress Multisite. Actívalo para toda la red o en sitios individuales; cada sitio conserva sus propios ajustes y datos.

== Screenshots ==

1. El modal de vista rápida mostrando la galería del producto, el precio y el formulario de añadir al carrito.
2. La pantalla de ajustes de Peek.

== External Services ==

Peek no se conecta a ningún servicio externo. El modal de vista rápida obtiene su fragmento de producto desde tu propio sitio a través de `admin-ajax.php` de WordPress (la acción `peek_quick_view`), así que ningún dato de clientes o de productos sale nunca de tu servidor. Los únicos datos que Peek almacena son dos opciones de WordPress que crea, `peek_settings` (tu configuración del modal y del botón) y `peek_db_version`, ambas eliminadas cuando se borra el plugin. Peek no envía ningún correo electrónico ni carga scripts, fuentes ni analíticas de terceros.

== Translations ==

Plogins Peek incluye traducciones al polaco, al alemán y al español para la interfaz del plugin. El dominio de texto es `plogins-peek`, por lo que los paquetes de idioma de WordPress.org también pueden sustituir o ampliar estas traducciones incluidas.

== Changelog ==

= 1.0.2 =
* Añadidas traducciones al polaco, al alemán y al español para la interfaz del plugin.

= 1.0.1 =
* Primera versión estable.

= 0.3.1 =
* Renombrado a Plogins Peek para WooCommerce para lograr un nombre de plugin más distintivo.

= 0.3.0 =
* Nuevo: colocación del botón en el bucle, debajo de la tarjeta o superpuesto en la miniatura (al pasar el cursor o enfocar).
* Nuevo: shortcode `[peek]` como alias de `[peek_quick_view]`.

= 0.2.0 =
* Nuevo: shortcode `[peek_quick_view]` para colocar un activador de vista rápida en cualquier lugar, con atributos opcionales `id`, `text` y `style`.
* Nuevo: fila de estado del stock en el modal, con un interruptor.
* Nuevo: número de miniaturas de la galería configurable (0–12).
* Nuevo: estilo del botón que activa, texto, icono o icono + texto (solo icono conserva un nombre accesible).
* Nuevo: ámbito de visualización, cargar solo en la tienda/archivos, o también en los bucles de relacionados/venta adicional de producto individual.
* Nuevo: controles de la interfaz del modal en la página de ajustes (título, etiqueta del botón de cerrar, texto de carga/error, texto del enlace para ver el producto, etiqueta del SKU e interruptores para el encabezado del modal, el botón de cerrar y el cierre al hacer clic en el fondo).
* Nuevo: `uninstall.php` elimina las opciones del plugin al borrarlo.
* Añadida la ruta de dominio (Domain Path) para las traducciones.

= 0.1.0 =
* Versión inicial: modal de vista rápida AJAX accesible para los bucles de tienda y archivo de WooCommerce, con una página de ajustes para la etiqueta del botón y el contenido del modal.
