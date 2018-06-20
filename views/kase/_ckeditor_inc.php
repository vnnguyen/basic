<?
\app\assets\CkeditorOnlyAsset::register($this);
$this->registerJs(\app\assets\CkeditorOnlyAsset::ckeditorJs('textarea#editor', 'full'));