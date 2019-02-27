<?php
use yii\helpers\Html;
use kartik\select2\Select2;

?>
<style>
  .q_class {
    background: #f9f2f4;
    color: #9c1d3d;
    border-radius: 3px;
}
</style>

<div class="col-md-6 card">
    <div class="card-body">
        <form>
            <div class="cp_form" id="wrap-cptForm">
                <div class="row entry_info">
                    <div class="col-md-12 form-group field-cptour-who_pay">
                        <label class="control-label" for="cptour-who_pay"> text </label>
                        <input id="cptour-input" class="form-control"type="text">
                    </div>
                    <div class="col-md-6 form-group" id="wrap-ncc">
                        <label class="control-label" for="cptour-venue_id">Provider</label>
                        <?= Html::dropDownList('ncc', '', [], [
                            'class' => 'form-control',
                            'id' => 'cptour-venue_id',
                            'data-selected' => 'no'
                        ]) ?>

                        <?= Html::input('hidden', 'ncc_name', '', [
                            'id' => 'ncc_name',
                        ]) ?>
                    </div>
                    <div class="col-md-6 form-group " id="wrap-dv">
                        <label class="control-label" for="cptour-dv_id">service</label>
                        <?= Html::input('text', 'dv', '', [
                            'class' => 'form-control',
                            'id' => 'cptour-dv_id',
                        ]) ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
$js = <<<TXT
    var NCC_ID = 0;
    var data_source_dv = null;
    var DATA_S = null;

    var TEXT_NCC = '';
    var TERM = '';
    var gitElement = $("#cptour-venue_id").select2({
        ajax: {
            url: "/cptour/search_ncc",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data, params) {
                // console.log(data);return false;
                TERM = params.term;
                params.page = params.page || 1;
                return {
                    results: $.map(data.suggestions, displayItem),
                    pagination: {
                        more: (params.page * 20) < data.total_count
                    }
                };
            }
        },
        cache: true,
        placeholder: "Search",
        escapeMarkup: function (markup) { return markup; },
        templateResult: function(data) {
            return data.html;
        },
        templateSelection: function(repo) {
            return repo.name || repo.text;
        },
        allowClear: true,
        minimumInputLength: 2
    })
    .on('change', function() {
        NCC_ID = $(this).val();
        if (NCC_ID > 0) {
            $.ajax({
                url: "/cptour/list_dv",
                type: "GET",
                data: {id_ncc: NCC_ID},
                dataType: "json",
                success: function(response){
                    data_source_dv = $.map(response.dv, function (obj) {
                        obj.id = obj.id;
                        obj.text = obj.text || obj.name; // replace name with the property used for the text
                        return obj;
                    });
                    $('#cptour-dv_id').html('');
                    if(data_source_dv.length == 0) {
                        if ($('#cptour-dv_id').hasClass("select2-hidden-accessible")) {
                            $('#cptour-dv_id').select2('destroy');
                        }
                        console.log("service null");
                        $('#cptour-dv_id').focus();
                        return false;
                    }

                    $('#cptour-dv_id')
                    .append($('<option>', {value: '', text : ''}))
                    .select2({
                        placeholder: "Select service",
                        data: data_source_dv,
                        tags: "true",
                        maximumInputLength: 20
                        }).on("load", function(e) {}).trigger('load');
                        $('#cptour-dv_id').select2('open');

                },
                error: function(xhr, ajaxOptions, thrownError) { alert('No response from server');
                }
            });
        }
    });

    $(document).on('keyup', '.select2-search__field', function (e) {
        TEXT_NCC = $(this).val();
        });
    $(gitElement).on('select2:closing', function(e){
        if(TEXT_NCC == ''){
            TEXT_NCC = 'search';
        } else {
            $('#ncc_name').data('selected', 'no').val(TEXT_NCC);

        }
        $('#wrap-ncc').find('.select2-selection__placeholder').text(TEXT_NCC);

    });
    $(gitElement).on('select2:close', function(e){
        if ($('#cptour-dv_id').hasClass("select2-hidden-accessible")) {
            $('#cptour-dv_id').select2('open');
        } else {
            $('#cptour-dv_id').focus();
        }
    });
    $(gitElement).on('select2:select', function(e){
        $('#ncc_name').data('selected', 'yes').val('');
        TEXT_NCC = '';
    });
    $(gitElement).on('select2:open', function(e){
        if(TEXT_NCC == 'search') TEXT_NCC = '';
        $(document).find('.select2-search__field').val(TEXT_NCC);
    });
    $(gitElement).on('select2:unselecting', function(e){
        $('#cptour-dv_id').select2('destroy');
    });
    $(document).on('focus', '#wrap-ncc .select2, #wrap-dv .select2', function() {
        $(this).siblings('select').select2('open');
    });
    function displayItem(repo) {
        var image = repo.image == ''? 'https://secure.gravatar.com/avatar/679185b8d4c3ad74555f48ca99fa86bf?d=wavatar' : repo.image;
        var lower_name = repo.name.toLowerCase();
        var name_style = lower_name.replace(TERM.toLowerCase(), '<span class="q_class">' + TERM.toLowerCase() + '</span>');
        return {
            id : repo.id,
            text :    repo.name,
            html : '<div class="clearfix"><img style="width:48px; height:48px; float:left; margin-right:10px" class="img-circle" src="'+image+'">'+
            '<p class="text-capitalize select2_text">'+ name_style +
            '</p>'+
            '<span class="other_info">detail</span>'+
            '</div>'
        };
    }

TXT;
$this->registerJs($js);
                                                                                            ?>
