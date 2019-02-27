var venue_id = 0;
var id_ncc;


$(document).on('click', $('#formlink').find('[name="dv_link"]'), function(){
    Autocomplete();
});
$(document).on('click', '.remove_link', function(){
    var clicked = $(this);
    var cfirm = confirm('Unlink this cpt?');
    if (cfirm) {
        var cpt_id = $(this).data('id');
        $.ajax({
            url: "/appbasic/web/cpt/unlink",
            type:'GET',
            data: {cpt_id:cpt_id},
            dataType: 'json',
            success:function(response)
            {
                if (response.err) {
                    alert(response.err);
                    return;
                }
                var td = $(clicked).closest('td');
                $(clicked).remove();
                $(td).find('.links').remove();
                $(td).append('<input data-vid="'+response.success.dv_id+'" name="chk[]" value="'+response.success.dvtour_id+'" type="checkbox">');
            },
            error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
        });
    }
});


///////////////////////tooltip//////////////
$(document).on({
    mouseenter: function(){// Hover over code
        var title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<p class="my_tooltip"></p>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
    },
    mouseout: function(){
        // Hover out code
        $(this).attr('title', $(this).data('tipText'));
        $('.my_tooltip').remove();
    },
    mousemove: function(e){
        var mousex = e.pageX - 120; //Get X coordinates
        var mousey = e.pageY - 40; //Get Y coordinates
        $('.my_tooltip')
        .css({ top: mousey, left: mousex , zIndex: 999999})
    }

},'.masterTooltip');

//////////////////////////

$(document).ready(function(){
    jQuery.each($('.links'), function(){
        if ($(this).data('status') == 'not ok') {
            $(this).css('color', 'red')
        }
        if ($(this).data('status') == 'ok') {
            $(this).css('color', 'green')
        }
    });
    $('#formlink').find('[name="chk[]"]').on('click', function(){
        $('#formlink').find('[name="dv_link"]').val('');
        // $('#formlink').formValidation('resetForm');
        if($(this).prop('checked')) {
            if (venue_id == 0) {
                venue_id = $(this).data('vid');
                $('#formlink').find('[name="venue_id"]').val(venue_id);
            } else {
                if (venue_id != $(this).data('vid') ) {
                    $(this).prop('checked', false);
                    alert('Không cùng nhà cung cấp');
                }
            }
        } else {
            var i = 0;
            $('#formlink').find('[name="chk[]"]').each(function(index, item){
                if ($(item).prop('checked')) {
                    i ++;
                    return false;
                }
            });
            $('#formlink').find('[name="select_type"]').val('');
            $('#wrap-check').find('li a').removeClass('active');
            $('#wrap-check').find('li a:first').addClass('active');
            if (i == 0) {
                venue_id = 0;
                $('#formlink').find('[name="venue_id"]').val('');
            }
        }
    });
    $('.sugggest_link').on('click', function(){
        $('#linkForm').find('[name="name"]').val($(this).data('name'));
        $('#linkForm').find('[name="cpt_id"]').val($(this).data('id'));
        var dv_id = $(this).data('dv');
        id_ncc = $(this).closest('td').find('[name="chk[]"]').data('vid');
        $('#link-modal').on('show.bs.modal', function() {
            $.ajax({
                url: "/appbasic/web/cpt/get_data_link",
                type:'GET',
                data: {id:id_ncc},
                dataType: 'json',
                success:function(response)
                {
                    if (response == null) {
                        return;
                    }
                    $('#linkForm').find('[name="dv"]').empty();
                    jQuery.each(response, function(index, item){
                        $('#linkForm').find('[name="dv"]').append($('<option>', {
                                value: item.id,
                                text : item.name
                            }));
                    });
                    $('#linkForm').find('[name="dv"]').val(dv_id);
                },
                error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
            });

        });
        $('#link-modal').modal('show');
        return false;
    });
    $('.links').click(function(){
        $(this).toggleClass('fa fa-link checked fa fa-check');
        var cpt_id = $(this).data('id');
        $.ajax({
            url: "/appbasic/web/cpt/check_link",
            type:'GET',
            data: {id:cpt_id},
            success:function(response)
            {
                if (response == 1) {
                    return;
                } else {
                    alert('error !');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) { alert(xhr.responseText); }
        });
    });
//////////////////////////////////////
    $('#formlink').formValidation({
        framework: 'bootstrap',
        icon: false,
        fields: {
            'dv_link': {
                validators: {
                    notEmpty: {
                        message: 'The service is required'
                    }
                }
            },
            'venue_id': {
                validators: {
                    notEmpty: {
                        message: 'The venue ID is required'
                    }
                }
            },
        }
    });
//////////////////////////////////////
    $('#link_auto').on('click', function(){
        $('#list-option-modal').modal('show');
    });


    $('#wrap-check').find('li a').click(function(){
        if ($('#list_cpt_link').find('input[type="checkbox"]').length == 0) { return;}
        venue_id = 0;
        $('#wrap-check').find('input[type="checkbox"]').prop('checked', true);
        jQuery.each($('#list_cpt_link').find('input[type="checkbox"]'), function(index, item){
            if (venue_id == 0) {
                venue_id = $(this).data('vid');
                $('#formlink').find('[name="venue_id"]').val(venue_id);
                cpt_name = $(this).closest('tr').find('.unit').text().trim().toLowerCase();
                $(this).prop('checked', true);
            } else {
                if (venue_id != $(this).data('vid')) {
                    venue_id = 0;
                    jQuery.each($('#list_cpt_link').find('input[type="checkbox"]'), function(index, item){
                        $(this).prop('checked', false);
                    });
                    alert('Không cùng nhà cung cấp');
                    $('#wrap-check').find('input[type="checkbox"]').prop('checked', false);
                    return false;
                } else {
                    $(this).prop('checked', true);
                }
            }
        });
        $('#wrap-check').find('li a').removeClass('active');
        $(this).addClass('active');
    });
    $('#wrap-check').find('li a:first').click(function(){
        $('#formlink').find('[name="select_type"]').val('');
    });
    $('#wrap-check').find('li a:last').click(function(){
        $('#formlink').find('[name="select_type"]').val('allpages');
    });
    $('#wrap-check').find('input[type="checkbox"]').click(function(){
        var clicked = $(this);
        if ($(this).prop('checked')) {
            jQuery.each($('#list_cpt_link').find('input[type="checkbox"]'), function(index, item){
                if (venue_id == 0) {
                    venue_id = $(this).data('vid');
                    $('#formlink').find('[name="venue_id"]').val(venue_id);
                    $(this).prop('checked', true);
                } else {
                    if (venue_id != $(this).data('vid')) {
                        venue_id = 0;
                        $('#formlink').find('[name="venue_id"]').val("");
                        jQuery.each($('#list_cpt_link').find('input[type="checkbox"]'), function(index, item){
                            $(this).prop('checked', false);
                        });
                        alert('Không cùng nhà cung cấp');
                        clicked.prop('checked', false);
                        return false;
                    } else {
                        $(this).prop('checked', true);
                    }
                }
            });

        } else {
            jQuery.each($('#list_cpt_link').find('input[type="checkbox"]'), function(index, item){
                $(this).prop('checked', false);
            });
            venue_id = 0;
            $('#formlink').find('[name="venue_id"]').val("");
        }
        $('#wrap-check').find('li a').removeClass('active');
        $('#wrap-check').find('li a:first').addClass('active');
    });

});

function Autocomplete(){
    $('.autocomplete').devbridgeAutocomplete({
        serviceUrl: '/appbasic/web/cpt/list_dv?vid='+venue_id,
        lookupFilter: function (suggestion, query, queryLowerCase) {
            console.log(1);
        },
        onSelect: function (suggestion) {
            console.log(suggestion.data);
            $('#formlink').find('[name="active_link"]').val(suggestion.data)
            //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
        }
    });
}