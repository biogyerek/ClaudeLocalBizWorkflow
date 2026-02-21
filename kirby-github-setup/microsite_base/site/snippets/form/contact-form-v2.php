<?php if($site->email()->isNotEmpty()) : ?>

    <form action="<?php echo $page->url() ?>" method="POST">

        <div>
            <label for="name" class="required">First Name</label>
            <input<?php if ($form->error('name')): ?> class="error"<?php endif; ?> name="name" type="text" value="<?php echo $form->old('name') ?>">
        </div>

        <div>
            <label for="lastname" class="required">Last Name</label>
            <input<?php if ($form->error('lastname')): ?> class="error"<?php endif; ?> name="lastname" type="text" value="<?php echo $form->old('lastname') ?>">
        </div>

        <div>
            <label for="email" class="required">Email</label>
            <input<?php if ($form->error('email')): ?> class="error"<?php endif; ?> name="email" type="email" value="<?php echo $form->old('email') ?>">
        </div>

        <div>
            <label for="phone" class="required">Phone Number</label>
            <input<?php if ($form->error('phone')): ?> class="error"<?php endif; ?> name="phone" type="tel" value="<?php echo $form->old('phone') ?>">
        </div>


        <div class="form-full">
            <label for="message" class="required">Comment or Message</label>
            <textarea<?php if ($form->error('message')): ?> class="error"<?php endif; ?> name="message" rows="8" cols="40"><?php echo $form->old('message') ?></textarea>
        </div>

        <?php echo csrf_field() ?>
        <?php echo honeypot_field() ?>
        <input type="submit" value="Submit" class="btn btn--cta">
    </form>
    <?php if ($form->success()): ?>
        <div class="box box--success">Thank you for your message. We will get back to you soon!</div>
    <?php else: ?>
        <?php snippet('uniform/errors', ['form' => $form]) ?>
    <?php endif; ?>


<?php endif ?>