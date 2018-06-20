<?php

yap('page_title', 'Test dynamic form fields');


?>
<style>
.hidden-print.line-through {text-decoration:line-through;}
th.hidden-print.line-through, td.hidden-print.line-through {color:#ddd; background-color:#eee;}
td.hidden-print.line-through .flag-icon {display:none;}
.table-narrow tr>th, .table-narrow tr>td {padding:8px 4px!important;}
.table-narrow tr>th:first-child, .table-narrow tr>td:first-child {padding-left:16px!important;}
.table-narrow tr>th:last-child, .table-narrow tr>td:last-child {padding-right:16px!important;}
td input:focus, td select:focus, td textarea:focus, button:focus {border-color:#000!important;}
</style>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">Test multiple fields</h6>
        </div>
        <table id="tbl" class="table table-narrow table-condensed">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Content</th>
                    <th>Note</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr id="stem" class="hide">
                    <td width="25%"><select class="form-control" name="type[]"><option>Type 1</option><option>Type 2</option><option>Type 3</option></select></td>
                    <td width="50%"><input type="text" class="form-control" name="name[]"></td>
                    <td width="25%"><input type="text" class="form-control" name="note[]"></td>
                    <td width="10"><i class="rowdel fa fa-trash cursor-pointer text-danger"></i></td>
                </tr>
            </tbody>
        </table>
        <div class="panel-body text-right"><button id="rowadd" class="btn btn-default">+ Add row</button></div>
    </div>
</div>
<?
$js = <<<'TXT'
$('#rowadd').on('click', function(){
    var tr = $('tr#stem').clone(true, true).removeAttr('id').appendTo('#tbl tbody').removeClass('hide').find(':input:eq(0)').focus();
    return false;
})
$('#tbl').on('click', 'i.rowdel', function(){
    $(this).closest('tr').remove();
});
TXT;

$this->registerJs($js);