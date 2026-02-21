<?php if($site->webhookUrl()->isNotEmpty()) : ?>

    <form action="<?php echo $site->webhookUrl() ?>" method="POST" accept-charset="UTF-8">

        <input type="hidden" name="_utf8" value="✓">

        <div>
            <label for="name" class="required">Keresztnév</label>
            <input name="name" type="text" required placeholder="Keresztnév">
        </div>

        <div>
            <label for="lastname" class="required">Vezetéknév</label>
            <input name="lastname" type="text" required placeholder="Vezetéknév">
        </div>

        <div>
            <label for="email" class="required">Email cím</label>
            <input name="email" type="email" required placeholder="pelda@email.hu">
        </div>

        <div>
            <label for="phone" class="required">Telefonszám</label>
            <input name="phone" type="tel" required placeholder="+36 70 123 4567">
        </div>

        <div>
            <label for="service" class="required">Szolgáltatások</label>
            <select name="service" required>
                <?php
                $services = $site->index()->filterBy('template', 'in', ['service']);
                foreach($services as $service): ?>
                <option value="<?= $service->title() ?>"><?= $service->title() ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-full">
            <label for="message" class="required">Megjegyzés vagy üzenet</label>
            <textarea name="message" rows="8" cols="40" required placeholder="Írja le az igényét..."></textarea>
        </div>

        <input type="submit" value="Küldés" class="btn btn--cta">
    </form>

<?php endif ?>
