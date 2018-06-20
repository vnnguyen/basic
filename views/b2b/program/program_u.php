<?
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
include('_program_inc.php');
if (!isset($price_table)) {
    $price_table = null;
}
$css = <<<TXT
////////////css table prices/////////////////////////
#wrap_table_price {display: inline-block; position: relative;}
.table-editable { position: relative; }
.table-editable .glyphicon {font-size: 20px;
}
.table-remove {color: #700; cursor: pointer; }
.table-remove :hover {color: #f00; }
#wrap_table_price, .wrap_add_extensions{ margin-bottom: 10px}
.table-up, .table-down, .table-merge, .table-unmerge {color: #007; cursor: pointer; }
.table-up:hover, .table-down:hover, .table-merge:hover {color: #00f; }
.table-add {color: #070; cursor: pointer; position: absolute; top: 5px; right: 0; }
.table-add:hover {color: #0b0; }
td.focus, th.focus {background: #fff!important; border: 1px solid #3399FF!important; box-shadow: 0px 0px 3px #cdcdcd!important}
#gen_table, #add_extensions { padding: 3px 5px}
table tr#th_row { background: #f3f3f3}
table tr td.th_column { background: #f3f3f3}
table tr td, table tr th { width: 100px;}
table tr td:last-child, table tr th:last-child { width: 135px!important;}
.icons-list { position: absolute;  right: 10}
.wrap_icon_menu { position: relative; float: right;}
.ex_remove {color: #EF5350; display: inline-block;  padding: 1px; position: absolute; top: 0; right: 5px;}
.ex_remove:hover { background: #ccc;}


#wrap_extensions .item {display: inline-block; color: #166DC8; cursor: pointer; margin-right: 19px; border: 1px solid #ddd; text-align: center;margin-top: 10px; padding: 2px 10px; border-radius: 8px; position: relative;}
#wrap_extensions .extension_item{ display: inline-block; padding: 6px 20px; width: 100%;}
#wrap_extensions .item .ext_content{ color: #666}
TXT;
$this->registerCss($css);
$productTypeList = [
'private'=>'Normal tour',
'b2b-prod'=>'Product tour',
'extension'=>'Extension',
];

Yii::$app->params['page_breadcrumbs'] = [
['B2B', 'b2b'],
['Products', 'b2b/programs'],
];

if ($theProgram->isNewRecord) {
    Yii::$app->params['page_title'] = 'New tour program';
    Yii::$app->params['page_breadcrumbs'][] = ['New', 'b2b/programs/c'];
} else {
    Yii::$app->params['page_title'] = 'Edit tour program: '.$theProgram['title'];
    Yii::$app->params['page_breadcrumbs'][] = ['View', 'b2b/programs/r/'.$theProgram['id']];
    Yii::$app->params['page_breadcrumbs'][] = ['Edit', 'b2b/programs/u/'.$theProgram['id']];
}

// cac file anh banner
$files = [];

if (file_exists(Yii::getAlias('@webroot').'/upload/devis-banners/')) {
    $files = scandir(Yii::getAlias('@webroot').'/upload/devis-banners/', 1) ;
    asort($files);
}

$fileNameList = [];
foreach ($files as $k=>$v) {
    if ($v != '.' && $v != '..') {
        $fileNameList[] = ['name'=>$v];
    }
}

$conds = <<<TXT
h3. Ce prix comprend :

* Hébergement pour tout le parcours dans les hôtels listés au programme ou, en cas d’indisponibilité de ceux-ci, dans des hôtels équivalents. 
* Tous les déplacements selon le programme en véhicule privatif.
* Les repas comme mentionnés dans le programme (B = Petit Déjeuner ; L = Déjeuner ; D = Dîner).
* Guides accompagnateurs francophones pour tout le circuit.
* Droits d'entrée des sites à visiter.
* Les billets de volss domestiques : Hué - Hanoi, Buon Me Thuoc – Da Nang par Vietnam Airlines (la plus grande compagnie aérienne du Vietnam)
* Un bateau collectif avec une cabine privée à deux dans la baie d’Halong, 
* Les frais de dossier
* Les taxes
* Tous les services logistiques nécessaires pour l'organisation du programme.

h3. Ce prix ne comprend pas :

* Vols et taxes d'aéroport internationaux depuis/ vers votre pays.
* Pourboire, boissons, téléphone et tout ce qui n’est pas clairement mentionné dans la rubrique « Le prix comprend ». (Pour le pourboire pour guide et chauffeur, à prévoir environ de 3 à 4 Euros par jour par personne, si vous êtes contents de leurs services).
TXT;

$more = <<<TXT
h3. Les plus d’Amica Travel 

* Un petit guide culturel (de 80 pages) du Vietnam mis à la disposition de chaque voyageur dès l’arrivée
* Cadeau de bienvenue
* Boissons fraiches durant les transferts routiers
* Suivi 24h/24 du voyage, depuis le bureau de Hanoi, par un agent clientèle dédié
* Présence téléphonique en France

h3. Conditions de paiement

* Si vous souhaitez payez en Euros, la somme à payer sera reconvertie en Euros selon le taux de change de référence  publié par la Banque Centrale Européenne à la date la plus proche de celle du paiement. Ce taux sera consulté sur le Site Internet de cette Banque, en cliquant sur le lien : "http://www.ecb.int/stats/exchange/eurofxref/html/index.en.html":http://www.ecb.int/stats/exchange/eurofxref/html/index.en.html
* Un acompte de 25% du prix total est à verser par virement bancaire ou par carte bancaire via Internet dès la réservation
* Le solde de 75% est à payer au commencement du voyage, en liquide ou par carte bancaire
* Les frais bancaires liés au paiement sont à la charge du client

h3. Conditions d’annulation

En cas d’annulation du voyage, le client doit payer des pénalités qui correspondent:

* à 3% du prix total du voyage, si son annulation parvient à Amica Travel dans un délai égal ou supérieur à 45 jours avant le commencement du voyage ;
* à 5% du prix total du voyage, si son annulation parvient à Amica Travel de 31 à 45 jours avant le commencement du voyage ;
* à 10% du prix total du voyage, si son annulation parvient à Amica Travel de 15 à 30 jours avant le commencement du voyage ;
* à 15% du prix total du voyage, si son annulation parvient à Amica Travel de 7 à 14 jours avant le commencement du voyage ;
* à 20% du prix total du voyage, si son annulation parvient à Amica Travel de 72 heures à 6 jours avant le commencement du voyage ;
* à 25 % du prix total du voyage, si son annulation parvient à Amica Travel moins de 72 heures avant le commencement du voyage.
TXT;

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
?>
<div class="col-md-8">
    <div class="panel panel-default">
        <div class="panel-body">
            <? if ($theProgram->isNewRecord) { ?>
            <div class="alert alert-info">
                <i class="fa fa-fw fa-info-circle text-info"></i>
                You can also create a new itinerary by copy an existing one. Just <a class="alert-link" href="/ct">view all itineraries</a>, select one you want to copy and then select the <kbd>Copy as new</kbd> menu item.
            </div>
            <? } ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($theProgram, 'title') ?></div>
                <div class="col-md-6"><?= $form->field($theProgram, 'offer_type')->dropdownList($productTypeList)->label('Program type') ?></div>
            </div>
            <div class="row">
                <div class="col-md-12"><?= $form->field($theProgram, 'about')->label('Description') ?></div>
            </div>
            <?= $form->field($theProgram, 'tags') ?>
            <?= $form->field($theProgram, 'esprit')->textArea(['rows'=>3]) ?>
            <?= $form->field($theProgram, 'points')->textArea(['rows'=>3]) ?>
            <?= $form->field($theProgram, 'img_upload')->fileInput([
                'class' => 'file-input',
                'data-show-upload' => "false"
                ]) ?>
            <div class="row">
                <div class="col-md-3"><?= $form->field($theProgram, 'language')->dropdownList(isset($languageList) ? $languageList: [] , ['prompt'=>'- Select -'])->label('Language') ?></div>
                <div class="col-md-3"><?= $form->field($theProgram, 'day_count')->label('Days') ?></div>
                <div class="col-md-3"><?= $form->field($theProgram, 'pax') ?></div>
                <?php if ($theProgram->offer_type != 'b2b-prod' && $theProgram->offer_type != 'extension'): ?>
                    <div class="col-md-3" class="df"><?= $form->field($theProgram, 'day_from')->label('Start date') ?></div>
                <?php endif ?>
            </div>
            <?php if ($theProgram->offer_type != 'b2b-prod' && $theProgram->offer_type != 'extension'): ?>
                <?= $form->field($theProgram, 'intro')->textArea(['rows'=>3]) ?>
            <?php endif ?>
            
            <p><strong>PRICES AND PROMOTIONS</strong></p>
            <?= $form->field($theProgram, 'prices')->textArea(['rows'=>15, 'style'=>'font-family:Courier New, mono; font-size:14px; color:#000;']) ?>
            <?php if ($theProgram->offer_type != 'b2b-prod' && $theProgram->offer_type != 'extension'): ?>
                <div class="row wrap_op_price">
                    <div class="col-md-3"><?= $form->field($theProgram, 'price') ?></div>
                    <div class="col-md-3"><?= $form->field($theProgram, 'price_unit')->dropdownList(['EUR'=>'EUR', 'USD'=>'USD', 'VND'=>'VND']) ?></div>
                    <div class="col-md-3"><?= $form->field($theProgram, 'price_for')->dropdownList(['personne'=>'personne', 'groupe'=>'groupe']) ?></div>
                    <div class="col-md-3"><?= $form->field($theProgram, 'price_until') ?></div>
                </div>
            <?php endif ?>
            <?= $form->field($theProgram, 'promo')->textArea(['rows'=>10]) ?>

            <?= $form->field($theProgram, 'conditions')->textArea(['rows'=>10]) ?>
            <?= $form->field($theProgram, 'others')->textArea(['rows'=>10]) ?>
            
            <!-- /////////////////////////////////////////////////////////////// -->
            <?php if ($theProgram->offer_type == 'b2b-prod' || $theProgram->offer_type == 'extension'): ?>
                <div id="wrap_table_price" data-action="<?= ($theProgram->scenario == 'product/u/prod')? 'update': 'create';?>">
                    <?php if ($theProgram->scenario == 'product/c/prod' || $price_table == null){ ?>
                        <div class="wrap_gen_table">
                            <a id="gen_table">+ Click here to add prices</a>
                        </div>
                    <?php } ?>
                    <div class="wrap_icon_menu" style="<?= ($price_table == null)? 'display: none': '';?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i> <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a id="table-add_new_row"><i class="fa fa-plus"></i> Add new row</a></li>
                            <li><a id="table-add_new_col"><i class="fa fa-plus"></i> Add new column</a></li>
                            <li><a id="table-remove_last_col"><i class="fa fa-trash"></i> Remove last column</a></li>
                            <li><a id="table-remove_empty"><i class="fa fa-trash"></i> Remove table</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <div id="table" style="<?= ($price_table == null)? 'display: none': '';?>" class="table-responsive table-editable">
                        <table class="table table-narrow">
                            <caption>Table price</caption>
                            <tbody id="sortable-list-second">
                                <?php if ($theProgram->scenario == 'product/u/prod' && $price_table != null): ?>
                                    <?php
                                    $prices = json_decode($price_table->v);
                                    //header row
                                    $row_header = $prices[0];
                                    $num_col = 0;
                                    echo '<tr id="th_row">';
                                    foreach ($row_header as $td) {
                                        // var_dump($row_header);die();
                                        echo '<th class="text-center" contenteditable="true">'.$td->id.'</th>';
                                        $num_col ++;
                                    }
                                    echo '<th class="text-center" width="90px"></th></tr>';

                                    foreach ($prices as $tr) {
                                        $total_col = 0;
                                        echo '<tr>';
                                        foreach ($tr as $key => $td) {
                                            $content = '';
                                            if ($total_col <  $num_col) {
                                                $td_data = explode('/',$td->value);

                                                if (count($td_data) >= 2) {
                                                    $colspan = intVal($td_data[1]);
                                                } else {
                                                    $colspan = 1;
                                                }
                                                $total_col += $colspan;
                                                $content = $td_data[0];
                                                if ($key == 0) {
                                                    echo '<td colspan="'.$colspan.'" class="text-center th_column" contenteditable="true">'.$content.'</td>';
                                                } else {
                                                    echo '<td colspan="'.$colspan.'" class="text-center" contenteditable="true">'.$content.'</td>';
                                                }
                                            }
                                        }
                                        echo '<td class="text-center text-right actions_td ui-sortable-handle"> <span class="table-remove"><i class="fa fa-trash" aria-hidden="true"></i></span> <span class="table-up" style=""><i class="fa fa-arrow-up" aria-hidden="true"></i></span> <span class="table-down" style=""><i class="fa fa-arrow-down" aria-hidden="true"></i></span> <span class="table-merge"><i class="fa fa-compress" aria-hidden="true"></i></span> <span class="table-unmerge" style="display: none;"><i class="fa fa-undo" aria-hidden="true"></i></span></td></tr>';
                                    }
                                    ?>
                                 <?php endif ?>
                             </tbody>
                         </table>
                     </div>
                     <input type="hidden" name="table_price" id="export">

                 </div>
                 <?php if ($theProgram->offer_type != 'extension'): ?>
                    <div class="wrap_add_extensions">
                        <a id="add_extensions" >+ Click here to add extensions</a>
                        <input type="hidden" name="extensionList" value="">
                        <div id="wrap_extensions">
                            <?php if ($theProgram->scenario == 'product/u/prod' && $extensions != null): ?>
                                <?php foreach ($extensions as $ext): ?>
                                    <div class="item" data-extension_id="<?= $ext->id?>">
                                        <span class="ex_remove"><i class="fa fa-remove"></i></span>
                                        <a class="extension_item" href="#" target="_blank" >
                                            <span class="ex_title"><?= $ext->title?></span>
                                        </a>
                                        <div class="ext_content">
                                        <?php foreach ($arr_ext_days as $id => $days): ?>
                                            <?php if ($ext->id == $id): ?>
                                                <?php $i = 0; ?>
                                                <?php foreach ($days as $day): ?>
                                                    <?php $i++; ?>
                                                    <span>Day <?= $i.': '.$day['name'].' ('.$day['meals'].')'?></span><br>
                                                <?php endforeach ?>
                                                
                                            <?php endif ?>
                                        <?php endforeach ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    </div>
                 <?php endif ?>
                <div class="clearfix"></div>
            <?php endif ?>
            



            <!-- /////////////////////////////////////////////////////////// -->
            <?php if ($theProgram->offer_type != 'b2b-prod' && $theProgram->offer_type != 'extension'): ?>
                <p class="note_for_amica"><strong>NOTE (FOR AMICA ONLY)</strong></p>
                <?= $form->field($theProgram, 'summary')->textArea(['rows'=>3]) ?>
            <?php endif ?>
            <div class="text-right"><?= Html::submitButton('Save changes', ['class'=>'btn btn-primary']) ?></div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-body">
            <?= $form->field($theProgram, 'image')->dropdownList(ArrayHelper::map($fileNameList, 'name', 'name'), ['id'=>'header-image', 'prompt'=>'- Select -']) ?>
            <div id="image-preview" class="mb-1em">
                <? if ($theProgram['image'] != '') { ?>
                <img class="img-responsive thumbnail" src="<?= DIR ?>upload/devis-banners/small/<?= $theProgram['image'] ?>" />
                <? } ?>
            </div>
            <p><strong>CHỈ DẪN</strong></p>
            <p>Các trường miêu tả text dài có thể đánh dấu chữ đậm nghiêng như sau:</p>
            <p>*đậm* --> <b>đậm</b>
                <br />_nghiêng_ --> <i>nghiêng</i>
                <br />* List item --> &middot; List item
            </p>

            <p>Thông tin giá nhập vào cần tuân theo dạng thức cố định, cách nhau bằng các dấu hai chấm. Mỗi thông tin viết trên một dòng.</p>
            <p><code>
                OPTION: Giải thích về option<br />
                + Ville : Hotel : Chambre : www.abcd.com<br />
                + Ville : Hotel : Chambre : www.abcd.com<br />
                - Prix / personne en chambre double : 1234<br />
                - Prix / personne en chambre individuelle : 2345<br />
                OPTION: Giải thích về option<br />
                + Ville : Hotel : Chambre : www.xyzt.com<br />
                + Ville : Hotel : Chambre : www.xyzt.com<br />
                - Prix / personne en chambre double : 5678<br />
                - Prix / personne en chambre individuelle : 9012<br />
            </code></p>
            <p>Chọn giá đại diện và đơn vị tính giá ở bên cạnh</p>

            <? if (isset($theDays)) { ?>
            <p><strong>ITINERARY</strong></p>
            <ol>
                <? foreach ($theDays as $day) { ?>
                <li><?= $day['name'] ?> (<?= $day['meals'] ?>)</li>
                <? } ?>
            </ol>
            <? } ?>
        </div>
    </div>
</div>
<?php if ($theProgram->offer_type == 'b2b-prod'): ?>
    <!-- extension Modal -->
    <div class="modal fade" id="search_extension_modal" role="dialog">
        <div class="modal-dialog dialog-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Search extensions</h4>
                </div>
                <div class="modal-body">
                    <select id="select_extension" name='extension' class="form-control">
                    </select>
                </div>
                <div class="modal-footer">
                    <span class="btn btn-primary" id="extensionSave">Save</span>
                    <span class="btn btn-default" data-dismiss="modal" id="btn-close-modal">Cancel</span>
                </div>
            </div>
        </div>
    </div>
    <!--end extension Modal -->
<?php endif ?>

<?
ActiveForm::end();
$js = <<<TXT
$('#header-image').change(function(){
    var image = $(this).val();
    if (image == '') {
        $('#image-preview').html('<img class="img-responsive thumbnail" src="http://placehold.it/300x100" />');
    } else {
        $('#image-preview').html('<img class="img-responsive thumbnail" src="/upload/devis-banners/small/'+image+'" />');
    }
});
$('#product-day_from, #product-price_until').datepicker({
    format: "yyyy-mm-dd",
    weekStart: 1,
    todayBtn: "linked",
    clearBtn: true
});


// $('form#w0').on('afterValidateAttribute', function (event, messages, errorAttributes) {
//     if (errorAttributes != null) {
//         alert(1);
//     }
// });


/////////////////set price table//////////////////////////

var TABLE = $('#table');
var EXPORT = $('#export');
var FIRST_TH_CONTENT = '#';
var COUNT_ROWS;
var ARR_HEADER = ['Taille du groupe', '2 pax', '3 pax', '4 pax', '5 pax', '6 pax', '7 pax', '8 pax', '9 pax', '10 pax', '11 pax', '12 pax'];
var NUM_OF_COL = ARR_HEADER.length + 1;
var ARR_EXTENSION = [];
var ACTION = $('#wrap_table_price').data('action');

    $( "#sortable-list-second" ).sortable({
        handle: ".actions_td",
        // start: function( event, ui ) {
        //     index_b = ui.item.find('.i-count').text();
        // },
        stop: function( event, ui ) {
            setUpDown();
        },
    });
    $(document).on('click', '.ex_remove', function(){
        var clicked = $(this),
        wrap = clicked.closest('#wrap_extensions'),
        ex = clicked.closest('.item'),
        ex_id = ex.data('extension_id');
        ARR_EXTENSION = [];
        jQuery.each(wrap.find('.item'), function(i, item){
            if ($(item).data('extension_id') != ex_id) {
                ARR_EXTENSION[i] = $(item).data('extension_id');
            }
        });
        console.log(ARR_EXTENSION);
        $('form').find('[name="extensionList"]').val('');
        $('form').find('[name="extensionList"]').val(ARR_EXTENSION.toString());
        $(ex).fadeOut(400, function(){
            $(ex).detach();
        });
        return false;
    });
    $(document).ready(function(){
        $("#product-img_upload").fileinput({
           'showUpload':false
        });

        ///////////////////////////add extensions...///////////
        jQuery.each($('#wrap_extensions').find('.item'), function(i, item){
            ARR_EXTENSION[i] = $(item).data('extension_id');
        });
        $('form').find('[name="extensionList"]').val(ARR_EXTENSION.toString());
        if (ACTION != null) {
            if (ACTION == 'create') {
                $('.wrap_icon_menu, #table').hide();
            } else {
                setUpDown();
            }
        }
        
        $('#add_extensions').click(function(){

            $('#search_extension_modal').modal('show');
            $('#select_extension').select2({
                placeholder: "Search",
                minimumInputLength: 2,
                ajax: {
                    url: "$baseUrl/b2b/programs/search_extension",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return  {
                            results: $.map(data.items, function (obj) {
                                obj.id = obj.id;
                                obj.text = obj.text || obj.title;
                                return obj;
                            }),
                            pagination: {
                                more: (params.page * 50) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
            });
            $('.select2').css('width','100%');
        }).on("change", function(e) {
            var lastValue = e.currentTarget.value;
            var lastText = e.currentTarget.textContent;
        });

        $('#extensionSave').click(function(){
            var ex_id = $('#select_extension').val();
            var title = $('#select_extension  option:last-child').text();
            if (ex_id != null && title != null) {
                if (ARR_EXTENSION.indexOf(ex_id) == -1) {
                    ARR_EXTENSION.push(ex_id);
                    $.ajax({
                        method: 'GET',
                        url: '$baseUrl/b2b/programs/data_extension',
                        data: {ct_id: ex_id},
                        dataType: 'json'
                    }).done(function(response){
                        console.log(response);
                        var last_item = $('#wrap_extensions').find('.item:last');
                        $(last_item).append('<div class="ext_content"></div>');
                        jQuery.each(response, function(index, item){
                            var day = index + 1;
                            var span = '<span>Day '+day+': '+item.name+' ('+item.meals+')</span><br/>';
                            $(last_item).find('.ext_content').append(span);
                        });
                    });
                    $('#wrap_extensions').append('<div class="item" data-extension_id="'+ex_id+'"><a class="extension_item" href="#" target="_blank"><span class="ex_title">'+title+'</span></a><span class="ex_remove"><i class="fa fa-remove"></i></span></div>');
                    $('#search_extension_modal').modal('hide');
                    $('form').find('[name="extensionList"]').val(ARR_EXTENSION.toString());
                }
            }
        });


        ///////////////////////////////////////////////////////
        $('#table-remove_empty').click(function(){
            var clicked = $(this),
                wrap_menu = clicked.closest('.wrap_icon_menu'),
                html = '<div class="wrap_gen_table"> <a id="gen_table">+ Click here to add prices</a> </div>';
            $(html).insertBefore(wrap_menu);
            $('.wrap_icon_menu, #table').hide();
            $('#sortable-list-second').empty();
        });
        $('#table-add_new_col').click(function(){
            var th_last = TABLE.find('table tr th:last');
            var new_th = '<th class="text-center" contenteditable="true">New column</th>';
            if (th_last.index() == 0) {
                $(new_th).removeAttr('contenteditable');
            }
            $(new_th).insertBefore(th_last);
            NUM_OF_COL ++;

            var trs = TABLE.find('table tr:not(.th_row)');
            jQuery.each(trs, function(index, tr){
                var td = $(tr).find('td:last');
                var td_new = '<td class="text-center" contenteditable="true">value</td>';
                if ($(td).index() == 0) {
                    $(td_new).addClass('th_column');
                    $(td_new).insertBefore($(td));
                } else {
                    $(td_new).insertBefore($(td));
                }
            });
        });
        $('#table-remove_col_at').click(function(){
            var col_index = prompt('The index column to remove: ');
            if (col_index != null && col_index != '') {
                removeCol(col_index);
            }
        });
        function removeCol(col_index){
            if (col_index > 0 && col_index < NUM_OF_COL) {
                // remove column in head row
                var th = TABLE.find('table tr#th_row th:eq('+col_index+')');
                if (th.length == 1) {
                    $(th).remove();
                    var trs = TABLE.find('table tr:not(#th_row)');
                    jQuery.each(trs, function(index, tr){
                        var tds = $(tr).find('td:not(:last, :first)');
                        if (tds.length == NUM_OF_COL - 2) {
                           $(tr).find('td:eq('+col_index+')').detach();
                       } else {
                        console.log('has merge');
                    }
                });
                NUM_OF_COL --;
            }
        }
    }
    $('#table-remove_last_col').click(function(){
        var th_last = TABLE.find('table tr th:last');
        if (th_last.index() != 0) {
            NUM_OF_COL --;
        }
        $(th_last).prev().remove();
        var trs = TABLE.find('table tr:not(#th_row)');
        jQuery.each(trs, function(index, tr){
            var td = $(tr).find('td:last');
            if ($(td).prev().length > 0 && $(td).prev().prop('colspan') > 1) {
                $(td).prev().prop('colspan', $(td).prev().prop('colspan') - 1)
            } else {
                $(td).prev().remove();
            }
        });
    });
    $('#table-add_new_row').click(function () {
        var tr = '<tr>';
        for(var i = 0; i < NUM_OF_COL; i++) {
            if (i == 0) {
                tr += '<td class="text-center th_column" contenteditable="true">'+i+'</td>';
            } else if (i == (NUM_OF_COL-1)) {
                tr += '<td class="text-center text-right actions_td"> <span class="table-remove"><i class="fa fa-trash" aria-hidden="true"></i></span> <span class="table-up"><i class="fa fa-arrow-up" aria-hidden="true"></i></span> <span class="table-down"><i class="fa fa-arrow-down" aria-hidden="true"></i></span> <span class="table-merge"><i class="fa fa-compress" aria-hidden="true"></i></span> <span class="table-unmerge"><i class="fa fa-undo" aria-hidden="true"></i></span></td>';
            } else {
                tr += '<td class="text-center" contenteditable="true" >'+i+'</td>';
            }
        }
        tr += '</tr>';
        TABLE.find('table tbody').append(tr);

        setUpDown();
        var last_tr = TABLE.find('table tbody tr:last');
        last_tr.find('td:first').focus();
    });
        // A few jQuery helpers for exporting only
    jQuery.fn.pop = [].pop;
    jQuery.fn.shift = [].shift;
    $('form#w0').on('beforeValidate', function (e) {
        var rows = TABLE.find('tr:not(:hidden)');
        var headers = [];
        var data = [];

            // Get the headers (add special header logic here)
        $(rows.shift()).find('th:not(:empty)').each(function () {
            headers.push($(this).text().toString().toLowerCase());
        });
            // Turn all existing rows into a loopable array
        rows.each(function () {
            var td = $(this).find('td');
            var h = {};
                // Use the headers from earlier to name our hash keys
            headers.forEach(function (header, i) {
                h[i] = {'id': header, 'value': td.eq(i).text()};
                if (td.eq(i).prop('colspan') != '' && parseInt(td.eq(i).prop('colspan')) > 1) {
                    h[i] = {'id': header, 'value': td.eq(i).text()+"/"+td.eq(i).prop('colspan')};
                }
            });
            data.push(h);
        });
        if (data.length > 0) {
            EXPORT.text('');
            // Output the result
            EXPORT.val(JSON.stringify(data));
            data = [];
        }
        
        return true;
    });

    
});
$(document).on('click', '#gen_table', function(){
        $(this).closest('.wrap_gen_table').hide();
        $('.wrap_icon_menu, #table').fadeIn();

        var tr = '<tr id="th_row">';

        if (NUM_OF_COL > 2) {
            for(var i = 0; i < NUM_OF_COL; i++) {
                if (i == (NUM_OF_COL-1)) {
                    tr += '<th  class="text-center" width="90px"></th>';
                } else {
                    tr += '<th  class="text-center" contenteditable="true" >'+ARR_HEADER[i]+'</th>';
                }
            }
            tr += '</tr>';
            TABLE.find('table tbody').append(tr);
        }

    });
//////////////////////out ready////////////////
    $(document).on('keydown', 'td, th', function(event){
        // if(13 == event.which) { // press ENTER-key
        //     var row = $(this).closest('tr'),
        //     row_next = row.next();
        //     if (row_next == null || row_next.length == 0){
        //         var tr = '<tr>';
        //         for(var i = 0; i < NUM_OF_COL; i++) {
        //             if (i == 0) {
        //                 tr += '<td class="text-center th_column" contenteditable="true">'+i+'</td>';
        //             } else if (i == (NUM_OF_COL-1)) {
        //                 tr += '<td class="text-center text-right"> <span class="table-remove"><i class="fa fa-trash" aria-hidden="true"></i></span> <span class="table-up"><i class="fa fa-arrow-up" aria-hidden="true"></i></span> <span class="table-down"><i class="fa fa-arrow-down" aria-hidden="true"></i></span> <span class="table-merge"><i class="fa fa-compress" aria-hidden="true"></i></span></td>';
        //             } else {
        //                 tr += '<td class="text-center" contenteditable="true" >'+i+'</td>';
        //             }
        //         }
        //         tr += '</tr>';
        //         TABLE.find('table tbody').append(tr);
        //         setUpDown();
        //         var last_tr = TABLE.find('table tbody tr:last');
        //         last_tr.find('td:first').focus();
        //         return false;
        //     }
        //     row_next.find('td:first').focus();
        //     return false;
        // } else 
        if(9 == event.which) {  // press TAB-key
            if ($(this).index() == NUM_OF_COL-1) {
                var row = $(this).closest('tr'),
                row_next = row.next();
                if (row_next != null && row_next.length == 1) {
                    row_next.find('td:first').focus();
                    return false;
                }
            }
        } else if(38 == event.which) {  // press UP-key
            var td_index = $(this).index();
            var row = $(this).closest('tr'),
            row_prev = row.prev();
            if (row_prev != null && row_prev.length == 1) {
                row_prev.find('td:eq('+td_index+')').focus();
            }
            return false;
        } else if(40 == event.which) {  // press DOWN-key
            var td_index = $(this).index();
            var row = $(this).closest('tr'),
            row_next = row.next();
            if (row_next != null && row_next.length == 1) {
                row_next.find('td:eq('+td_index+')').focus();
            }
            return false;
        }
        // else if(27 == event.which) {  // press ESC-key
        //     tdObj.html(preText);
        // }//{ console.log(event.which); return;}
    });
    $(document).on('focus', 'th, td', function(){
        $(this).addClass('focus');
    });
    $(document).on('blur', 'th, td', function(){
        $(this).removeClass('focus');
    });
    $(document).on('click','.table-remove', function () {
        $(this).parents('tr').detach();
        setUpDown();
    });

    $(document).on('click','.table-up', function () {
        var row_clicked = $(this).parents('tr');
        if (row_clicked.index() === 1) return; // Don't go above the header
        row_clicked.prev().before(row_clicked.get(0));
        setUpDown();
    });
    $(document).on('click', '.table-unmerge', function(){
        var clicked = $(this),
        tr = clicked.closest('tr'),
        tds = tr.find('td');
        jQuery.each(tds, function(index, td){
            var colspan_td = parseInt($(td).prop('colspan'));
            if (colspan_td > 1) {
                $(td).prop('colspan', 1);
                for(var i = 1; i < colspan_td; i++) {
                    var html = $(td).clone().removeClass('th_column, focus').text('value');
                    $(html).insertAfter($(td))
                }
            }
        });
        clicked.hide();
    });
    $(document).on('click', '.table-merge', function(){
        var clicked = $(this);
        var tr = $(clicked).closest('tr');
        var rang = prompt('what column want merge? (ex:0-2) ');
        if (rang != null) {
            var arr_col = rang.split('-');
            var check_exist_col = true;
            if (arr_col.length == 2 && arr_col[0] != arr_col[1]) {
                if (isNaN(arr_col[0]) || isNaN(arr_col[1]))
                    return false;
                if (parseInt(arr_col[0]) < parseInt(arr_col[1])) {
                    var min = arr_col[0];
                    var max = arr_col[1];
                } else {
                    var max = arr_col[0];
                    var min = arr_col[1];
                }
                if (max >= $(tr).find('td').length || $(tr).find('td:eq('+max+')').index() == $(tr).find('td').length - 1) {
                    return false;
                } else {
                    console.log($(tr).find('td:eq('+max+')').length - 1);
                    console.log($(tr).find('td:eq('+max+')').index());
                }
                if ($(tr).find('td:eq('+arr_col[0]+')').length > 0 
                    && $(tr).find('td:eq('+arr_col[1]+')').length > 0) {

                    var td_prev = $(tr).find('td:eq('+min+')').prev();
                    var td_next = $(tr).find('td:eq('+max+')').next();
                    var colspan = 0;
                    var arr_to_merge = [];
                    jQuery.each($(tr).find('td'), function(index, td){
                        if (index >= min && index <= max) {
                            var colspan_td = parseInt($(td).prop('colspan'));
                            if (colspan_td > 1) {
                                colspan = colspan + colspan_td;
                            } else {
                                colspan ++;
                            }
                            arr_to_merge[index] = td;
                        }
                    });

                    var html = '<td class="text-center" colspan="'+colspan+'" contenteditable="true"></td>';
                    $(html).insertBefore(td_next);
                    if (colspan == NUM_OF_COL - 1) {
                        $(td_next).prev().addClass('th_column');
                    }
                    jQuery.each(arr_to_merge, function(i, item){
                        if (item != undefined) {
                            $(item).detach();
                        }
                    });
                    if ($(tr).find('td').length < NUM_OF_COL) {
                        $(tr).find('.table-unmerge').show();
                    } else {
                        $(tr).find('.table-unmerge').hide();
                    }
                }
            } else { alert('undefined')}
        }
    });
    $(document).on('click','.table-down', function () {
        var row = $(this).parents('tr');
        row.next().after(row.get(0));
        setUpDown();
    });

    function setUpDown()
    {
        var trs = TABLE.find('table tr:not(#th_row)');
        if (trs.length == 1) {
            var tr = trs[0];
            $(tr).find('.table-up').hide();
            $(tr).find('.table-down').hide();
            if ($(tr).find('td').length < NUM_OF_COL) {
                $(tr).find('.table-unmerge').show();
            } else {
                $(tr).find('.table-unmerge').hide();
            }
        } else {
            if (trs.length > 0) {
                jQuery.each($(trs), function(index, row){
                    var action_up = $(row).find('.table-up');
                    var action_down = $(row).find('.table-down');
                    action_up.show();
                    action_down.show();
                    if (index == 0) {
                        action_up.hide();
                        action_down.show();
                    }
                    if (index == trs.length - 1) {
                        action_up.show();
                        action_down.hide();
                    }
                    if ($(row).find('td').length < NUM_OF_COL) {
                        $(row).find('.table-unmerge').show();
                    } else {
                        $(row).find('.table-unmerge').hide();
                    }
                });
            }
        }
    }
TXT;
$this->registerJsFile($baseUrl.'/js/core/libraries/jquery_ui/interactions.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile($baseUrl.'/js/plugins/uploaders/fileinput.min.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJsFile($baseUrl.'/js/pages/uploader_bootstrap.js', ['depends'=>'yii\web\JqueryAsset']);
$this->registerJs($js);
