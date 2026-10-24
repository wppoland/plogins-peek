<?php
/**
 * Run: php tests/block-button-check.php
 *
 * The block Product Collection button must not double up with the button the
 * classic loop hooks print. WooCommerce fires those hooks inside a Product
 * Collection only on shop/taxonomy archives, for a collection inheriting the
 * template query, while its compatibility layer is on.
 */

declare(strict_types=1);

define('ABSPATH', __DIR__ . '/');
require __DIR__ . '/../src/Contract/HasHooks.php';
require __DIR__ . '/../src/Service/PeekService.php';

use Peek\Service\PeekService;

$cases = [
    // [isArchive, isInherited, layerDisabled, classic hooks cover it]
    [true, true, false, true],   // TT5 shop: WooCommerce prints the button.
    [false, false, false, false], // Homepage collection: the block filter must.
    [false, true, false, false], // Inherited but not an archive: no classic hooks.
    [true, false, false, false], // Custom collection on the shop page.
    [true, true, true, false],   // Compatibility layer switched off.
];

foreach ($cases as [$archive, $inherited, $disabled, $expected]) {
    $got = PeekService::classicHooksCoverBlock($archive, $inherited, $disabled);
    assert($got === $expected, sprintf('archive=%d inherited=%d disabled=%d: expected %s', $archive, $inherited, $disabled, var_export($expected, true)));
}

echo "block-button-check: OK\n";
