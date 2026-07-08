=== Plogins Peek - Quick View for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, quick view, product quick view, product modal, quick shop
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Vista rápida del producto para WooCommerce: un modal de producto AJAX con galería, precio, SKU y complemento al carrito. Sin jQuery.

== Description ==

Peek añade un botón de vista rápida del producto a su tienda WooCommerce y archiva bucles de productos. Al hacer clic en él, se abre un modo de producto AJAX accesible, para que los compradores puedan obtener una vista previa de los productos, elegir opciones y agregarlos al carrito sin salir del listado.

El modal muestra la imagen destacada y las miniaturas de la galería, el título, el SKU, el precio, el estado del stock, una breve descripción, el formulario nativo para añadir al carrito (incluidos los productos variables) y un enlace a la página completa del producto. Cada parte se puede alternar desde la pantalla de configuración.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-peek/docs/
* <strong>Página de complementos</strong> - https://plogins.com/es/plogins-peek/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-peek
* <strong>Informes de errores y solicitudes de funciones</strong> - https://github.com/wppoland/plogins-peek/issues


= Built for speed and accessibility =

* <strong>No hay jQuery</strong> en el código de interfaz del complemento, el script es Vanilla JS, diferido y cargado en el pie de página.
* <strong>Sin cambio de diseño (CLS).</strong> El modal está completamente oculto hasta que se abre y se desplaza internamente, por lo que nunca redistribuye la página.
* <strong>Enfoque atrapado y compatible con el teclado.</strong> El foco se mueve al cuadro de diálogo al abrirlo, queda atrapado mientras está abierto, se cierra al hacer clic en Escape o en el fondo y regresa al botón de activación al cerrar. El diálogo usa `role="dialog"` con `aria-modal`.
* <strong>Consciente de variaciones.</strong> El formulario de añadir al carrito admite productos variables a través del propio script de variación de WooCommerce.

= Settings =

Una página de configuración de capacidad de WooCommerce (menú Peek) le permite:

* Activar o desactivar la vista rápida.
* Establezca la etiqueta y el estilo del botón de activación (texto, icono o icono + texto).
* Elija dónde se carga: solo archivos de tienda y productos, o también los bucles relacionados/de venta adicional en páginas de productos individuales.
* Configure el cromo modal: título, etiqueta del botón de cerrar, texto de carga y error, el texto del enlace "ver producto" y la etiqueta de SKU, además de alternar para el encabezado modal, el botón de cerrar y cerrar con clic en el fondo.
* Elija qué piezas se muestran en el modal (imagen, galería con un recuento de miniaturas configurable, título, SKU, precio, estado de existencias, descripción breve, añadir al carrito, enlace del producto completo).

= Shortcode =

Coloque un disparador de vista rápida en cualquier lugar con `[peek_quick_view id="123"]`, o el alias más corto `[peek id="123"]`. Atributos opcionales: `text` (etiqueta personalizada) y `style` (`text`, `icon` o `icon_text`). El modal y sus activos se cargan automáticamente dondequiera que aparezca el shortcode.

Peek se desarrolla al aire libre. El código, los problemas abiertos y el historial de versiones disponibles en https://github.com/wppoland/plogins-peek, los informes de errores y los parches son bienvenidos allí.

== Installation ==

1. Cargue el complemento en `/wp-content/plugins/plogins-peek`, o instálelo a través de Complementos → Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Visite el menú <strong>Peek</strong> en wp-admin para configurar la etiqueta del botón y el contenido modal.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Sí. Peek requiere una instalación activa de WooCommerce.

= Does it use jQuery? =

El propio script de interfaz del complemento es JavaScript básico sin dependencia de jQuery. Cuando un producto tiene variaciones, el script de variación incluido en WooCommerce (que a su vez usa jQuery) se pone en cola para que el formulario de variación funcione como se esperaba.

= Where does the quick-view button appear? =

En la página de la tienda y en los bucles de archivo de productos (categorías, etiquetas, taxonomías), después de cada producto. No cambia las páginas de productos individuales.

= Does the modal support add to cart? =

Sí. Peek muestra el formulario nativo de añadir al carrito de WooCommerce dentro del modo de compra rápida, incluida la cantidad y las opciones de productos variables.

= Does it work with variable products? =

Sí. Los productos variables utilizan el formulario de variación propio de WooCommerce dentro del modal del producto, por lo que los compradores pueden elegir una variación antes de agregarla al carrito.

= Will it cause layout shift? =

No. El modal está oculto hasta que se abre y se superpone a la página, por lo que al abrirlo nunca se redistribuye el contenido existente.

= Can I place a quick-view button manually? =

Sí. Utilice `[peek_quick_view id="123"]` o `[peek id="123"]` para añadir un activador de vista rápida del producto en diseños personalizados.


= Does this plugin work on WordPress Multisite? =

Sí. Este complemento es compatible con WordPress Multisite. Activarlo en red o activarlo en sitios individuales; Cada sitio mantiene su propia configuración y datos.

== Screenshots ==

1. El modo de vista rápida que muestra la galería de productos, el precio y el formulario para añadir al carrito.
2. La pantalla de configuración de Peek.

== External Services ==

Peek no se conecta a ningún servicio externo. El modo de vista rápida recupera su fragmento de producto de tu propio sitio a través de `admin-ajax.php` de WordPress (la acción `peek_quick_view`), por lo que ningún comprador o datos de producto salen de su servidor. Los únicos datos almacenados por Peek son dos opciones de WordPress que crea, `peek_settings` (tu configuración modal y de botones) y `peek_db_version`, ambas eliminadas cuando se elimina el complemento. Peek no envía correos electrónicos ni carga scripts, fuentes ni análisis de terceros.

== Changelog ==

= 1.0.1 =
* Primera versión estable.

= 0.3.1 =
* Renombrado a Plogins Peek para WooCommerce para obtener un nombre de complemento más distintivo.

= 0.3.0 =
* Nuevo: ubicación del botón de bucle, debajo de la tarjeta o superposición en la miniatura (desplazar el cursor/enfocar).
* Nuevo: código corto `[peek]` como alias para `[peek_quick_view]`.

= 0.2.0 =
* Nuevo: código abreviado `[peek_quick_view]` para colocar un activador de vista rápida en cualquier lugar, con atributos opcionales `id`, `text` y `style`.
* Nuevo: fila de estado del stock en el modal, con un interruptor.
* Nuevo: recuento de miniaturas de galería configurable (0–12).
* Nuevo: estilo del botón de activación, texto, ícono o ícono + texto (solo ícono mantiene un nombre accesible).
* Nuevo: muestra el alcance, carga solo en la tienda/archivos, o también bucles relacionados con un solo producto/venta adicional.
* Nuevo: controles modales de Chrome en la página de configuración (título, etiqueta del botón de cerrar, texto de carga/error, texto del enlace de visualización del producto, etiqueta de SKU y opciones para el encabezado modal, el botón de cerrar y el cierre con clic en el fondo).
* Nuevo: `uninstall.php` elimina las opciones del complemento al eliminarlo.
* Ruta de dominio agregada para traducciones.

= 0.1.0 =
* Lanzamiento inicial: modal de vista rápida AJAX accesible para bucles de archivo y tienda de WooCommerce, con una página de configuración para la etiqueta del botón y el contenido modal.
