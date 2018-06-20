<?
use yii\helpers\Html;
use yii\helpers\Markdown;

include('_users_inc.php');

yap('page_icon', 'tag');
yap('page_title', 'User tags');


?>
<div class="col-md-12">
    <? if (!empty($theUsers)) { ?>
    <p><strong><?= count($theUsers) ?> USERS WITH TAG <em><?
foreach ($theTags as $tag) {
    if ($tag['id'] == $tagId) {
        echo $tag['name'];
        break;
    }
} ?></em></strong></p>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table class="table table-xxs table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>G</th>
                        <th>Born</th>
                        <th>N</th>
                        <th>Email</th>
                        <th>Tours</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach ($theUsers as $user) { ?>
                    <tr>
                        <td><?= Html::a($user['id'], '/users/r/'.$user['id'], ['target'=>'_blank']) ?></td>
                        <td><?= $user['lname'] ?> <?= $user['fname'] ?></td>
                        <td><?= strtoupper(substr($user['gender'], 0, 1)) ?></td>
                        <td><?= $user['byear'] ?></td>
                        <td><?= strtoupper($user['country_code']) ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?
                        foreach ($theTours as $tour) {
                            if ($tour['user_id'] == $user['id']) {
                                echo Html::a($tour['op_code'], '/products/op/'.$tour['id'], ['target'=>'_blank', 'title'=>$tour['op_name']]), ' &nbsp; ';
                            }
                        }
                        ?></td>
                    </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>
    </div>

    <? } ?>

    <p><strong>ALL USER TAGS</strong></p>
    <p><?
$tags = [];
foreach ($theTags as $tag) {
    $tags[] = Html::a($tag['name'], '@web/users/tags?tag='.$tag['id']);
}
echo implode(', ', $tags);
?>
    </p>
</div>