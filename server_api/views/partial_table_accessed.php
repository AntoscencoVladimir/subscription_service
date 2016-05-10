<?php if (!empty($entities)) : ?>
    <section class="container">
        <h2>Выполнено переходов за период:</h2>
        <table class="table">
            <thead>
            <tr>
                <th>E-mail</th>
                <th>IP</th>
                <th>Страна</th>
                <th>Браузер</th>
                <th>Дата перехода по ссылке</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($entities as $entity) : ?>
                <tr>
                    <td><?php echo $entity->getEmail() ?></td>
                    <td><?php echo $entity->ip ?></td>
                    <td><?php echo $entity->country ?></td>
                    <td><?php echo $entity->user_agent ?></td>
                    <td><?php echo $entity->created ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>