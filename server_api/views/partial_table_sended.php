<?php if (!empty($entities)) : ?>
<section class="container">
    <h2>Отправлено писем за период:</h2>
    <table class="table">
        <thead>
        <tr>
            <th>E-mail</th>
            <th>ID Сервера</th>
            <th>Статус отправки</th>
            <th>Дата отправки</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($entities as $entity) : ?>
        <tr>
            <td><?php echo $entity->getEmail()?></td>
            <td><?php echo $entity->sender_server?></td>
            <td><?php echo $entity->status ? 'отправлено' : 'не отправлено' ?></td>
            <td><?php echo $entity->created ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>