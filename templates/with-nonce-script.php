<?php declare(strict_types=1); // -*- coding: utf-8 -*-
/*
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @var $dataLayerName string
 * @var $gtmId string
 * @var $nonce string
 */

?>

<script nonce='<?php echo $nonce ?>'>
    (
        function( w, d, s, l, i ) {
            w[l] = w[l] || [];
            w[l].push( {'gtm.start': new Date().getTime(), event: 'gtm.js'} );
            var f = d.getElementsByTagName( s )[0],
                j = d.createElement( s ), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            var n = d.querySelector('[nonce]');
            n && j.setAttribute( 'nonce', n.nonce || n.getAttribute('nonce') );
            f.parentNode.insertBefore( j, f );
        }
    )( window, document, 'script', '<?= esc_js($dataLayerName); ?>', '<?= esc_js($gtmId); ?>' );
</script>
