
(function() {
    'use strict';
    
    // Confirm before delete with better UX
    document.addEventListener('submit', function(e) {
        if (e.target.matches('form[onsubmit*="confirm"]')) {
            // Custom confirm dialog could be added here
            console.log('Delete action confirmed');
        }
    });
    
    // Clear input field after successful submission
    const todoForm = document.querySelector('form[action*="todos.store"]');
    if (todoForm) {
        // Listen for form submission success
        // In real app, you'd use AJAX and clear on success
        todoForm.addEventListener('submit', function() {
            setTimeout(() => {
                const input = this.querySelector('input[name="title"]');
                if (input && !document.querySelector('.is-invalid')) {
                    input.value = '';
                }
            }, 100);
        });
    }
    
    // Auto-dismiss alerts
    const autoDismissAlerts = () => {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert.parentNode) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    };
    
    // Run on page load
    document.addEventListener('DOMContentLoaded', autoDismissAlerts);
    
})();