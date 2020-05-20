<style>
.ks-settings-slide-block {
  position: fixed;
  top: 0;
  right: -295px;
  width: 295px;
  height: 100%;
  background: #f6f6ff;
  border-left: solid 1px rgba(57, 81, 155, 0.2);
  z-index: 99999;
  /* Minimum 2 */
  -webkit-transition: right .4s ease;
  transition: right .4s ease;
  padding: 30px; }
  .ks-settings-slide-block.ks-open {
    right: 0; }
  .ks-settings-slide-block > .ks-settings-slide-control {
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
    border-right: none; }
    .ks-settings-slide-block > .ks-settings-slide-control > .ks-icon {
      color: #333; }
  .ks-settings-slide-block > .ks-header {
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
    .ks-settings-slide-block > .ks-header > .ks-text {
      font-size: 14px;
      font-weight: 500; }
    .ks-settings-slide-block > .ks-header > .ks-settings-slide-close-control {
      cursor: pointer; }
      .ks-settings-slide-block > .ks-header > .ks-settings-slide-close-control > .ks-icon {
        position: relative;
        top: 2px;
        font-size: 18px;
        color: rgba(58, 82, 155, 0.6); }
        .ks-settings-slide-block > .ks-header > .ks-settings-slide-close-control > .ks-icon:hover {
          color: rgba(58, 82, 155, 0.8); }
  .ks-settings-slide-block > .ks-themes-list {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    -js-display: flex;
    display: flex;
    -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
            flex-wrap: wrap;
    margin-top: 20px; }
    .ks-settings-slide-block > .ks-themes-list > .ks-theme {
      width: 36px;
      height: 36px;
      -webkit-border-radius: 2px;
              border-radius: 2px;
      margin-top: 10px;
      position: relative;
      margin-right: 10px; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-active {
        text-align: center; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-active::before {
          display: inline-block;
          content: "\f17b";
          font-family: "LineAwesome";
          font-size: 18px;
          color: #fff;
          position: relative;
          top: -3px;
          padding-top: 9px; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme::after {
        content: '';
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 7px;
        height: 7px;
        -webkit-border-radius: 4px;
                border-radius: 4px; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-primary {
        background: #3F51B5; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-primary::after {
          background: #42a5f5; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-dark-primary {
        background: #2a3356; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-dark-primary::after {
          background: #f35b25; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-info {
        background: #42a5f5; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-info::after {
          background: #3F51B5; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-blink-pink-san-marino {
        background: #f85f73; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-blink-pink-san-marino::after {
          background: #4159b8; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-bermuda-gray-malachite {
        background: #718ca1; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-bermuda-gray-malachite::after {
          background: #1ec318; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-royal-blue-orchid {
        background: #6251da; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-royal-blue-orchid::after {
          background: #d149d0; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-ebony-clay-cerise-red {
        background: #222831; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-ebony-clay-cerise-red::after {
          background: #e23e57; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-international-klein-blue-dixie {
        background: #0139b0; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-international-klein-blue-dixie::after {
          background: #e69616; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-jungle-green-chambray {
        background: #27ae61; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-jungle-green-chambray::after {
          background: #3F51B5; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-voodoo-medium-purple {
        background: #4a304d; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-voodoo-medium-purple::after {
          background: #9043d8; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-cornflower-blue-ecstasy {
        background: #4c6ef5; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-cornflower-blue-ecstasy::after {
          background: #fd7e14; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-purple-mandy {
        background: #7950f2; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-purple-mandy::after {
          background: #e64980; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-oslo-gray-royal-blue {
        background: #868e96; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-oslo-gray-royal-blue::after {
          background: #4160de; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-astronaut-blue-persian-green {
        background: #00405d; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-astronaut-blue-persian-green::after {
          background: #02a388; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-old-brick {
        background: #911f27; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-old-brick::after {
          background: #630a10; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-old-brick {
        background: #911f27; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-old-brick::after {
          background: #630a10; }
      .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-white {
        background: #fff;
        border: 1px solid #dee0e1; }
        .ks-settings-slide-block > .ks-themes-list > .ks-theme.ks-white::after {
          background: #3F51B5; }
  .ks-settings-slide-block > .ks-settings-list {
    margin: 0;
    padding: 0;
    list-style: none;
    margin-top: 30px; }
    .ks-settings-slide-block > .ks-settings-list > li {
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
      .ks-settings-slide-block > .ks-settings-list > li + li {
        margin-top: 15px; }
      .ks-settings-slide-block > .ks-settings-list > li > .ks-checkbox-slider {
        margin: 0; }
    </style>
    <?
    $this->registerJs("
        var ksSettingsSlideControl = $('.ks-settings-slide-control');
        var ksSettingsSlideCloseControl = $('.ks-settings-slide-close-control');
        ksSettingsSlideControl.on('click', function() {
            $(this).closest('.ks-settings-slide-block').toggleClass('ks-open');
        });

        ksSettingsSlideCloseControl.on('click', function() {
            $(this).closest('.ks-settings-slide-block').removeClass('ks-open');
        });
    ");
    ?>
    <div class="ks-settings-slide-block">
        <a class="ks-settings-slide-control text-pink">
            <span class="fa fa-fw fa-cog"></span>
        </a>

        <div class="ks-header">
            <span class="ks-text">Layout Options</span>
            <a class="ks-settings-slide-close-control">
                <span class="ks-icon fa fa-close"></span>
            </a>
        </div>

        <div class="ks-themes-list">
            <a href="../default-primary/index.html" class="ks-theme ks-primary ks-active"></a>
            <a href="../default-primary-dark/index.html" class="ks-theme ks-dark-primary"></a>
            <a href="../default-info/index.html" class="ks-theme ks-info"></a>
            <a href="../default-pink/index.html" class="ks-theme ks-blink-pink-san-marino"></a>
            <a href="../default-bermuda-gray/index.html" class="ks-theme ks-bermuda-gray-malachite"></a>
            <a href="../default-royal-blue/index.html" class="ks-theme ks-royal-blue-orchid"></a>
            <a href="../default-ebony-clay/index.html" class="ks-theme ks-ebony-clay-cerise-red"></a>
            <a href="../default-international-klein-blue/index.html" class="ks-theme ks-international-klein-blue-dixie"></a>
            <a href="../default-jungle-green/index.html" class="ks-theme ks-jungle-green-chambray"></a>
            <a href="../default-voodoo/index.html" class="ks-theme ks-voodoo-medium-purple"></a>
            <a href="../default-cornflower-blue/index.html" class="ks-theme ks-cornflower-blue-ecstasy"></a>
            <a href="../default-purple/index.html" class="ks-theme ks-purple-mandy"></a>
            <a href="../default-oslo-gray/index.html" class="ks-theme ks-oslo-gray-royal-blue"></a>
            <a href="../default-astronaut-blue/index.html" class="ks-theme ks-astronaut-blue-persian-green"></a>
            <a href="../default-old-brick/index.html" class="ks-theme ks-old-brick"></a>
            <a href="../default-white/index.html" class="ks-theme ks-white"></a>
        </div>

        <ul class="ks-settings-list">
            <li>
                <span class="ks-text">Collapsed Sidebar</span>
                <label class="ks-checkbox-slider ks-on-off ks-primary ks-sidebar-checkbox-toggle">
                    <input type="checkbox" value="1">
                    <span class="ks-indicator"></span>
                    <span class="ks-on">On</span>
                    <span class="ks-off">Off</span>
                </label>
            </li>
            <li>
                <span class="ks-text">Fixed page header</span>
                <label class="ks-checkbox-slider ks-on-off ks-primary ks-page-header-checkbox-toggle">
                    <input type="checkbox" value="0" checked="">
                    <span class="ks-indicator"></span>
                    <span class="ks-on">On</span>
                    <span class="ks-off">Off</span>
                </label>
            </li>
            <li>
                <span class="ks-text">Dark/Light Sidebar</span>
                <label class="ks-checkbox-slider ks-on-off ks-primary ks-sidebar-style-checkbox-toggle">
                    <input type="checkbox" value="0" checked="">
                    <span class="ks-indicator"></span>
                    <span class="ks-on">On</span>
                    <span class="ks-off">Off</span>
                </label>
            </li>
            <li>
                <span class="ks-text">White/Gray Content Background</span>
                <label class="ks-checkbox-slider ks-on-off ks-primary ks-content-bg-checkbox-toggle">
                    <input type="checkbox" value="0" checked="">
                    <span class="ks-indicator"></span>
                    <span class="ks-on">On</span>
                    <span class="ks-off">Off</span>
                </label>
            </li>
        </ul>
    </div>