<style>
.slide-block {
    position: fixed;
    top: 0;
    right: -800px;
    width: 800px;
    height: 100%;
    background: #fff;
    border-left: solid 1px rgba(57, 81, 155, 0.2);
    z-index: 99999;
    /* Minimum 2 */
    -webkit-transition: right .3s ease;
    transition: right .3s ease;
    padding:20px;
    overflow:auto;
}
.slide-block.ks-open {
    right: 0;
}
.slide-block > .ks-settings-slide-control {
    cursor: pointer;
    position: absolute;
    font-size: 21px;
    padding: 8px 10px;
    top: 30%;
    background: #fff;
    border: solid 1px rgba(57, 81, 155, 0.2);
    -webkit-border-top-left-radius: 2px;
            border-top-left-radius: 2px;
    -webkit-border-bottom-left-radius: 2px;
            border-bottom-left-radius: 2px;
    left: -42px;
    border-right: none;
}
.slide-block > .ks-settings-slide-control > .ks-icon {
    color: #333; }
.slide-block > .ks-header {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    -js-display: flex;
    display: flex;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center; }
.slide-block > .ks-header > .ks-text {
    font-size: 14px;
    font-weight: 500; }
.slide-block > .ks-header > .ks-settings-slide-close-control {
    cursor: pointer; }
.slide-block > .ks-header > .ks-settings-slide-close-control > .ks-icon {
    position: relative;
    top: 2px;
    font-size: 18px;
    color: rgba(58, 82, 155, 0.6);
}
.slide-block > .ks-header > .ks-settings-slide-close-control > .ks-icon:hover {
    color: rgba(58, 82, 155, 0.8);
}
.slide-block > .ks-themes-list {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    -js-display: flex;
    display: flex;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-top: 20px; }
.slide-block > .ks-themes-list > .ks-theme {
    width: 36px;
    height: 36px;
    -webkit-border-radius: 2px;
      border-radius: 2px;
    margin-top: 10px;
    position: relative;
    margin-right: 10px; }
.slide-block > .ks-themes-list > .ks-theme.ks-active {
    text-align: center; }
.slide-block > .ks-themes-list > .ks-theme.ks-active::before {
    display: inline-block;
    content: "\f17b";
    font-family: "LineAwesome";
    font-size: 18px;
    color: #fff;
    position: relative;
    top: -3px;
    padding-top: 9px; }
.slide-block > .ks-themes-list > .ks-theme::after {
    content: '';
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 7px;
    height: 7px;
    -webkit-border-radius: 4px;
    border-radius: 4px; }
.slide-block > .ks-themes-list > .ks-theme.ks-primary {background: #3F51B5; }
.slide-block > .ks-themes-list > .ks-theme.ks-primary::after {background: #42a5f5; }
.slide-block > .ks-themes-list > .ks-theme.ks-dark-primary {background: #2a3356; }
.slide-block > .ks-themes-list > .ks-theme.ks-dark-primary::after {background: #f35b25; }
.slide-block > .ks-themes-list > .ks-theme.ks-info {background: #42a5f5; }
.slide-block > .ks-themes-list > .ks-theme.ks-info::after {background: #3F51B5; }
.slide-block > .ks-themes-list > .ks-theme.ks-blink-pink-san-marino {background: #f85f73; }
.slide-block > .ks-themes-list > .ks-theme.ks-blink-pink-san-marino::after {background: #4159b8; }
.slide-block > .ks-themes-list > .ks-theme.ks-bermuda-gray-malachite {background: #718ca1; }
.slide-block > .ks-themes-list > .ks-theme.ks-bermuda-gray-malachite::after {background: #1ec318; }
.slide-block > .ks-themes-list > .ks-theme.ks-royal-blue-orchid {background: #6251da; }
.slide-block > .ks-themes-list > .ks-theme.ks-royal-blue-orchid::after {background: #d149d0; }
.slide-block > .ks-themes-list > .ks-theme.ks-ebony-clay-cerise-red {background: #222831; }
.slide-block > .ks-themes-list > .ks-theme.ks-ebony-clay-cerise-red::after {background: #e23e57; }
.slide-block > .ks-themes-list > .ks-theme.ks-international-klein-blue-dixie {background: #0139b0; }
.slide-block > .ks-themes-list > .ks-theme.ks-international-klein-blue-dixie::after {background: #e69616; }
.slide-block > .ks-themes-list > .ks-theme.ks-jungle-green-chambray {background: #27ae61; }
.slide-block > .ks-themes-list > .ks-theme.ks-jungle-green-chambray::after {background: #3F51B5; }
.slide-block > .ks-themes-list > .ks-theme.ks-voodoo-medium-purple {background: #4a304d; }
.slide-block > .ks-themes-list > .ks-theme.ks-voodoo-medium-purple::after {background: #9043d8; }
.slide-block > .ks-themes-list > .ks-theme.ks-cornflower-blue-ecstasy {background: #4c6ef5; }
.slide-block > .ks-themes-list > .ks-theme.ks-cornflower-blue-ecstasy::after {background: #fd7e14; }
.slide-block > .ks-themes-list > .ks-theme.ks-purple-mandy {background: #7950f2; }
.slide-block > .ks-themes-list > .ks-theme.ks-purple-mandy::after {background: #e64980; }
.slide-block > .ks-themes-list > .ks-theme.ks-oslo-gray-royal-blue {background: #868e96; }
.slide-block > .ks-themes-list > .ks-theme.ks-oslo-gray-royal-blue::after {background: #4160de; }
.slide-block > .ks-themes-list > .ks-theme.ks-astronaut-blue-persian-green {background: #00405d; }
.slide-block > .ks-themes-list > .ks-theme.ks-astronaut-blue-persian-green::after {background: #02a388; }
.slide-block > .ks-themes-list > .ks-theme.ks-old-brick {background: #911f27; }
.slide-block > .ks-themes-list > .ks-theme.ks-old-brick::after {background: #630a10; }
.slide-block > .ks-themes-list > .ks-theme.ks-old-brick {background: #911f27; }
.slide-block > .ks-themes-list > .ks-theme.ks-old-brick::after {background: #630a10; }
.slide-block > .ks-themes-list > .ks-theme.ks-white {background: #fff;border: 1px solid #dee0e1; }
.slide-block > .ks-themes-list > .ks-theme.ks-white::after {background: #3F51B5; }
.slide-block > .ks-settings-list {
    margin: 0;
    padding: 0;
    list-style: none;
    margin-top: 30px; }
.slide-block > .ks-settings-list > li {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    -js-display: flex;
    display: flex;
    -webkit-box-pack: justify;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center; }
.slide-block > .ks-settings-list > li + li {
    margin-top: 15px; }
.slide-block > .ks-settings-list > li > .ks-checkbox-slider {
    margin: 0; }
</style>
    <?
    $this->registerJs("
        var ksSettingsSlideControl = $('.ks-settings-slide-control');
        var ksSettingsSlideCloseControl = $('.ks-settings-slide-close-control');
        ksSettingsSlideControl.on('click', function() {
            $(this).closest('.slide-block').toggleClass('ks-open');
        });

        ksSettingsSlideCloseControl.on('click', function() {
            $(this).closest('.slide-block').removeClass('ks-open');
        });
    ");
    ?>
<div class="slide-block">
    <div class="ks-header">
        <h6 class="panel-title">Nội dung thư mẫu</h6>
        <span class="btn btn-default" id="copy"><i class="fa fa-clone" aria-hidden="true"></i></span>
        <i class="pull-right fa fa-times ks-settings-slide-close-control"></i>
    </div>
    <hr>
    <div class="ks-body" id="ks-body"></div>
</div>