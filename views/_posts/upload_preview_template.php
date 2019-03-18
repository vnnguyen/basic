<div class="d-none" id="preview_template">
    <div class="file-row">
        <div class="preview"><img data-dz-thumbnail src="/assets/img/placeholder.jpg" style="width:64px; height:64px;"></div>
        <div>
            <div>
                <span class="name font-weight-semibold" data-dz-name></span>
                &mdash;
                <small class="size text-muted" data-dz-size></small>
                <small class="pull-right text-info"><?= Yii::t('x', 'just now') ?></small>
            </div>

            <strong class="error text-danger" data-dz-errormessage></strong>

            <div>
                <a data-dz-remove class="text-warning cancel" href="#">
                    <i class="fa fa-ban"></i>
                    <span><?= Yii::t('x', 'Cancel upload') ?></span>
                </a>
                <a data-dz-remove class="text-danger delete" href="#">
                    <i class="fa fa-trash-o"></i>
                    <span><?= Yii::t('x', 'Delete') ?></span>
                </a>
            </div>

            <div class="progress" style="height:0.375rem;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width:0%;" data-dz-uploadprogress></div>
            </div>
        </div>
    </div>
</div>
