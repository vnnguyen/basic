<?php
use yii\helpers\Html;
?>

<div class="card table-responsive">
        <table class="table table-narrow table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Date of birth</th>
                    <th>country</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($theContacts as $contact) { ?>
                <tr>
                    <td> <?= $contact['id']?></td>
                    <td>
                        <div><?= $contact['fname']?></div>
                    </td>
                    <td>
                        <div><?= $contact['lname']?></div>
                    </td>
                    <td>
                        <?php if ($contact['gender'] == 'male') echo "Mr" ?>
                        <?php if ($contact['gender'] == 'female') echo "Ms" ?>
                    </td>
                    <td><?php if ($contact['bday'] != 0 && $contact['bmonth'] != 0 && $contact['byear'] != 0) { ?>
                        <?= $contact['bday'] ?>/<?= $contact['bmonth'] ?>/<?= $contact['byear'] ?>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($contact['country_code'] != '--') echo $contact['country_code'] ?>
                    </td>                    <td><?php
                    foreach ($contact['metas'] as $meta) {
                        if ($meta['format'] == 'email') {
                            echo '<div>', Html::a($meta['value'], ""), '</div>';
                        }
                    }
                    ?></td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>