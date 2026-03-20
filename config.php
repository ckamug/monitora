<?php
// This file outputs JavaScript configuration
require_once __DIR__ . '/configuracoes.php';
?>
<script>
// Global application configuration
const APP_Config = {
    baseURL: '<?php echo URL; ?>',
    ajaxPath: '<?php echo URL; ?>public/componentes/',
    
    // Helper function to construct AJAX URLs
    getAjaxUrl: function(component, action) {
        return this.baseURL + 'public/componentes/' + component + '/model/' + action;
    }
};
</script>
