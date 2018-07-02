<?
$baseUrl = Yii::getAlias('@www');
if ($venue['latlng'] != '') {
    $latlng = explode(',', $venue['latlng']);
}

?>
<style>
    .modal-header .close {
        margin-top: 0;
        position: absolute;
        right: -50px;
        top: -45%;
    }
</style>
<div class="modal-item-detail modal-dialog modal-lg" role="document" data-latitude="<?= (isset($latlng))? $latlng[0]:''?>" data-longitude="<?= (isset($latlng))? $latlng[1]:''?>" data-address="">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="section-title">
                <h2><?= $venue['name']?></h2>
                <div class="label label-default"><?= $venue['stype']?></div><div class="rating-passive" data-rating="4">
                    <span class="stars"></span>
                    <span class="reviews">6</span>
                </div><div class="controls-more">
                    <ul>
                        <li><a href="#">Add to favorites</a></li>
                        <li><a href="#">Add to watchlist</a></li>
                    </ul>
                </div>
                <!--end controls-more-->
            </div>
            <!--end section-title-->
        </div>
        <!--end modal-header-->
        <div class="modal-body">
            <div class="left">
                <div class="avatar" data-owl-nav="1" data-owl-dots="0"><img class="img-responsive" src="<?= $venue['image']?>" alt=""></div>
                <div class="map" id="map-modal"></div>
                <!--end map-->

                <section>
                    <h3>Contact</h3><h5><i class="fa fa-map-marker"></i>63 Birch Street</h5><h5><i class="fa fa-phone"></i>361-492-2356</h5><h5><i class="fa fa-envelope"></i>hello@markys.com</h5>
                </section>
            </div>
            <!--end left -->
            <div class="right">
                <section>
                    <h3>About</h3>
                    <div class="read-more"><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis, arcu non hendrerit imperdiet, metus odio scelerisque elit, sed lacinia odio est ac felis. Nam ullamcorper hendrerit ullamcorper. Praesent quis arcu quis leo posuere ornare eu in purus. Nulla ornare rutrum condimentum. Praesent eu pulvinar velit. Quisque non finibus purus, eu auctor ipsum.</p></div>
                </section>
                <!--end about--><section>
                    <h3>Features</h3>
                    <ul class="tags"><li>Wi-Fi</li><li>Parking</li><li>TV</li><li>Vegetarian</li></ul>
                </section>
                <!--end tags-->
            </div>
            <!--end right-->
        </div>
        <!--end modal-body-->
    </div>
    <!--end modal-content-->
</div>
<!--end modal-dialog-->
