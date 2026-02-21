<?php if ($form->error($field)): ?>
    <p class="error-text"><?php echo implode('<br>', $form->error($field)) ?></p>
<?php endif; ?>