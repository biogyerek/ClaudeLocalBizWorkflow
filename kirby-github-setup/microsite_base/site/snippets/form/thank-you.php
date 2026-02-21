<?php if (kirby()->session()->get('estimate-form')): ?>
<p>Thank you <?php echo kirby()->session()->get('estimate-form')->data('name'); ?> for your request.</p>
<?php endif; ?>