<?
$this->params['breadcrumb'] = [
    ['Messages', 'messages'],
];

$this->params['actions'] = [];
if (isset($theNote['id']) && in_array(SEG2, ['r', 'u', 'd'])) {
    $this->params['actions'][] = [
        ['icon'=>'eye', 'title'=>'View', 'link'=>'messages/r/'.$theNote['id'], 'active'=>SEG2 == 'r'],
        ['icon'=>'edit', 'title'=>'Edit', 'link'=>'messages/u/'.$theNote['id'], 'active'=>SEG2 == 'u'],
    ];
    $this->params['actions'][] = [
        ['icon'=>'trash-o', 'title'=>'Delete', 'link'=>'messages/d/'.$theNote['id'], 'active'=>SEG2 == 'd', 'class'=>'text-danger'],
    ];
}
