<?php if($site->email()->isNotEmpty()) : ?>

    <form action="<?php echo $page->url() ?>" method="POST">

        <div>
            <label for="name" class="required">Keresztnév</label>
            <input<?php if ($form->error('name')): ?> class="error"<?php endif; ?> name="name" type="text" value="<?php echo $form->old('name') ?>" placeholder="Keresztnév">
        </div>

        <div>
            <label for="lastname" class="required">Vezetéknév</label>
            <input<?php if ($form->error('lastname')): ?> class="error"<?php endif; ?> name="lastname" type="text" value="<?php echo $form->old('lastname') ?>" placeholder="Vezetéknév">
        </div>

        <div>
            <label for="email" class="required">Email cím</label>
            <input<?php if ($form->error('email')): ?> class="error"<?php endif; ?> name="email" type="email" value="<?php echo $form->old('email') ?>" placeholder="pelda@email.hu">
        </div>

        <div>
            <label for="phone" class="required">Telefonszám</label>
            <input<?php if ($form->error('phone')): ?> class="error"<?php endif; ?> name="phone" type="tel" value="<?php echo $form->old('phone') ?>" placeholder="+36 70 123 4567">
        </div>

        <div>
            <label for="service" class="required">Szolgáltatás</label>
            <select name="service">
            <?php $value = $form->old('service') ?>
                <option value="">-- Válasszon szolgáltatást --</option>

                <?php
                $services = $site->index()->filterBy('template', 'in', ['service']);
                foreach($services as $service): ?>

                <option value="<?= $service->slug() ?>"><?= $service->title() ?></option>

                <?php endforeach ?>

            </select>
            <?php snippet('form/error', ['field' => 'service']) ?>
        </div>

        <div class="form-full">
            <label for="message" class="required">Üzenet</label>
            <textarea<?php if ($form->error('message')): ?> class="error"<?php endif; ?> name="message" rows="8" cols="40" placeholder="Írja le kérését részletesen..."><?php echo $form->old('message') ?></textarea>
        </div>

        <?php echo csrf_field() ?>
        <?php echo honeypot_field() ?>
        <input type="submit" value="Küldés" class="btn btn--cta">
    </form>
    <?php if ($form->success()): ?>
        <?php go('thank-you') ?>
    <?php else: ?>
        <?php snippet('uniform/errors', ['form' => $form]) ?>
    <?php endif; ?>

<?php endif ?>