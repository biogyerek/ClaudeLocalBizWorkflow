document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[action*="hook.eu2.make.com"], form[action*="hook.make.com"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('input[type="submit"]');
            const originalButtonValue = submitButton.value;
            
            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.value = 'Küldés...';
            
            // Remove any existing messages
            const existingMsg = this.parentElement.querySelector('.form-message');
            if (existingMsg) existingMsg.remove();
            
            // Send form data
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'form-message form-success';
                    successMsg.innerHTML = '✓ Köszönjük! Az üzenetet sikeresen elküldtük. Hamarosan jelentkezünk!';
                    successMsg.style.cssText = 'margin-top: 1rem; padding: 1rem; background-color: #f0e4d7; color: #6b4423; border: 1px solid #d4c5b0; border-radius: 4px;';
                    this.parentElement.insertBefore(successMsg, this.nextSibling);
                    
                    // Reset form
                    this.reset();
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                // Show error message
                const errorMsg = document.createElement('div');
                errorMsg.className = 'form-message form-error';
                errorMsg.innerHTML = '✗ Hiba történt. Kérjük, próbálja újra később vagy írjon nekünk közvetlenül.';
                errorMsg.style.cssText = 'margin-top: 1rem; padding: 1rem; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;';
                this.parentElement.insertBefore(errorMsg, this.nextSibling);
            })
            .finally(() => {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.value = originalButtonValue;
            });
        });
    });
});
