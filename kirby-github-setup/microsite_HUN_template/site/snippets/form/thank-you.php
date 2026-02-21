<?php if (kirby()->session()->get('estimate-form')): ?>
<p>Köszönjük <?php echo kirby()->session()->get('estimate-form')->data('name'); ?> a kérését.</p>
<?php endif; ?>