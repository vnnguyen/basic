<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
            <?php foreach ($result[20583] as $k => $value) {
                if ($k == 'id') continue;

                ?>
                <th><?= $k?></th>
            <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) {?>
            <tr>
            <?php foreach ($row as $k => $value) {
                if ($k == 'id') continue;
                ?>
                <td><?= $value?></td>
            <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>