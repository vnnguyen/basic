<?
// The task
$q = $db->query('SELECT * FROM at_tasks WHERE id=%i LIMIT 1', seg3);
$theTask = $q->countReturnedRows() > 0 ? $q->fetchRow() : show_error(404);

$rName = '';
if ($theTask['rid'] != 0 && $theTask['rtype'] != 'none') {
    if ($theTask['rtype'] == 'case') $q = $db->query('SELECT name FROM at_cases c WHERE c.id=%i LIMIT 1', $theTask['rid']);
    if ($theTask['rtype'] == 'tour') $q = $db->query('SELECT name FROM at_tours t WHERE t.id=%i LIMIT 1', $theTask['rid']);
    if ($theTask['rtype'] == 'user') $q = $db->query('SELECT name FROM persons WHERE id=%i LIMIT 1', $theTask['rid']);
    if ($theTask['rtype'] == 'venue') $q = $db->query('SELECT name FROM venues WHERE id=%i LIMIT 1', $theTask['rid']);
    if ($q->countReturnedRows() > 0) {
        $rName = $q->fetchScalar();
        if ($theTask['rtype'] == 'case') $rURL = 'cases/r/'.$theTask['rid'];
        if ($theTask['rtype'] == 'tour') $rURL = 'tours/r/'.$theTask['rid'];
        if ($theTask['rtype'] == 'user') $rURL = 'users/r/'.$theTask['rid'];
        if ($theTask['rtype'] == 'venue') $rURL = 'venues/r/'.$theTask['rid'];
    } else {
        $rName = '';
    }
}

// The task users
$q = $db->query('SELECT * FROM at_task_user WHERE task_id=%i', $theTask['id']);
$theTaskUsers = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$q = $db->query('SELECT id, lname, email FROM persons WHERE email!="" AND is_member="yes" ORDER BY lname');
$emailToList = $q->countReturnedRows() > 0 ? $q->fetchAllRows() : array();

$fv = new hxFormValidation();
$fv->setRules('description', 'Description of task', 'trim|required|max_length[500]|htmlspecialchars');
$fv->setRules('who', 'Responsible', 'required');
$fv->setRules('due_date', 'Ngày đến hạn', 'trim|required|exact_length[10]');
$fv->setRules('due_time', 'Giờ đến hạn', 'trim|min_length[4]|max_length[5]');
$fv->setRules('mins', 'Số phút dự tính', 'trim|required|is_natural_no_zero');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($fv->run()) {
        if (!isset($_POST['due_date']) || $_POST['due_date'] == '') {
            $_POST['fuzzy'] = 'date';
            $_POST['due_dt'] = '2029-12-31 23:59:59';
        } else {
            if (!isset($_POST['due_time']) || $_POST['due_time'] == '') {
                $_POST['fuzzy'] = 'time';
                $_POST['due_dt'] = $_POST['due_date'].' 23:59:59';
            } else {
                $_POST['fuzzy'] = 'none';
                $_POST['due_dt'] = $_POST['due_date'].' '.substr($_POST['due_time'], 0, 2).':'.substr($_POST['due_time'], -2).':00';
            }
        }

    $q = $db->query('UPDATE at_tasks SET uo=%s, ub=%s, status=%s, description=%s, mins=%i, due_dt=%s, fuzzy=%s, is_priority=%s, is_all=%s WHERE id=%i LIMIT 1',
            NOW,
            myID,
            'on',
            $_POST['description'],
            $_POST['mins'],
            $_POST['due_dt'],
            $_POST['fuzzy'],
            $_POST['is_priority'],
            $_POST['is_all'],
            $theTask['id']
        );
        
        $db->query('DELETE FROM at_task_user WHERE task_id=%i', $theTask['id']);
        
        //hMailer::send();
    
        $m_to_list = array();
        foreach ($emailToList as $et) {
            if (in_array($et['id'], $_POST['who'])) {
                $db->query('INSERT INTO at_task_user (task_id, user_id, assigned_dt) VALUES (%i, %i, %s)', $theTask['id'], $et['id'], NOW);
                if (myID != $et['id']) $m_to_list[] = $et['email'];
            }
        }
        
        if (!empty($m_to_list)) {
            $email = new fEmail();
            $email->clearRecipients();      
            $email->setFromEmail('no-reply@amicatravel.com', 'Amica Travel IMS');
            $email->addRecipient('nobody@amicatravel.com');
            
            $email->setSubject('['.myName.'][Giao nhiệm vụ] '.$_POST['description']);
            $email->setBody('Gửi từ IP : '.$appUser->ipAddress.'
Giao cho: '.implode(',', $m_to_list).'
Liên quan đến: '.(isset($rURL) ?  $rName.' / '.SITE_URL.$rURL : '(Không có liên quan)').'

'.$_POST['description'].'
========================
Thời hạn: '.$due.'

------------------------
Xem chi tiết: '.SITE_URL.'tasks');
            foreach ($m_to_list as $mToEmail) $email->addBCCRecipient($mToEmail);
            $email->send();
        }
        if ($theTask['rtype'] != '') {
            redirect($theTask['rtype'].'s/r/'.$theTask['rid']);
        } else {
            if (in_array(myID, $_POST['who'])) {
                redirect('tasks');
            } else {
                redirect('tasks/assigned');
            }
        }
        exit;
  }
} else {
    foreach ($theTask as $k=>$v) $_POST[$k] = $v;
    $_POST['due_date'] = substr($_POST['due_dt'], 0, 10);   
    if ($_POST['fuzzy'] == 'time') {
        $_POST['due_time'] = '';
    } else {
        $_POST['due_time'] = substr($_POST['due_dt'], 11, 5);
    }
    $_POST['who'] = array();
    foreach ($theTaskUsers as $tu) $_POST['who'][] = $tu['user_id'];
     
}

$pageM = 'tasks';
$pageT = 'Sửa nhiệm vụ';
$pageB = array(
    anchor('tasks', 'Tasks'),
    anchor(uris, 'Sửa'),
    );

include_once('_hd_limitless.php'); ?>
<div class="col-md-3">&nbsp;</div>
<div class="col-md-6">
    <div class="alert alert-warning"><strong>CHÚ Ý:</strong> Sau khi sửa, nhiệm vụ sẽ ở trạng thái <em>Chưa được hoàn thành</em></div>
    <?=$fv->getErrorMessage('<div class="alert alert-error">', '</div>')?>
    <form class="form-horizontal" method="post" action="">
        <fieldset>
            <input type="hidden" name="fuzzy" value="none" />
            <input type="hidden" name="is_all" value="yes" />
            <div class="control-group">
                <label class="control-label" for="">Liên quan đến</label>
                <div class="controls"><?=isset($rURL) ? anchor($rURL, $rName) : '(Không có liên quan)'?></div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">Việc cần làm và thời hạn (ngày, giờ)</label>
                <div class="controls">
                    <p><input type="text" placeholder="Miêu tả việc cần làm, tối đa 255 chữ" style="width:100%" name="description" value="<?=$_POST['description']?>" /></p>
                    <p>
                        <input type="text" class="input-small datepicker" name="due_date" maxlength="10" value="<?=$_POST['due_date']?>" placeholder="yyyy-mm-dd" >
                        <input type="text" class="text-center" style="width:70px" name="due_time" value="<?=$_POST['due_time']?>" maxlength="5" placeholder="hh:mm" />
                        <select class="input-small" name="is_priority" style="width:100px;">
                            <option value="no">--</option>
                            <option value="yes" <?=$_POST['is_priority'] == 'yes' ? 'selected="selected"' : ''?>>ưu tiên</option>
                        </select>
                        dự tính <input type="text" style="width:50px;" class="text-center" name="mins" maxlength="3" value="<?=$_POST['mins']?>"> phút
                    </p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">Giao cho</label>
                <div class="controls">
                    <select class="select2" style="width:100%" data-placeholder="Chọn một hoặc nhiều người" name="who[]" multiple>
                        <? foreach ($emailToList as $u) { ?>
                        <option value="<?=$u['id']?>" <?= in_array($u['id'], $_POST['who']) ? 'selected="selected"' : ''?>><?=$u['lname']?> (<?=$u['email']?>)</option>
                        <? } ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="">Nếu giao cho nhiều người thì</label>
                <div class="controls">
                    <label class="radio">&nbsp;&nbsp;&nbsp; <input type="radio" name="is_all" value="yes" <?=$_POST['is_all'] == 'yes' ? 'checked="checked"' : ''?> /> Việc hoàn thành khi mọi người được giao hoàn thành</label>
                    <label class="radio">&nbsp;&nbsp;&nbsp; <input type="radio" name="is_all" value="no" <?=$_POST['is_all'] == 'no' ? 'checked="checked"' : ''?> /> Việc hoàn thành khi có 1 người được giao hoàn thành</label>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Ghi các thay đổi</button>
            </div>
        </fieldset>
    </form>
</div>
<div class="col-md-3">
    <h3>Chỉ dẫn</h3>
    <p>Định dạng ngày: bắt buộc là yyyy-mm-dd (năm-tháng-ngày, giữ số 0 ở đầu nếu có).</p>
    <p>Định dạng giờ: 08h15, 08:15, 0815 đều được, nhưng giữ số 0 ở đầu nếu có.</p>
    <p>Để trống ngày và giờ nếu không cần thời hạn chi tiết.</p>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js"></script>
<script>
$(function(){
    $(".select2").select2();
    $('input.datepicker').daterangepicker({
        locale: {
            firstDay: 1,
            format: 'YYYY-MM-DD'
        },
        singleDatePicker: true,
        showDropdowns: true
    });
});
</script>
<? include_once('_ft_limitless.php');