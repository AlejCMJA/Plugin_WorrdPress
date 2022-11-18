<?php 
/**
 * Plugin Name: Payment JCAMPOS
 * Plugin URI: https://github.com/AlejCMJA/Plugin_WorrdPress.git
 * Description: Este es un plugin de pasarela de pago
 * Version: 1.0.0
 * Author: Julian Alejandro Campos Mairena
 * Author URI: https://github.com/AlejCMJA
 * License: GPL2
 */

add_action('wp_footer', function(){?>

<script>
	alert('Hola, esto pronto sera pronto un Plugin de una pasarela de pago para WordPress');
</script>
<?php }  ,9999); ?>