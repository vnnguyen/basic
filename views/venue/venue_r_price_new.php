<?
use yii\helpers\Html;
use yii\helpers\Markdown;

?>
<style>
    table th{ font-weight: 600 }
</style>
<div class="tab-pane" id="t-prices_new">
    <div class="tab-content">
        <div class="row mb-20">
            <div class="col-md-3">
                Select a date:<br>
                <input id="seldate_new" type="text" data-today-button="<?= NOW ?>" data-venue-id="<?= $theVenue['id']?>" class="form-control" name="" value="">
            </div>
        </div>
        <?php if (count($table_price) > 0) {
            foreach ($table_price as $t_name => $table) {
        ?>
        <div id="wrap-tables">
            <div class="table-responsive">
            <strong><?= $t_name?></strong>
            <table class="table table-bordered t_cutcol">
            	<thead>
            		<?php
            		$arr_head = [];
            		foreach ($table as $k => $row) {
            			if(strpos($row, '/h') !== false) {
                    	$row = preg_replace('/(\<td(.*)\>)\[.+\](\<\/td\>)/', '',$row);
            				$arr_head[] = $k;


            			?>
						<?= str_replace(['/h', 'td>', '<td'], ['', 'th>', '<th'], $row)?>
                    <?php }
                    	$row = preg_replace('/(\<td(.*)\>)\[.+\](\<\/td\>)/', '',$row);
                    	$table[$k] = $row;
                	} ?>
            	</thead>
                <tbody>
                    <?php foreach ($table as $k => $row) {?>
						<?
							if(!in_array($k, $arr_head))
						echo $row?>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <br>
            <?php
            }
        }?>
    </div>
        </div>
        
</div>
<?php
$js = <<<'JS'
$.fn.datepicker.language['vi'] = {
    days: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
    daysShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    daysMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    months: ['Tháng giêng', 'Tháng hai', 'Tháng ba', 'Tháng tư', 'Tháng năm', 'Tháng sáu', 'Tháng bảy', 'Tháng tám', 'Tháng chín', 'Tháng mười', 'Tháng mười một', 'Tháng mười hai'],
    monthsShort: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
    today: 'Hôm nay',
    clear: 'Xoá',
    dateFormat: 'mm/dd/yyyy',
    timeFormat: 'hh:ii aa',
    firstDay: 1
};

$('#seldate_new').datepicker({
    firstDay: 1,
    todayButton: new Date(),
    clearButton: true,
    autoClose: true,
    language: 'en',
    dateFormat: 'dd/mm/yyyy',
    onSelect: function(fd, d, picker) {
        if (!d) return;
        var val = fd;
        if (val == '') {
            return;
        }
        var venue_id = $('#seldate_new').data('venue-id');
        $.ajax({
            method: 'GET',
            url: '/venues/price_table',
            datatype: 'json',
            data: {
                venue_id: venue_id,
                date: val,
            },
        })
        .done(function(data){
            $('#wrap-tables').empty();
            $.each(JSON.parse(data), function(index, table){
                var arr_k = [];
                var tbl_html = '<div class="responsive"><table class="table table-bordered t_cutcol"> <strong>'+ 
                index +'</strong><thead>';
                $.each(table, function(in_d, row){
                    if(row.search('/h') != -1){
                        arr_k[in_d] = in_d;
                        var re = /\<td\>\[.+\]\<\/td\>/;
                        row = row.replace(re, '');
                        row = row.replace(/\<td/mg, '<th');
                        row = row.replace(/td\>/mg, 'th>');

                        tbl_html += row;
                    }
                });
                tbl_html += '</thead><tbody>';
                $.each(table, function(in_d, row){
                    if(arr_k.indexOf(in_d) == -1){
                        var re = /\<td\>\[.+\]\<\/td\>/;
                        row = row.replace(re, '');
                    	tbl_html += row;
                    }
                });
                tbl_html += '</tbody></table></div><br />';
                $('#wrap-tables').append(tbl_html);

            });
            cutCol();

        })
        .fail(function(data){
            if (data.message) {
                alert(data.message)
            } else {
                alert('Request failed. Please try again.')
            }
        })
    }
});



cutCol();
function cutCol()
{
 
$.each($('.t_cutcol'), function(t_i, table){
     $(table).find('tr th').each(function(i) {
        //select all tds in this column
        var tds = $(this).parents('table')
             .find('tr td:nth-child(' + (i + 1) + ')');
        if(tds.is(':empty')) {
            //hide header
            $(this).remove();
            // $(this).hide();
            //hide cells
            tds.remove();
            // tds.hide();
        } 
    });
});






	$.each($('.t_cutcol'), function(t_i, table){
		var cnt_col = [];
		var Rows = $(table).find('tr');
		$.each(Rows, function(r_i, row){
			cnt_col[r_i] = 0;
			$(row).find('td,th').each(function(c_i, col){
				if($(col).text().length > 0 ){
					cnt_col[r_i] ++;
				}
			});
		});
		var max_col = cnt_col[0];

		for(var i = 0; i < cnt_col.length; i++) {
		  if(cnt_col[i] > max_col) max_col = cnt_col[i];
		}

		$.each(Rows, function(r_i, row){
			$(row).find('td, th').each(function(c_i, col){console.log(c_i);
				if(c_i >= max_col){
					$(col).remove();
				}
			});
		});
	});
}

// $('#seldate_new').datepicker().data('datepicker').selectDate(new Date());

JS;

$this->registerJs($js);