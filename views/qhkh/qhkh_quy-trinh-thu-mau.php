<?php
use yii\helpers\Html;

include('_qhkh_inc.php');

Yii::$app->params['page_title'] = 'Thư mẫu cho QHKH';

?>
<style>
.page-header-content {background:#fff url(https://www.amica-travel.com/upload-images/whoarewe/notre-equipe/service-clientele-amica-travel-1491878555.jpg) right top no-repeat; background-size:contain;}
</style>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading no-border-bottom">
                <h6 class="panel-title">Types de lettres</h6>
            </div>
        </div>
        <!-- Timeline -->
        <div class="timeline timeline-left">
            <div class="timeline-container">

                <!-- Sales stats -->
                <div class="timeline-row">
                    <div class="timeline-icon">
                        <a href="#" class="btn bg-orange-300 btn-rounded btn-icon btn-lg">
                            <span class="letter-icon">1</span>
                        </a>
                    </div>
                    <div class="panel panel-flat timeline-content">
                        <div class="panel-heading">
                            <h6 class="panel-title">Avant le voyage</h6>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="#" data-ref="bv">Lettre de bienvenue</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="timeline-row">
                    <div class="timeline-icon">
                        <a href="#" class="btn bg-orange-400 btn-rounded btn-icon btn-lg">
                            <span class="letter-icon">2</span>
                        </a>
                    </div>
                    <div class="panel panel-flat timeline-content">
                        <div class="panel-heading">
                            <h6 class="panel-title">Pendant le voyage</h6>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="#" data-ref="sv">Lettre de suivi</a></li>
                                <li><a href="#" data-ref="x2">Confirmation de la demande du client</a></li>
                                <li><a href="#" data-ref="x3">Fin du voyage - Email bye</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="timeline-row">
                    <div class="timeline-icon">
                        <a href="#" class="btn bg-orange btn-rounded btn-icon btn-lg">
                            <span class="letter-icon">3</span>
                        </a>
                    </div>
                    <div class="panel panel-flat timeline-content">
                        <div class="panel-heading">
                            <h6 class="panel-title">Après le voyage</h6>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="#" data-ref="5">Lettre de souvenirs</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="timeline-row">
                    <div class="timeline-icon">
                        <a href="#" class="btn bg-orange-600 btn-rounded btn-icon btn-lg">
                            <span class="letter-icon">4</span>
                        </a>
                    </div>
                    <div class="panel panel-flat timeline-content">
                        <div class="panel-heading">
                            <h6 class="panel-title">Après la lettre de SV</h6>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="#" data-ref="51">Réponse positive - utilisable pour tém</a></li>
                                <li><a href="#" data-ref="52">Lien de témoignage</a></li>
                                <li><a href="#" data-ref="53">Réponse positive - non utilisable pour tém</a></li>
                                <li><a href="#" data-ref="54">Réponse négative</a></li>
                                <li><a href="#" data-ref="55">Lettre d'excuse</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="timeline-row">
                    <div class="timeline-icon">
                        <a href="#" class="btn bg-orange-600 btn-rounded btn-icon btn-lg">
                            <span class="letter-icon">5</span>
                        </a>
                    </div>
                    <div class="panel panel-flat timeline-content">
                        <div class="panel-heading">
                            <h6 class="panel-title">Graves problèmes</h6>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-6">
                                <ul>
                                    <li><a href="#" data-ref="580">Khách phàn nàn HD không cho khách đi bộ</a></li>
                                    <li><a href="#" data-ref="581">Khách phàn nàn về CT ở Lào và đòi bồi thường</a></li>
                                    <li><a href="#" data-ref="582">Khách trúng độc ở Lào - Miss the care of Customer Relations</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <!-- <li><a href="#" data-ref="561"></a></li> -->
                                    <li><a href="#" data-ref="562">Đồ ăn kém, HD k tốt, dv k tương xứng mức giá, khách đòi bồi thường (F1208035)</a></li>
<!--                                     <li><a href="#" data-ref="563"></a></li>
                                    <li><a href="#" data-ref="564"></a></li>
                                    <li><a href="#" data-ref="565"></a></li>
                                    <li><a href="#" data-ref="566"></a></li>
 -->                                    <li><a href="#" data-ref="567">Khách cũ quay lại, ko hài lòng DV, CT - tặng chèque cadeau (F1411046)</a></li>
                                    <li><a href="#" data-ref="568">Khách không hài lòng về chương trình Cambodge (F1412080)</a></li>
                                    <li><a href="#" data-ref="569">Khách không hài lòng về HDV (F1503105)</a></li>
                                    <!-- <li><a href="#" data-ref="56"></a></li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="timeline-row">
                    <div class="timeline-icon">
                        <a href="#" class="btn bg-orange-700 btn-rounded btn-icon btn-lg">
                            <span class="letter-icon">A</span>
                        </a>
                    </div>
                    <div class="panel panel-flat timeline-content">
                        <div class="panel-heading">
                            <h6 class="panel-title">Les autres lettres</h6>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="#" data-ref="41">Lettre de message Service Plus</a></li>
                                <li><a href="#" data-ref="42">Texte pour l'anniversaire</a></li>
                                <li><a href="#" data-ref="43">Lettre pour l'anniversaire de marriage</a></li>
                                <li><a href="#" data-ref="44">Lettre pour Joyeux Noël et bonne année</a></li>
                                <li><a href="#" data-ref="45">Lettre de condoléances</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /timeline -->
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h6 class="panel-title">Les liens importants</h6>
            </div>
            <div class="panel-body">
                <ul>
                    <li><a target="_blank" href="https://www.amica-travel.com/">Amica Travel</a>
                        <ol>
                            <li><a target="_blank" href="https://www.amica-travel.com/club-ami-amica" name="0a629skdeirei202tqheutli7n">Club Ami Amica</a></li>
                            <li><a target="_blank" href="https://www.amica-travel.com/portrait-voyageur" name="7v7tcganl4jtl0oansedioefdq">Portrait voyageur</a></li>
                            <li><a target="_blank" href="https://www.amica-travel.com/temoignages" name="1rm1gjrbbdobtd6qk81hab99cg">Témoignage</a></li>
                        </ol>
                    </li>
                    <li>Blogs
                        <ol>
                            <li><a target="_blank" href="https://blog.amica-travel.com/" name="2vasq8f5f2nfkr9lbcf5i5lp8a">Blog 360 Degrés Vietnam</a></li>
                            <li><a target="_blank" href="http://hanoivietnam.fr/" name="51uqhm3cd7fhd1abkor2jacg81">Hanoi Vietnam</a></li>
                        </ol>
                    </li>
                    <li>Réseaux sociaux
                        <ol>
                            <li><a target="_blank" href="https://goo.gl/Ysq642">Google+ Hanoi</a></li>
                            <li><a target="_blank" href="https://goo.gl/yrGQtE">Google+ Saigon</a></li>
                            <li><a target="_blank" href="https://goo.gl/yjAE52">Google+ Laos</a></li>
                            <li><a target="_blank" href="https://goo.gl/CeTTvQ">Google+ Cambodge</a></li>

                            <li><a target="_blank" href="https://www.facebook.com/amica.travel.vietnam/?fref=ts" name="2901i4pduf8okpngj8dfub4as5">Facebook</a></li>
                            <li><a target="_blank" href="https://twitter.com/AmicaTravel">Twitter</a></li>
                            <li><a target="_blank" href="https://www.instagram.com/amicatravel/">Instagram</a></li>
                            <li><a target="_blank" href="https://www.youtube.com/user/AmicaTravelVietnam">Youtube</a></li>
                            <li><a target="_blank" href="https://pinterest.com/amicatravel/">Pinterest</a></li>

                        </ol>
                    </li>
                    <li>Forums
                        <ol>
                            <li><a target="_blank" href="http://www.tripadvisor.fr/ShowTopic-g293921-i8432-k7273243-o20-Qui_a_recemment_voyage_avec_l_agence_locale_Amica_Travel-Vietnam.html">Trip Advisor</a>
                                <ol>
                                    <li><a target="_blank" href="https://www.tripadvisor.fr/Attraction_Review-g293924-d8861467-Reviews-Amica_Travel_Day_Tours-Hanoi.html">Tours</a></li>
                                    <li><a target="_blank" href="https://www.tripadvisor.fr/ShowTopic-g293921-i8432-k11444560-Le_Cambodge_avec_l_agence_locale_AMICA-Vietnam.html">Tours Cambodge</a></li>
                                    <li><a target="_blank" href="https://www.tripadvisor.fr/ShowTopic-g293939-i9162-k11465193-Retour_de_15_j_Cambodge_avec_Amica_Travel-Cambodia.html">Tours Cambodge</a></li>
                                    <li><a target="_blank" href="https://www.tripadvisor.fr/Hotel_Review-g2415097-d3135829-Reviews-Chez_Pa-Bac_Ha_Lao_Cai_Province.html">Chez Pa</a></li>
                                    <li><a target="_blank" href="https://www.tripadvisor.fr/Hotel_Review-g303945-d3181067-Reviews-Chez_Ich-Ninh_Binh_Ninh_Binh_Province.html">Chez Ich</a></li>
                                    <li><a target="_blank" href="https://www.tripadvisor.fr/Hotel_Review-g4196266-d4196270-Reviews-Chez_Tap-Ba_Be_National_Park_Bac_Kan_Province.html">Chez Tap</a></li>
                                    <li><a target="_blank" href="https://www.tripadvisor.fr/Hotel_Review-g4196266-d4196270-Reviews-Chez_Tap-Ba_Be_National_Park_Bac_Kan_Province.html">Chez Thanh</a></li>
                                    <li><a target="_blank" href="https://www.tripadvisor.fr/Hotel_Review-g293926-d4196244-Reviews-Chez_Nguyen-Hue_Thua_Thien_Hue_Province.html">Chez Nguyen</a></li>
                                    <li><a target="_blank" href="http://www.routard.com/forum_message/1609026/voyage_avec_amica_travel.htm" name="2poa7oal2kngc3tkdm4is3v1oe">Routard</a></li>
                                </ol>
                            </li>
                            <li><a target="_blank" href="http://www.petitfute.com/v45031-hanoi/c1122-voyage-transports/c743-agence-receptive-guide-touristique/143513-amica-travel.html" name="5gsivcjafqjn195vebqana3hip">Petit Futé</a></li>
                            <li><a target="_blank" href="http://www.ciao.fr/Voyage_sur_mesure_au_Vietnam__2138176" name="05he9aeun69ufnpli2uiuij066">Ciao </a></li>
                            <li><a target="_blank" href="https://fr.trustpilot.com/review/amica-travel.com" name="46hd7l7a8m8t2gj48vaeg4vft3">Trustpilot </a></li>
                        </ol>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php include('_slider.php') ?>
<?php
$js = <<<'JS'
$('.timeline').on('click', 'a', function(e){
    e.preventDefault()
    var ref = $(this).data('ref')
    var title = $('#title-' + ref).html()
    var body = $('#body-' + ref).html()
    $('#ks-title').html(title)
    $('#ks-body').html(body)
    $('.slide-block:eq(0)').addClass('ks-open')
})
JS;
$this->registerJs($js);
?>
<div style="display:none">
    <h3 class="topic" id="title-bv">Lettre de bienvenue + Souhait d'un bon voyage</h3>
    <div id="body-bv">
        <p>Bonjour Madame/Monsieur/Mesdames ...,</p>
        <p>Je suis  ... de la Relation Client, collègue de  ..., votre conseiller (ère). Maintenant que vous avez établi votre programme avec elle/lui, je suis heureuse de vous annoncer que je vais désormais m'occuper de votre voyage. Je vais vous suivre tout au long de votre voyage, donc si vous avez des questions ou que vous rencontrez des problèmes, n'hésitez pas à m'en faire part, je me chargerai d'y répondre.</p>
        <p>Je vous souhaite un très bon voyage avec plein de bons souvenirs.</p>
        <p>Bonne journée et bien cordialement,</p>
    </div>

    <h3 id="title-sv">Une grande attention de la part d'Amica Travel - Lettre de suivi (pour prendre les nouvelles des clients)</h3>
    <div id="body-sv">
        <p>Bonjour Madame/Monsieur ...,</p>
        <p>Je suis  ... de la Relation Client d'Amica Travel. Comment allez-vous? (J'étais vraiment heureuse de vous accueillir à notre bureau.)</p>
        <p>Je tiens à vous envoyer quelques mots pour prendre vos nouvelles. Comment se passe votre séjour jusqu'à présent? Comment trouvez-vous nos services? J'espère que vous êtes satisfaits et que vous passez de très bons moments.</p>
        <p>Dans l'attente de votre retour, je vous souhaite une très bonne journée.</p>
        <p>Bien cordialement,</p>
        <p>==============================</p>
        <p>Bonjour Madame/Monsieur ...,</p>
        <p>Comment allez-vous? C'est Thu de la Relation Client d'Amica Travel. Ca fait quelques jours que je n'ai pas pris de vous nouvelles, c'est pourquoi j'y remédie par ce petit message.</p>
        <p>J'ai essayé de vous joindre à votre hôtel afin de pouvoir vous parler de vive voix, mais je pense que vous étiez déjà sortis pour visiter la ville. Je vous écris donc ces quelques mots pour être s&ucirc;re que vous allez bien et que votre séjour au  Centre se passe bien. J'espère que vous êtes satisfaits de nos services.</p>
        <p>En attendant votre retour, je vous souhaite une très bonne journée.</p>
        <p>Amicalement,</p>
    </div>

    <h3 id="title-x2">Confirmation de la demande du client</h3>
    <div id="body-x2">
        <p><strong>Confirmation de la demande du client</strong></p>
        <p>Bonjour Madame, Monsieur...,</p>
        <p>Le service client a bien reçu votre demande/ réponse. (Je suis vraiment désolée de ce dérangement.) Je vais voir avec ma collègue pour résoudre ce problème et revenir vers vous le plus tôt possible. </p>
        <p>Je vous remercie de nous avoir fait part de ces soucis car cela nous permettra d'améliorer notre service. </p>
        <p>En attendant de vous recontacter, je vous souhaite passer une bonne journée!</p>
        <p>Bien cordialement,</p>
    </div>

    <h3 id="title-x3">Fin du voyage - Email bye</h3>
    <div id="body-x3">
        <p>Cher (e) Monsieur/Madame,</p>
        <p>Aujourd'hui est le dernier jour de votre voyage de xxx jours et j'espère que vous avez de beaux souvenirs de ce dernier.</p>
        <p>En xxx jours vous avez pu découvrir une bonne partie de notre pays et après ce voyage, je pense que vous connaissez un peu mieux le Vietnam: nos paysages, nos habitants et surtout nos plats :) </p>
        <p>Je suis triste de devoir vous dire au revoir, mais je vous remercie encore une fois d'avoir choisi Amica Travel. Organiser votre voyage fut un véritable plaisir. Vous resterez dans nos cœurs. </p>
        <p>Je vous souhaite un bon voyage de retour et une bonne continuation.</p>
        <p>Bien cordialement,</p>
    </div>
<?php
$txt = <<<TXT
<p>Cher/chère/chères/chers...,</p>
<p>J'espère que vous allez bien et que votre retour s'est bien passé.</p>
<p>Au nom de toute l'équipe, je vous remercie d'avoir choisi Amica Travel, et j'espère que dans l'ensemble, vous garderez de très bons souvenirs de votre voyage au <strong class="text-danger">XXX</strong>. Nous serions heureux d'avoir vos ressentis sur celui-ci et que vous nous partagiez vos expériences, qu'elles soient bonnes ou mauvaises, dans cette terre lointaine afin que nous puissions améliorer nos services. Vos impressions sont très importantes pour nous car nous sommes toujours à la recherche des meilleures prestations possibles pour nos voyageurs. Afin de mesurer votre satisfaction globale de votre voyage pourriez-vous nous attribuer une note sur 10 pour celui-ci ?</p>
<p>Pour Amica Travel, la relation avec ses anciens voyageurs est primordiale et nous souhaitons vous apporter toujours plus de services. C'est pourquoi nous avons créé le club " Ami Amica " qui vous donne accès à de nombreux avantages. En effet, vous pouvez obtenir des réductions sur vos prochains voyages et surtout vous pouvez en faire profiter vos amis. De plus, si vous parrainez des amis, ce club vous permet d'avoir le droit à des cadeaux sur le thème du Vietnam et même jusqu'à 125€ offerts par groupe parrainé. Pour avoir plus d'informations, n'hésitez pas à accéder au Club " Ami Amica " en ligne en cliquant sur le lien suivant : <a href="https://www.amica-travel.com/club-ami-amica">https://www.amica-travel.com/club-ami-amica</a>.</p>
<p>Encore une fois, j'espère que vous avez passez de très bon moments au <strong class="text-danger">XXX</strong>, et je suis sûre que vous avez pris de nombreuses photos pour les immortaliser. N'hésitez pas à nous les partager si vous le souhaitez, c'est avec grand plaisir que je les réceptionnerai.</p>
<p>Dans l'espoir de recevoir votre réponse,</p>
<p>AMICAlement,</p>
<p>Chargée de Relation Client</p>
TXT;
?>
    <!-- <p><a href="https://mail.google.com/mail/u/0/?view=cm&tf=1&su=Un grand merci de la part d'Amica Travel&body=<?= Html::encode($txt) ?>&fs=1">Send by Gmail</a></p> -->
    <h3 id="title-5">Un grand merci de la part d'Amica Travel (Lettre de souvenirs)</h3>
    <div id="body-5">
        <p>Cher/chère/chères/chers...,</p>
        <p>J'espère que vous allez bien et que votre retour s'est bien passé.</p>
        <p>Au nom de toute l'équipe, je vous remercie d'avoir choisi Amica Travel, et j'espère que dans l'ensemble, vous garderez de très bons souvenirs de votre voyage au <strong>XXX</strong>. Nous serions heureux d'avoir vos ressentis sur celui-ci et que vous nous partagiez vos expériences, qu'elles soient bonnes ou mauvaises, dans cette terre lointaine afin que nous puissions améliorer nos services. Vos impressions sont très importantes pour nous car nous sommes toujours à la recherche des meilleures prestations possibles pour nos voyageurs. Afin de mesurer votre satisfaction globale de votre voyage pourriez-vous nous attribuer une note sur 10 pour celui-ci ?</p>
        <p>Pour Amica Travel, la relation avec ses anciens voyageurs est primordiale et nous souhaitons vous apporter toujours plus de services. C'est pourquoi nous avons créé le club " Ami Amica " qui vous donne accès à de nombreux avantages. En effet, vous pouvez obtenir des réductions sur vos prochains voyages et surtout vous pouvez en faire profiter vos amis. De plus, si vous parrainez des amis, ce club vous permet d'avoir le droit à des cadeaux sur le thème du Vietnam et même jusqu'à 125€ offerts par groupe parrainé. Pour avoir plus d'informations, n'hésitez pas à accéder au Club " Ami Amica " en ligne en cliquant sur le lien suivant : https://www.amica-travel.com/club-ami-amica.</p>
        <p>Encore une fois, j'espère que vous avez passé de très bons moments au <strong>XXX</strong>, et je suis sûre que vous avez pris de nombreuses photos pour les immortaliser. N'hésitez pas à nous les partager si vous le souhaitez, c'est avec grand plaisir que je les réceptionnerai.</p>
        <p>Dans l'espoir de recevoir votre réponse,</p>
        <p>AMICAlement,</p>
        <p>Chargée de Relation Client</p>
    </div>

    <h3 id="title-41">Lettre de message Service Plus</h3>
    <div id="body-41">
        <p>1. En offrant le thé</p>
        <p>"La culture du thé au Vietnam est une tradition vieille de plus de 3.000 ans. Elle est considérée, avec la lecture, comme la méthode la plus efficace pour échapper aux préoccupations de la vie quotidienne. (Ainsi, le thé est présent dans pratiquement toutes les activités communautaires du Vietnam: mariages, anniversaires et cérémonies rituelles.) Il  existe plusieurs types de thé au Vietnam, avec chacun une saveur différente. La vie est aussi comme cela, chaque expérience à sa propre saveur. J'espère ce voyage aura pour vous une bonne saveur, avec de beaux paysages et de bons moments."</p>
        <hr>
        <p>2. En offrant le livre "A la découverte de la culture vietnamienne"</p>
        <p>Bonjour ...,</p>
        <p>Nous savons que votre fille Emilie aime beaucoup écrire depuis son jeune âge. C'est un loisir rare pour un enfant qu'il faut encourager. De plus, comme ce voyage au Vietnam est très important pour elle et lui permet de découvrir la terre o&ugrave; elle est née, Amica Travel lui a préparé un petit cadeau. Nous souhaitons lui offrir le livre: "A la découverte de la culture vietnamienne", écrit par le très célèbre écrivain vietnamien Huu Ngoc. J'espère que ce livre lui donnera plus de connaissances sur la culture vietnamienne et l'aidera pour ses futurs écrits.</p>
        <p>J'espère, un jour, pouvoir tenir entre mes mains un livre écrit par Emilie elle-même. Je me sentirais tellement heureuse.</p>
        <p>Avec toute mon amitié,</p>
        <p>Amica Travel</p>
        <hr>
        <p>3. En offrant la carte de LU:</p>
        <p>Bonjour ...,</p>
        <p>Lu Xing est un des Trois Étoiles du Bonheur de la divinité Sanxing dans la mythologie chinoise. Lu est un symbole de réussite professionnelle, de richesse et même de famille. En vous offrant cette carte, je vous souhaite un très joyeux anniversaire. Que votre 41ème année soit remplie de bonheur, de prospérité et d'amour. J'espère que cette journée vous laissera des souvenirs inoubliables.</p>
        <p>JOYEUX ANNIVERSAIRE !</p>
        <hr>
        <p>4. Pour les "Viet Kieu" rentrant au Vietnam</p>
        <p>"Je vous écris ce petit message pour vous souhaiter un très bon retour au Vietnam, votre terre natale. Je voulais vous remercier très sincèrement de nous avoir fait à nouveau confiance et d'avoir décidé d'organiser votre deuxième voyage avec nous. Nous en sommes très honorés, je tenais à vous adresser personnellement un immense merci.</p>
        <p>Enfin, je voulais vous souhaiter un très....  J'espère que ... sera remplie de bonheur, de chance et d'amour. En ce jour de fête, je pense très fort à vous, et je vous souhaite une journée pleine de souvenirs inoubliables.</p>
        <p>Amicalement,"</p>
    </div>

    <h3 id="title-42">Texte pour l'anniversaire</h3>
    <div id="body-42">
        <p>Texte pour l'anniversaire</p>
        <p>De la part d'Amica Travel, nous vous souhaitons un très joyeux anniversaire. Que votre 51ème année soit remplie de bonheur, de chance et d'amour. En ce jour de fête, nous pensons très fort à vous, et nous espérons que cette journée vous laissera des souvenirs inoubliables.</p>
        <p>----------------------</p>
        <p>Il y a un petit prince qui vient de grandir un peu plus ?</p>
        <p>Tu vois, nous n'avons pas oublié...</p>
        <p>Cela se fête autour d'un gâteau d'anniversaire avec plein de bougies.</p>
        <p>En attendant, voici une petite carte qui te plaira nous l'espérons,</p>
        <p>Et nous te souhaitons un très bel anniversaire.</p>

        <p>Note : Pour un enfant</p>
        <p>---------------------</p>
        <p>Bonjour Mme/M. ...</p>
        <p>J'aimerai vous offrir cette petite surprise pour fêter dignement vos 61 bougies. Je pense très fort à vous en ce jour de fête. Que ces prochains 365 jours vous soient encore meilleurs que les précédents, qu'ils soient remplis de joie, de bonheur, d'amour et de voyages.</p>
        <p>----------------------</p>
        <p>Bonjour Mme/M. ...</p>
        <p>Je tenais aussi à vous dédier cette surprise pour vous souhaiter un joyeux anniversaire avec un peu d'avance. J'espère que cette petite attention vous fera plaisir, mais surtout que le jour J sera plein de sourires, de bonne humeur et de bonheur.</p>
        <p>Amicalement</p>
        <p>-----------------------</p>
        <p>De la part d'Amica Travel, nous vous souhaitons une journée remplie d'amour et de joie. Que cette année vous apporte la prospérité, la fortune et la santé. Joyeux anniversaire.</p>
        <p>===================</p>
        <p>Anglais:</p>
        <p>On behalf of Amica Travel, I wish you a happy birthday. On this special day, I wish you all the very best, all the joy you can ever have and may you be blessed abundantly today, tomorrow and the coming days! HAPPY BIRTHDAY TO YOU!!!!</p>
    </div>

    <h3 id="title-43">Lettre pour l'anniversaire de marriage</h3>
    <div id="body-43">
        <p>Lettre pour "Anniversaire de mariage"</p>
        <p>Bonjour Madame et Monsieur ...,</p>
        <p>Je vous adresse à tous deux mes sincères félicitations à l'occasion de votre XXX (nombre d'années) anniversaire de mariage. Votre entente et votre complicité font plaisir à voir.</p>
        <p>" L'amour n'est pas un sentiment, c'est un art ", disait G. Duhamel. Je pense que vous êtes deux grands artistes, qui avez su barrer à merveille votre navire et franchir les nombreux écueils que la vie quotidienne a fait surgir devant vous. Vous avez su créer un foyer affectueux, réalisation d'une heureuse union.</p>
        <p>Je vous réitère mes sincères félicitations pour cet anniversaire de mariage et vous souhaite de tout cœur que la solidité de votre union perdure encore durant de longues années.</p>
        <p>Avec mes meilleurs voeux,</p>
    </div>

    <h3 id="title-44">Lettre pour Joyeux Noël et bonne année</h3>
    <div id="body-44">
        <p>Lettre pour Joyeux Noël et bonne année</p>
        <p>Bonjour Madame/Monsieur ...,</p>

        <p>A l'occasion de Noël,  je tiens à vous écrire quelques mots pour vous souhaiter de joyeuses fêtes. Que la joie de cette période remplisse vos cœurs et vos âmes tout au long de l'année à venir. Que Noël soit haut en couleur; qu'il laisse derrière lui des souvenirs débordants de bonheur !</p>

        <p>Bien amicalement,</p>

        <p>====================</p>
        <p>Spécialement pour vous à l'occasion de Noël</p>
        <p>Vous faites partie de ces gens dont j'apprécie tout spécialement la compagnie parce que vous dégagez une énergie positive et que votre joie de vivre est vraiment communicative. Pour moi, vous n'êtes pas simplement qu'une connaissance, vous êtes quelqu'un qui a beaucoup d'importance et Noël me semble particulièrement un beau moment pour vous l'exprimer bien tendrement.</p>
        <p>Joyeux Noël!</p>

        <p>Note : ne se dit pas dans un contexte professionnel => à écrire seulement pour les gens avec qui on a une correspondance régulière</p>
        <p>---------------------------</p>
        <p>Bonne Année</p>
        <p>Que vous souhaiter de mieux que la santé dans votre vie, la prospérité dans vos affaires, et beaucoup d'amour tout au long de cette Nouvelle Année</p>
        <p>--------------------------</p>
        <p>Meilleurs Souhaits pour Le Nouvel An!</p>
        <p>Que vos Fêtes soient riches de joie et de gaieté, que cette nouvelle année déborde de bonheur et de prospérité et que tous vos vœux formulés deviennent réalité!</p>
        <p>Bonne Année!</p>
    </div>

    <h3 id="title-45">Lettre de condoléances</h3>
    <div id="body-45">
        <p>Bonjour Madame/Monsieur ...,</p>
        <p>Nous avons été bouleversés d'apprendre le décès de votre.... Nous ne trouvons pas les mots pour exprimer tout ce que nous souhaitons vous partager, cette épreuve est si injuste. Nos pensées les plus amicales sont pour vous, nous vous présentons nos condoléances les plus sincères.</p>
        <p>X a toute sa vie veillé au bien-être de son entourage, à notre tour aujourd'hui de lui réserver la place qu'elle/il mérite dans nos cœurs. Si nous pouvons nous rendre utile dans cette épreuve, en vous apportant une aide concrète ou du soutien, n'hésitez pas à vous tourner vers nous.</p>
        <p>Nous vous présentons toutes nos sincères condoléances et vous souhaitons beaucoup de courage et de force pour surmonter cette épreuve.</p>
        <p>Bien cordialement,</p>
        <p>==================</p>
        <p>Petits textes:</p>
        <p>"Nous tenons à ce que vous sachiez que nous pensons beaucoup à vous en ces jours tristes. Nous ne savons pas pourquoi certaines choses arrivent, mais nous savons que l'amour ravive notre espoir et que ceux que nous aimons sont présents dans notre mémoire. Nous sommes de tout cœur avec vous."</p>
        <p>-------------------------</p>
        <p>"Je sais que cela doit être un moment difficile pour vous tous. Sachez que vous êtes dans nos pensées et nos prières, et s'il y a la moindre chose que nous puissions faire pour vous, n'hésitez surtout pas à nous dire ce dont vous avez besoin."</p>
    </div>

    <h3 id="title-51">Réponse positive - utilisable pour témoignage</h3>
    <div id="body-51">
        <p>Réponse suite au retour des clients</p>
        <p>Pour les clients qui sont très satisfaits (commentaires positifs) - utilisable pour témoignage</p>
        <p>Bonjour Madame et Monsieur,</p>
        <p>J'ai bien reçu votre mail (et vos belles photos). Je suis ravie d'apprendre que votre voyage de retour s'est bien passé et que nos services vous ont satisfait.</p>
        <p>Merci beaucoup de nous  avoir partagé vos commentaires de voyage, car ils nous permettent de savoir exactement ce qui s'est passé durant le tour. Votre appréciation est donc un excellent outil pour contrôler la qualité de nos choix.</p>
        <p>Je profite également de cette occasion pour vous demander si nous pouvons afficher votre témoignage avec éventuellement vos coordonnées (adresse, numéro de téléphone ou courriel..) et vos photos (si vous en avez) ou au minimum l'un de ces éléments? Ce partage, qui rend vos écrits plus convaincants, sera précieux pour les autres voyageurs. Nous publions les avis de nos voyageurs qui le souhaitent ici : <a href="https://www.amica-travel.com/temoignages">Témoignage voyage</a>.</p>
        <p>Si vous avez apprécié votre voyage, pourriez-vous partager vos expériences avec Amica aux autres voyageurs sur les forums de voyage comme <a href="http://www.tripadvisor.fr/ShowTopic-g293921-i8432-k7273243-o20-Qui_a_recemment_voyage_avec_l_agence_locale_Amica_Travel-Vietnam.html">TripAvisor</a>, <a href="http://www.routard.com/forum_message/1609026/voyage_avec_amica_travel.htm">le Routard</a>, <a href="https://fr.trustpilot.com/review/amica-travel.com">Trustpilot</a> ou alors sur d'autres forums dont vous êtes membre ?</p>
        <p>Enfin, certains hébergements de votre circuit sont présents sur Tripadvisor, alors si vous avez des recommandations, conseils ou astuces pour les futurs visiteurs, ce serait très gentil de votre part de les partager sur le site:&nbsp;<a href="https://www.tripadvisor.fr/Hotel_Review-g4196266-d4196270-Reviews-Chez_Tap-Ba_Be_National_Park_Bac_Kan_Province.html">Chez Tap</a>&nbsp;/////&nbsp;<a href="https://www.tripadvisor.fr/Hotel_Review-g4353350-d4353353-Reviews-Chez_Thanh-Hong_Phong_Ninh_Binh_Province.html">Chez Thanh</a>&nbsp;/////&nbsp;<a href="https://www.tripadvisor.com/Hotel_Review-g293926-d4196244-Reviews-Chez_Nguyen-Hue_Thua_Thien_Hue_Province.html">Chez Nguyen</a>&nbsp;////&nbsp;<a href="https://www.tripadvisor.fr/Hotel_Review-g2415097-d3135829-Reviews-Chez_Pa-Bac_Ha_Lao_Cai_Province.html">Chez Pa</a>&nbsp;/////&nbsp;<a href="https://www.tripadvisor.fr/Hotel_Review-g303945-d3181067-Reviews-Chez_Ich-Ninh_Binh_Ninh_Binh_Province.html">Chez Ich</a>.</p>
        <p>Nous vous rappelons que si vous désirez vous aussi avoir votre article sur notre blog, vous êtes les bienvenus !</p>
        <p>Je reste à votre disposition pour recevoir vos commentaires et également pour répondre à toutes vos attentes.</p>
        <p>Amica-lement,</p>
    </div>

    <h3 id="title-52">Lien de témoignage</h3>
    <div id="body-52">
        <p>Lien témoignage</p>
        <p>Votre témoignage sur notre site web</p>
        <p>Bonjour Madame et Monsieur,</p>
        <p>Je vous remercie pour avoir partagé vos expériences de voyage avec nos futurs clients et aussi pour votre dévouement à parler de nous autour de vous.</p>
        <p>Vous pouvez trouver sous ce lien votre témoignage : XXX. J'espère avoir correctement reporté vos souvenirs et conseil. Si ce n'est pas le cas, n'hésitez pas à me faire part de vos modifications.</p>
        <p>Je vous souhaite du bonheur et de la santé,</p>
        <p>Avec tous mes remerciements les plus sincères.</p>
        <p>Bien cordialement</p>
    </div>

    <h3 id="title-53">Réponse positive - non utilisable pour témoignage</h3>
    <div id="body-53">
        <p>Les clients sont satisfaits dans l'ensemble (qq commentaires négatifs) - pas utilisable pour témoignage</p>
        <p>Bonjour Madame et Monsieur,</p>
        <p>J'ai bien reçu votre mail. Je suis ravie d'apprendre que votre retour s'est bien passé.</p>
        <p>Je vous remercie des remarques que vous nous avez réservées. Nous allons immédiatement les prendre en compte pour améliorer nos services. Votre appréciation est un excellent outil pour contrôler la qualité de nos choix.</p>
        <p>Si vous avez apprécié votre voyage, pouvez-vous partager vos expériences avec Amica aux autres voyageurs sur ces forums de voyage?</p>
        <p>Forum <a href="http://www.tripadvisor.fr/ShowTopic-g293921-i8432-k7273243-o20-Qui_a_recemment_voyage_avec_l_agence_locale_Amica_Travel-Vietnam.html">Trip Advisor</a>,<a href="http://www.petitfute.com/v45031-hanoi/c1122-voyage-transports/c743-agence-receptive-guide-touristique/143513-amica-travel.html"> Petit Futé</a>, <a href="http://www.ciao.fr/Voyage_sur_mesure_au_Vietnam__2138176">Ciao</a></p>
        <p>Forum <a href="http://www.routard.com/forum_message/1609026/voyage_avec_amica_travel.htm">Routard</a></p>
        <p>Forum <a href="https://fr.trustpilot.com/review/amica-travel.com">Trustpilot</a></p>
        <p>Ou alors sur d'autres forums dont vous êtes membre.</p>
        <p>Enfin, certains hébergements de votre circuit sont présents sur Tripadvisor, alors si vous avez des recommandations, conseils ou astuces pour les futurs visiteurs, ce serait très gentil de votre part de les partager sur le site:&nbsp;<a href="https://www.tripadvisor.fr/Hotel_Review-g4196266-d4196270-Reviews-Chez_Tap-Ba_Be_National_Park_Bac_Kan_Province.html">Chez Tap</a>&nbsp;/////&nbsp;<a href="https://www.tripadvisor.fr/Hotel_Review-g4353350-d4353353-Reviews-Chez_Thanh-Hong_Phong_Ninh_Binh_Province.html">Chez Thanh</a>&nbsp;/////&nbsp;<a href="https://www.tripadvisor.com/Hotel_Review-g293926-d4196244-Reviews-Chez_Nguyen-Hue_Thua_Thien_Hue_Province.html">Chez Nguyen</a>&nbsp;////&nbsp;<a href="https://www.tripadvisor.fr/Hotel_Review-g2415097-d3135829-Reviews-Chez_Pa-Bac_Ha_Lao_Cai_Province.html">Chez Pa</a>&nbsp;/////&nbsp;<a href="https://www.tripadvisor.fr/Hotel_Review-g303945-d3181067-Reviews-Chez_Ich-Ninh_Binh_Ninh_Binh_Province.html">Chez Ich</a>.</p>
        <p>Toute l'équipe d'Amica Travel vous remercie encore une fois pour nous avoir confié l'organisation de votre voyage et pour votre contribution à l'amélioration de la qualité de nos services.</p>
        <p>Sincèrement,</p>
    </div>

    <h3 id="title-54">Réponse négative</h3>
    <div id="body-54">
        <p>Les clients ne sont pas très satisfaits (beaucoup de commentaires négatifs)</p>
        <p>Bonjour Madame/Monsieur ...,</p>
        <p>Nous avons bien reçu votre mail. Nous vous remercions d'avoir pris le temps de nous faire part de vos commentaires et c'est avec beaucoup d'attention que nous avons pris connaissance de vos remarques. Nous avons un immense respect pour vos observations et nous allons immédiatement les prendre en compte. Ces commentaires nous sont précieux, car ils nous permettent de savoir exactement ce qui s'est passé durant le tour.</p>
        <p>Après lecture de vos commentaires, nous avons travaillé avec nos guides et nos partenaires pour comprendre et résoudre les différents problèmes. Nous tenons à nous excuser et vous apporter quelques explications:</p>
        <p> ... ... ... .....</p>
        <p>Pour chaque groupe de clients, nous avons essayé de proposer tout d'abord des sites incontournables et puis des sites parmi les plus remarquables. Cependant, malgré nos efforts et notre dévouement, il y a parfois des choses qui sont hors de notre portée (insistance des vendeurs, caractéristique touristique, mauvais état de route et de lieu et changement des horaires du vol sans prévu ...). </p>
        <p>Nous regrettons sincèrement que votre voyage ne fut pas à la hauteur de vos espérances. Nous espérons tout de même que vous gardez quelques bons souvenirs et que les inconvénients survenus n'ont pas entaché votre image du Vietnam.</p>
        <p>Nous tenons à vous remercier de votre contribution, et nous allons tout mettre en œuvre éviter qu'un tel cas se reproduise.</p>
        <p>Encore une fois, merci beaucoup pour vos retours.</p>
        <p>Toute l'équipe d'Amica Travel vous souhaite une bonne santé et beaucoup de bonheur dans votre vie. </p>
        <p>Cordialement,</p>
    </div>

    <h3 id="title-55">Lettre d'excuse</h3>
    <div id="body-55">
        <p>Bonjour Madame/Monsieur/Mesdames ...,</span></p>
        <p>Nous vous remercions très sincèrement pour les remarques que vous nous avez réservées et pour lesquelles nous avons un grand respect. Nous avons vraiment apprécié vos critiques constructives.</p>
        <p>Nous vous remercions de nous avoir fait parvenir vos commentaires détaillés qui vont nous permettre de comprendre nos erreurs et de nous améliorer par la suite. Nous sommes réellement navrés que votre voyage n'ait pas été à la hauteur de vos espérances malgré nos efforts. Nous espérons tout de même que vous gardez quelques bons souvenirs et que les inconvénients survenus n'ont pas entaché votre image du Vietnam.</p>
        <p>Nous tenons à vous remercier de votre contribution, et nous allons tout mettre en œuvre éviter qu'un tel cas se reproduise.</p>
        <p>Nous nous efforçons de perfectionner sans cesse nos services et souhaitons avoir le privilège de vous servir dans vos prochains voyages au Vietnam ou en Indochine.</p>
        <p>Toute l'équipe d'Amica Travel vous adresse d'ici là ses meilleures salutations. </p>
        <p>Sincèrement,</p>
    </div>

    <h3 id="title-561"></h3>
    <div id="body-561"></div>

    <h3 id="title-562">Đồ ăn kém, HD k tốt, dv k tương xứng mức giá, khách đòi bồi thường (F1208035)</h3>
    <div id="body-562">
        <p><strong>Thư khách gửi sau khi về nước:</strong></p>
        <p>Chère Mlle Duong,</p>
        <p>Merci pour votre message.</p>
        <p>Malheureusement, nous ne sommes pas du tout satisfaits de la façon dont notre voyage au Cambodge a été organisé et conduit. La partie Laos fut OK.</p>
        <p>Les problèmes principaux je les avais signalés en cours de voyage dans les messages ci-joints que je vous avais envoyés mais qui sont restés sans réponse de votre part.</p>
        <p>En plus des problèmes avec les repas – et même si un effort a été fait suite à nos protestations par votre représentant à Siem Reap qui nous a amenés dans un restaurant convenable lors de notre dernière soirée à Siem Reap – je tien à vous signaler que notre guide au Cambodge était simplement nul! Dans le formulaire d'évaluation qu'il m'a demandé de remplir la veille de notre départ je ne me suis pas permis de le dire de façon explicite et je l'ai noté plutôt bien tout en me réservant de vous adresser par la suite ces commentaires plus détaillés. Le niveau de connaissance de l'anglais de ce guide est insuffisant; ses connaissances générales sont très limitées et en tout cas ses difficultés d'expression en anglais rendent difficile la compréhension de ce qu'il dit; son sens de la proposition et de l'écoute du client simplement inexistant (il avait toujours l'attitude de dire que rien n'était possible, que ceci et cela était fermé; il ne rêvait que de se débarrasser de nous le plus rapidement possible; le dernier jour il a même levé la voix contre moi en prétendant que le palais royal à Phnom Penh, où nous aurions souhaité qu'il nous dépose, était fermé depuis plusieurs jours, ce qui était manifestement faux – il voulait simplement reprendre la route pour Siem Reap le plus rapidement possible).</p>
        <p>Compte tenu du montant que nous avons payé pour ce voyage, qui est énorme par rapport aux coûts au Cambodge et au niveau des services dont nous avons bénéficié, nous considérons que votre comportement, notamment en matière de rapport qualité/prix, est à la limite de l'arnaque.</p>
        <p>Je ne laisserai pas cela passer sans conséquences et j'adresserai des lettres formelles aux autorités responsables pour le tourisme au Viêtnam et au Cambodge. J'ai également l'intention de faire rapport dans les sites internet appropriés et le fora des voyageurs.</p>
        <p>La seule façon pour vous de vous rattraper et de nous dédommager serait de nous rembourser une partie de la somme que nous avons payée. Je considère que vous devriez nous rembourser un montant d'au moins US$ 500.</p>
        <p>Je reste en attente de votre réaction.</p>
        <p>Bien à vous,</p>
        <p>Giuseppe Angelini</p>
        <hr>
        <p><strong>Thư trả lời khách của Amica :</strong></p>
        <p>Bonjour Monsieur,</p>
        <p>Je reprends contact avec vous concernant les plaintes que vous avez adressées à Amica Travel  à la suite de votre voyage au Laos et au Cambodge.</p>
        <p>Vos reproches portent sur les points suivants :</p>
        <ol>
            <li>La qualité des repas</li>
            <li>La qualité du guide</li>
            <li>Le rapport qualité/prix</li>
        </ol>
        <p>Après avoir lu le rapport du guide et celui de notre représentant à Siem Reap, je tiens à vous répondre sur ces points comme suit :</p>
        <p>1. La qualité des repas</p>
        <p>Dans de nombreux endroits au Cambodge, comme Kratie, Kompong Thom etc, l’infrastructure touristique est peu développée et nous avons des difficultés à trouver des restaurants meilleurs que ceux proposés.</p>
        <p>Lors de votre entrevue avec notre représentant à Siem Reap, nous avons tenu compte de vos remarques et avons fait tout notre possible pour améliorer les choses. Nous vous avons même proposé de choisir vous-même les menus et/ou le restaurant (par exemple à Phnom Penh). Rares sont les agences qui acceptent de procéder à une telle démarche sans demander de suppléments.</p>
        <p>Malgré votre déception à ce sujet, déception que nous comprenons, nous estimons donc que notre responsabilité ne peut pas être engagée.</p>
        <p>2. Qualité du guide</p>
        <p>Vos reproches concernent d’une part ses compétences et d’autre part son attitude.</p>
        <p>Nous sommes étonnés de vos remarques sur les compétences de ce guide, pour plusieurs raisons :</p>
        <ul>
            <li>Lors de votre arrivée à Siem Reap, vous avez dit à notre représentant que ce guide est correct.</li>
            <li>La fiche d’évaluation que vous avez remplie à la fin du voyage nous dit que ce guide est « plutôt bien ».</li>
        </ul>
        <p>Même si vous vous réservez de faire des commentaires post-voyage, votre remarque selon laquelle le guide est « nul » nous surprend car elle est à l’opposée de ce que vous avez mentionné dans la fiche d’évaluation.</p>
        <p>Comme tout autre voyageur, vous avez le droit de demander le remplacement du guide. C’est la raison pour laquelle notre représentant à Siem Reap a sondé votre avis sur le guide.</p>

    </div>

    <h3 id="title-563"></h3>
    <div id="body-563"></div>

    <h3 id="title-564"></h3>
    <div id="body-564"></div>

    <h3 id="title-565"></h3>
    <div id="body-565"></div>

    <h3 id="title-566"></h3>
    <div id="body-566"></div>

    <h3 id="title-567">Khách cũ quay lại, ko hài lòng DV, CT - tặng chèque cadeau (F1411046)</h3>
    <div id="body-567">
        <p><strong>KHÁCH PHẢN HỒI</strong></p>
        <p>Bonjour Dieu Linh,</p>
        <p>La note attribuée pour ce voyage est de 6/10.</p>
        <p>Comme déjà évoqué par mon mari lors de votre dernier appel téléphonique au cours de notre voyage, nous sommes cette fois-ci un peu déçus de la prestation et de l'organisation.</p>
        <p>Voici les raisons principales:</p>
        <p>Nous avons eu 6 guides différents pendant notre périple de 18 jours, c'est beaucoup.</p>
        <p>Votre mail d'information ne parle que de trois personnes, Pheary, Ka et Neary. Vous n'aviez pas mentionné les deux guides vietnamiens Joseph et Chau, nous avons eu la surprise sur place. De plus Ka à Siem Reap a été malade le premier jour et il a été remplacé par un de ses amis guide, ...</p>
        <p>Ces 6 guides n'ont pas eu un niveau de français égal et surtout leur motivation était pour certains notamment au Cambodge pas franchement manifeste. Le guide remplaçant de Ka par contre était très motivé, bravo!</p>
        <p>A notre arrivée à Saigon le chauffeur (très bien) nous a emmenés au bureau local pour le paiement de notre voyage. Accueil froid par plusieurs femmes dont aucune ne parle ni français ni vraiment anglais, quelle déception par rapport à notre dernier voyage et accueil à Hanoi il y a 2 ans!</p>
        <p>Les familles d'accueil nous ont bien reçus mais ne semblent pas s'intéresser à leurs hôtes et heureusement que le chauffeur était là pour animer l'activité pêche dans la 2ème famille d'accueil car personne dans cette famille ne s'en est chargé. Comme déjà dit, quelle différence par rapport aux familles d'accueil au Vietnam il y a 2 ans.</p>
        <p>Côté organisation il y a eu un grand flottement lors du passage à Phnom Penh. L'après-midi de notre arrivée était trop court pour faire toutes les visites prévues et le lendemain la sortie sur l'île de la soie a nécessité plusieurs appels téléphoniques par notre guide (je ne sais pas à qui) pour que le programme prévu soit respecté un minimum.</p>
        <p>Le jour de notre départ de Phnom Penh nous devions partir avec le bateau le matin vers Chau Doc. Pour une raison inconnue le départ a eu lieu en début d'après-midi et nous sommes restés seuls toute la matinée sans programme alors que la guide aurait pu rattraper les visites non effectuées le premier après-midi (musée du génocide et marché russe). A notre arrivée à Chau Doc il faisait nuit, les visites prévues ont été reportées au lendemain et malheureusement se sont fait très rapidement pour respecter le planning.</p>
        <p>En conclusion, nous avons l'impression qu'Amica maîtrise parfaitement les voyages au Vietnam mais pas au Cambodge.</p>
        <p>En espérant que ces remarques puissent contribuer à une amélioration dans votre organisation, nous tenons quand même à vous dire que nous avons fait un très beau voyage.</p>
        <p>Bien cordialement.</p>
        <p>Anja & Philippe Puly</p>
        <hr>

        <p><strong>AMICA TRẢ LỜI (nội dung đã được duyệt)</strong></p>
        <p>Bonjour Anja,</p>
        <p>J'espère que vous allez toujours bien.</p>
        <p>Je vous souhaite une bonne année 2015 !</p>
        <p>Amica vous remercie encore de vos commentaires précieux sur le voyage qui nous aideront surement à améliorer nos services. Nous sommes désolés profondément que ce dernier ne vous a pas satisfait comme celui en 2012.</p>
        <p>Concernant les guides, c’était la saison de tourisme en Novembre au Cambodge, nous avions donc des difficultés à les chercher et arranger, c’est pour cela nous avons dû vous en mettre trois. A savoir qu’il faut toujours un guide pour Phnom Penh et un autre pour Siem Reap. Pour le Vietnam, votre voyage s’est coupé en deux parties, donc c’est difficile pour garder le même guide.</p>
        <p>A votre arrivée à Saigon, c’était le bureau de notre partenaire donc la qualité n’était pas le même à Hanoi. Nous en sommes désolés. Pourtant ce point sera bientôt amélioré car notre Directeur ira à Saigon dès ce weekend dans le but de faire des démarches pour ouvrir notre bureau de représentation dans le Sud.</p>
        <p>Le reste concernant les programmes, nous avons bien noté pour améliorer.</p>
        <p>Nous vous remercions de nous avoir partagé les informations grâce auxquelles nous comprenons bien comment votre voyage s’est passé. Nous espérons quand même que cela ne vous empêchera pas à faire appel à nos services si vous envisagerez de revenir en Asie dans l’avenir.</p>
        <p>A fin de nous excuser pour ces désagréments, nous voulons bien vous offrir un chèque cadeau de 50€ avec lequel vous pourrez faire des achats en ligne sur Amazon.fr.</p>
        <p>Nous souhaitons que cette offre vous apportera un petit plaisir et qu’il nous aide à vous transmettre nos meilleurs vœux pour nouvel an 2015.</p>
        <p>Avec tous nos remerciements,</p>
        <p>Amicales salutations.</p>
    </div>


    <h3 id="title-568">Khách không hài lòng về chương trình Cambodge (F1412080)</h3>
    <div id="body-568">
        <p><strong>KHÁCH PHẢN HỒI</strong></p>
        <p>Bonjour,</p>
        <p>Suite a votre appel ce jour, veuillez trouver ci-dessous notre vécu du séjour au Cambodge:</p>
        <ul>
            <li>- le 31/12: nous avons eu une communication avec la guide puis vous-même sur l'heure de départ vers Prek Toal. Nous avons attire votre attention d'un changement par rapport au programme. Le départ aux aurores vers 5h30 était programme a 7h30. Dans les faits la journée du 3 janvier,  nous sommes partis a 7h30 pour une arrivée a Prek Toal vers 10h15. Puis petit bateau pour l'observatoire ou nous sommes arrivés a 11h45. Il a fallu attendre 45 mn dans le bateau avant de pouvoir accéder a l'observatoire. Il était donc 12h30 pour observer les oiseaux. Nous avons déjeune a 14h, puis trajet vers battambang ou nous sommes arrivés tardivement  a 18h45. Nous n'avons pu observer les oiseaux tels que decrits et cites dans le programme (perches sur les arbres, pêchants ou prenant leur envol). Nous sommes très déçus car étions particulièrement intéressés par l'observation de ces oiseaux et cet écosystème unique.  De plus nous n'avons pas pu apprendre les différentes techniques dd pêche des habitants comme décrit dans le programme car "ce n'est pas la saison" pour le faire.</li>
            <li>- le 4 janvier a Battambang: déception a nouveau, le marche est sans intérêt, quand au musée et a la pagode ils étaient fermés car dimanche. Or ces visites étaient prévues dans le programme ce jour la. Comme il n'y avait ruŕ d'autre a faire, nous étions de retour a l'hôtel a 14h (hôtel situe a 15 m' du centre ville en voiture).</li>
            <li>- le 6 janvier, ce fut une journée entière de voiture (7h30 a 16h30) hormis un arrêt de 5 mn a Yeay Peau, et de 1h30 pour la cascade de Ta Tai. Koh Kong est sans interet et ce qui etait prevu dans le programme a savoir "vous pourrez passer le reste de la journee a profiter de la mer et des plages du golfe de Sial" etait infaisable. Absence de plage (hormis un bout de plage de 50m plein d'ordures et de détritus de toute sorte. Le guide et le chauffeur nous on confirme l'absence de plage a cet endroit. Quant a la mer, c'était proche d'un égout. Donc pas une bonne journée.</li>
            <li>- le 7 janvier, la journée du programme était infaisable. Départ prévu a 8h pour: 3h de bateau a l'aller avec visite de mangrove, 2 a 3 heures sur l'île, puis 2h30 de bateau au retour, puis un trajet Koh Kong - Kampot de 5 a 6 heures de route. Dans les faits, en partant a 8 h15, nous n'avons pu faire que la route, arrivés a 13h30 a notre hôtel. Route tres difficile et en yravaux avec une seule voix pendant 1h30 de trajet. Vous envisagiez de nous faire faire cela de nuit ? La seule activité faisable a été une courte visite de Kampot (1h). Nous n'avons pu faire l'excursion sur l'île ni apprécier le barbecue de fruits de mer.</li>
        </ul>
        <p>Lors de notre conversation ce jour, vous nous proposez 150 euros et un dîner a Phnom Penh. Notre constat est que nous avons perdu 3 jours de notre voyage a cause de l'incompétence de votre correspondant au Cambodge. Le préjudice subit est important pour nous, tant sur le plan moral (grande déception, absence de plaisir et de découvertes) et physique (longueur et difficulté des trajets en voiture, nous avons très mal au dos et aux jambes). Votre proposition de dédommagement nous paraît très insuffisante au regard de ce préjudice.</p>
        <p>Concernant le repas a Phnom Pen nous avons déjà d'autres plans organises (amis) donc cela ne convient pas.</p>
        <p>Concernant la somme, l'équivalent de la perte, en terme de préjudice, représente pour nous un minimum de 250 USD par personne, a titre de remboursement et cela avant notre départ de Phnom Penh.</p>
        <p>Nous sommes déçus de notre voyage au Cambodge  (hors Siem Réap), mais nous sommes par ailleurs satisfaits de notre voyage au Laos.</p>
        <p>Dans l'attente de votre réponse.</p>
        <p>Cordialement,</p>
        <hr>
        <p><strong>AMICA TRẢ LỜI</strong></p>
        <p>Bonjour Monsieur Dauvergne</p>
        <p>Nous avons bien reçu vos commentaires concerant votre voyage au Cambodge. Je vous en remercie sincèrement.</p>
        <p>Nous regrettons que votre voyage n’ait pas été à la hauteur de vos espérances, étant donné que la satisfaction du client est le but ultime d'Amica Travel.</p>
        <p>Nous avons, suite à vos commentaires, lu le rapport de notre représentant à Siem Reap et nous sommes désolés pour les évènements qui se sont passé durant le voyage du 3 au 7 Janvier. Vos commentaires vont nous aider à améliorer notre service.</p>
        <p>Le guide nous a dit deux choses différentes par rapport à vos informations :  Vous avez eu l’occasion de voir les oiseaux, mais pas autant que décrits sur le programme et vous avez aussi visité la pagode à Battambang.</p>
        <p>Je voudrais, malgré tout, vous exprimer notre profonde compréhension à l'égard de vos remarques.</p>
        <p>Cependant, compte tenu des frais du voyage, il nous est difficile d’accepter de vous rembourser 180 usd par personne, soit 360 usd pour votre groupe. Cette somme touche totalement le bénéfice de ce voyage.</p>
        <p>Je vous remercie par avance de votre compréhension,</p>
        <p>Bien cordialement.</p>
    </div>


    <h3 id="title-569">Khách không hài lòng về HDV (F1503015)</h3>
    <div id="body-569">
        <p>Khách viết :</p>
        <p><strong>Lần 1 :</strong></p>
        <p>Bonjour Ha,</p>
        <p>Notre meilleur auteur, compositeur et interprète au Québec, s'appelle Gilles Vigneault et il dit dans un de ces poèmes: "Ce que je dis, c'est en passant". Je vous livre aussi, en passant, les quelques réflexions qui suivent.</p>
        <p>Dans ma longue carrière, j'ai été directeur des Parcs et du Plein air d'une région du Québec qui a la superficie de la Belgique! J'y planifiais et organisais les activités reliées à la chasse, à la pêche, au camping, aux activités de découverte et d'interprétation des milieux naturels (faune, flore, camping, canöing, vélo de montagne, randonnées pédestres avec ou sans guide).</p>
        <p>Dans l'équipe importante que je dirigeais, il y avait des guides. Nous recrutions chacun et chacune en s'assurant qu'ils avaient le goût de PARTAGER leurs connaissances, qu'ils étaient GÉNÉREUX de leur temps, qu'ils aimaient les personnes, c'est-à-dire les CONNAÎTRE. VÉRIFIER leurs besoins, les ACCOMPAGNER dans la découverte des milieux visités. En plus de recruter les meilleurs, nous leur donnions une formation continue afin qu'ils intègrent bien les valeurs et la philosophie des parcs du Québec. Régulièrement, nous les rencontrions individuellement afin de leur transmettre les évaluations des clients et vérifier leur intérêt pour leur emploi.</p>
        <p>Plus concrètement encore, cela signifie que le guide doit s'intéresser à ses clients en s'assurant bien sûr que ceux-ci on le goût de partager certaines informations personnelles, toujours accompagner ses clients lors d'une randonnée en ne marchant pas 100 mètres devant eux (c'est alors difficile de poser une question quand une interrogation surgit...), toujours annoncer le programme, le réviser en début de journée pour s'assurer que les clients sont bien sur la même longueur d'onde etc.</p>
        <p>Nous avons choisi AMICA même si le coût du circuit était plus élevé parce que vous nous avez expliqué par courriel que votre offre se distinguait par les services de guides qui devenaient des amis et que nous aurions l'occasion de partager les repas avec nos hôtes dans les familles. Malheureusement, ce n'est pas l'expérience que nous avons vécue et nous sommes déçus d'avoir obtenu 50% de la prestation achetée.</p>
        <p>Bien à vous. Salutations à Huyn.</p>
        <p>Alain et Marthe Bélanger</p>
        <hr>
        <p><strong>Lần 2 :</strong></p>
        <p>Bonjour Ha,</p>
        <p>Comme nous avons respecté notre engagement en versant la totalité du montant réclamé pour le circuit que nous avons négocié de bonne fois avec Àmica et qu'une partie importante du contrat (le "share" du "discover and share" qui se retrouve à l'endos des t-shirt de vos guides), nous vous demandons de soumettre aux dirigeants d'Amica les commentaires que nous vous avons soumis afin qu'ils puissent évaluer qu'elle est la part du paiement reçu qui devrait nous être remis.</p>
        <p>Nous vous remercions encore de votre soutien dans notre démarche et vous prions d'accepter nos cordiales salutations.</p>
        <p>Marthe et Alain Bélanger</p>
        <hr>
        <p><strong>Amica trả lời khách – Fleur Mercier viết :</strong></p>
        <p>Cher Monsieur Bélanger,</p>
        <p>Veuillez excuser cette réponse un peu tardive qui arrive après une discussion entre notre vendeuse, notre service clientèle, notre direction et le guide.</p>
        <p>Je vous remercie pour vos emails et vos différents retours d’expérience qui nous permettent de nous enrichir et de nous améliorer.</p>
        <p>Je suis désolée de lire que vous regrettez d’être passés par Amica pour ce voyage au nord du Vietnam à cause de notre guide Tham. Veuillez noter que notre politique interne est de donner des primes aux guides qui ont bien travaillé. Nous avons compris que son manquement au partage d’informations a été causé par une frustration liée aux différents incidents qui se sont produits au début du voyage (le changement d’hôtel que vous avez effectué le premier jour et qui ne nous a pas été communiqué ce qui a fait perdre du temps sur le programme de cette journée, la perte de temps également le deuxième jour suite à l’oubli de l’appareil photo, le problème du paiement des boissons qui contractuellement n’ont jamais été inclues dans le programme, le questionnement du jugement du guide suite à la visite très tôt du marché de Bac Ha plutôt que tard à l’heure où tous les autres touristes le visitent). Veuillez noter que Tham n’aura pas de prime pour ce voyage afin qu’il comprenne que toutes les circonstances et raisons n’excusent pas son manque de dévouement et de professionnalisme.</p>
        <p>Je suis d’accord avec vous sur l’importance qu’a le guide dans un voyage, sur les connaissances, l’accompagnement et la générosité dont il doit faire preuve à tout moment. Suite à votre retour sur votre expérience comme directeur des Parcs et du Plein air au Québec, je vais retransmettre vos conseils à notre service de production et opérations afin qu’ils améliorent la formation des guides et ainsi atteindre un niveau de qualité à peu près égal aux guides Canadiens.</p>
        <p>Concernant votre déception sur le manquement à notre slogan « Discover and Share », vous devez comprendre que cette phrase s’applique non seulement au guide mais à Amica dans son ensemble. Cela englobe la vendeuse qui a échangé avec vous avant le départ, le service clientèle qui vous a envoyé les informations préalables au séjour et pendant celui-ci, la partie financière du voyage qui est redistribuée aux projets solidaires que nous soutenons, etc.</p>
        <p>Permettez-moi aussi de vous dire que je suis également non-native du Vietnam. La culture Vietnamienne est très différente, surtout sur un point qui malheureusement, vous a fait défaut, la communication. Je le vois tous les jours dans mon travail ou dans la vie quotidienne. Un serveur vous dira oui juste parce qu’il n’osera pas vous dire qu’il n’a pas compris, quitte au final à vous amener le mauvais plat. La différence de culture oblige alors une personne occidentale comme vous et moi à clairement notifier, questionner, demander, redemander une information pour que l’interlocuteur comprenne bien ce que l’on attend de lui.</p>
        <p>Je reviens sur votre remarque concernant les nuits chez l’habitant. Je comprends que vous auriez aimé pouvoir être seuls mais malheureusement, comme vous a dit notre conseillère Huyen dans un de ses emails, il n’est pas possible de garantir l’exclusivité des maisons, surtout pendant cette période de l’année encore très touristique. Veuillez aussi noter que la plupart des locaux pense que les touristes occidentaux préfèrent manger seuls. Une mise à l’écart de leur part se base sur le fait de vouloir être poli ou de ne pas oser déranger. C’est aussi là que nous, étrangers, devons faire le pas et insister amicalement pour encourager le partage que nous recherchons.</p>
        <p>Le geste commercial que nous vous faisons à hauteur de 150 euros vous sera transféré dans les plus brefs délais (somme symbolique correspondant à la moitié du paiement du guide sur la durée de votre voyage, soit la moitié de 27 euros x 11 jours. Je dis symbolique car nous nous devons bien évidemment de payer intégralement le guide). Je pense que vous comprenez aussi qu’il nous est obligatoire de payer toutes les prestations que vous avez consommées durant ce voyage telles que le service de transport, les nuitées, les repas. Nous espérons votre compréhension sur nos efforts pour répondre à votre demande de compensation financière car ce geste s’inscrit déjà hors cadre de notre politique contractuelle de remboursement/compensation.</p>
        <p>Mes sincères salutations.</p>
    </div>

    <h3 id="title-580">Khách phàn nàn HD không cho khách đi bộ</h3>
    <div id="body-580">
<p><strong>Email kh&aacute;ch gửi&nbsp;:</strong></p>

<p>Bonjour,</p>

<p>Nous sommes bien rentr&eacute;s. Nous nous remettons du d&eacute;calage horaire et nous nous r&eacute;habituions &agrave; Paris.</p>

<p>Nous reviendrons sans doute plus en d&eacute;tail sur le voyage. Mais pour r&eacute;sumer :</p>

<p>- Nous gardons d&#39;excellents souvenirs de ce voyage dans un pays superbe.<br />
- Le contenu du programme propos&eacute; par Amica est en g&eacute;n&eacute;ral bien. Une petite r&eacute;serve pour la fin du programme. Le march&eacute; flottant est d&#39;un int&eacute;r&ecirc;t assez limit&eacute;.? Et du coup, les trois jours au M&eacute;kong se font avec bien trop de temps pass&eacute; dans la voiture. C&#39;est dommage.<br />
- Les guides pour le centre et pour le sud &eacute;taient excellents.<br />
- La grosse r&eacute;serve, pour ne pas dire plus, porte sur le guide de la partie Nord. Il nous semble qu&#39;il y a l&agrave; un probl&egrave;me majeur.<br />
<br />
Je vous ai fait part de certains &eacute;l&eacute;ments durant le s&eacute;jour. Mais il y en a bien d&#39;autres.</p>

<p>Sur les 4 jours en montagne, nous avons eu une seule demi-journ&eacute;e de randonn&eacute;e &agrave; la hauteur. Et heureusement que Mr Pa, de la maison d&#39;h&ocirc;te, nous a accompagn&eacute; pour une des deux randonn&eacute;es, car visiblement, le guide ne connaissait pas le chemin. Cette seconde randonn&eacute;e de l&#39;apr&egrave;s midi s&#39;est faite apr&egrave;s une altercation avec le guide qui voulait &agrave; tout prix que nous passions deux heures &agrave; la maison &quot;pour nous reposer&quot; (ramen&eacute; finalement &agrave; une heure, devant notre insistance qui a failli tourner en conflit ouvert). Au moins une autre randonn&eacute;e a &eacute;t&eacute; annul&eacute;e (on nous a dit que les autorit&eacute;s avaient d&eacute;cid&eacute; de ne pas d&eacute;livrer de permis de visite dans un village - c&#39;est peut &ecirc;tre vrai, peut &ecirc;tre pas, mais nous n&#39;avons clairement pas confiance en ce qui nous &eacute;tait dit par le guide - guide au demeurant tr&egrave;s peu bavard et donnant peu d&#39;explications).</p>

<p>Des tas d&#39;autres anecdotes pourraient &ecirc;tre ajout&eacute;es.&nbsp; Par exemple, la surprise qu&#39;on nous explique, pour le retour de la baie d&#39;Ha Long vers Hanoi, que c&#39;est &quot;tr&egrave;s long par la route normal, tr&egrave;s fatiguant, sans int&eacute;r&ecirc;t&quot;, mais que Amica et la compagnie du chauffeur ne veulent pas payer l&#39;autoroute. Mais si nous payons nous-m&ecirc;mes 100 000 Dongs pour le p&eacute;age, ce sera bien mieux et bien plus rapide. Surpris de ce suppl&eacute;ment demand&eacute; au dernier moment, nous avons refus&eacute;, d&#39;autant plus qu&#39;il ne nous a pas &eacute;t&eacute; dit ce qu&#39;on ferait en arrivant deux heures plut&ocirc;t (on nous directement conduit &agrave; l&#39;a&eacute;roport). Mais ceci laisse une impression bizarre... 4 &euro;...</p>

<p>J&#39;ai cru comprendre que le guide normalement pr&eacute;vu n&#39;a pas &eacute;t&eacute; disponible, et qu&#39;Amica a pris un autre guide externe.</p>

<p>Pour nous, cela ne change rien. La responsabilit&eacute; d&#39;Amica est forte et elle est responsable de ses guides et de ce qu&#39;elle leur explique, du fait quy&#39;elle s&#39;assure qu&#39;ils connaissent bien l&#39;itin&eacute;raire, les attentes des clients. Nous avions bien insist&eacute; avant le d&eacute;part sur l&#39;importance, pour nous, des randonn&eacute;es dans le nord. A part la demi journ&eacute;e &eacute;voqu&eacute;e, le reste s&#39;est r&eacute;duit &agrave; la visite des villages touristiques, avec le flot de touristes, puis reprise de la voiture vers le village suivant. A chaque fois, parfois, une marche sans beaucoup d&#39;int&eacute;r&ecirc;t d&#39;une heure &agrave; d&eacute;ambuler dans un village ou entre deux villages. Certes, la m&eacute;t&eacute;o n&#39;&eacute;tait pas toujours au rendez-vous, mais quand m&ecirc;me.</p>

<p>Bref, la prestation globale d&#39;AMICA est de 5/10 &agrave; mes yeux. Cela ne remet pas en cause les excellents souvenirs que nous rapportons, la qualit&eacute; des guides du centre et du sud. Mais si nous avions choisi Amica, c&#39;&eacute;tait pour le fait qu&#39;elle semblait pouvoir s&#39;adapter &agrave; nos demandes (randonn&eacute;es) et offrir des circuits originaux. Cela n&#39;a pas &eacute;t&eacute; le cas dans le nord. Nous ne faisons pas de tels voyage tous le temps, c&#39;est pourquoi la prestation se doit d&#39;&ecirc;tre &agrave; la hauteur sur l&#39;ensemble du s&eacute;jour.</p>

<p>Vous nous invitez &agrave; aller sur le blog. Nous le ferons sans doute plus tard, pour partager les bons et les moins bons c&ocirc;t&eacute;s.</p>

<p>Bien cordialement</p>

<hr />
<p><strong>QHKH trả lời:</strong></p>

<p>Bonjour <strong>Monsieur Krivine,</strong></p>

<p>Nous avons bien re&ccedil;u votre mail. Je suis desolée de mon retour tardif. Je vous remercie d&rsquo;avoir pris le temps de nous envoyer vos remarques.</p>

<p>Apr&egrave;s lecture de vos commentaires, nous avons travaill&eacute; avec nos guides et nos partenaires pour comprendre et r&eacute;soudre les diff&eacute;rents probl&egrave;mes. Nous tenons &agrave; nous excuser et vous apporter quelques explications :</p>

<p>Nous sommes vraiment d&eacute;sol&eacute;es que le guide du Nord ne vous ait pas convenu, d&rsquo;autant plus que nous avions bien compris que les randonn&eacute;es dans le nord &eacute;taient importantes pour vous. Malheureusement,&nbsp;le guide pr&eacute;vu pour vous au d&eacute;part est tomb&eacute; gravement malade un jour avant votre arriv&eacute;e au Vietnam et il nous a fallu en trouver un nouveau en urgence, ce qui n&rsquo;a pas &eacute;t&eacute; facile vu que votre voyage s&rsquo;est d&eacute;roul&eacute; en haute saison pour le Vietnam et que pratiquement tous les guides &eacute;taient occup&eacute;s. Ce nouveau guide a donc eu tr&egrave;s peu de temps pour se familiariser avec votre programme, m&ecirc;me si nous lui avions clairement expliqu&eacute; vos attentes. J&rsquo;esp&egrave;re que vous comprendrez que nous avons essay&eacute; de faire au mieux d&egrave;s que nous avons appris la maladie de votre premier guide. Je tenais aussi &agrave; m&rsquo;excuser concernant la requ&ecirc;te de votre guide concernant le suppl&eacute;ment qu&rsquo;il vous a demand&eacute; pour l&rsquo;autoroute. Il a eu un comportement non professionnel et tout &agrave; fait inacceptable. Nous avons donc d&eacute;cid&eacute; de ne pas lui accorder de bonus et avons mis fin &agrave; notre collaboration avec lui. Il ne travaillera plus pour notre agence afin qu&rsquo;une telle situation ne se reproduise plus.</p>

<p>Je regrette sinc&egrave;rement que cette partie de votre voyage ne fut pas &agrave; la hauteur de vos esp&eacute;rances. J&rsquo;esp&egrave;re tout de m&ecirc;me que la promenade en v&eacute;lo offerte en compensation vous aura satisfaite et que les inconv&eacute;nients survenus n&rsquo;auront pas entach&eacute; votre image du Vietnam.</p>

<p>Nous tenons &agrave; vous remercier de votre contribution, et nous allons tout mettre en &oelig;uvre &eacute;viter qu&rsquo;un tel cas se reproduise.</p>

<p>Encore une fois, merci beaucoup pour vos retours.</p>

<p>Toute l&rsquo;&eacute;quipe d&rsquo;Amica Travel vous souhaite une bonne sant&eacute; et beaucoup de bonheur dans votre vie.&nbsp;</p>

<p>Cordialement,</p>

<p>Thu TRAN</p>
    </div>

    <h3 id="title-581">Khách phàn nàn về CT ở Lào và đòi bồi thường</h3>
    <div id="body-581">
<p><strong>Email của kh&aacute;ch&nbsp;:</strong></p>

<p>Bonjour,</p>

<p>Nous r&eacute;pondons &agrave; votre message du 21/02 nous demandant de vous communiquer nos ressentis et de partager notre exp&eacute;rience.</p>

<p>La pr&eacute;paration du voyage, la coordination avec&nbsp;<strong>Yen Nhu NGUYEN</strong>&nbsp;a &eacute;t&eacute; tr&egrave;s bonne.</p>

<p>A l&rsquo;usage, le circuit s&rsquo;est r&eacute;v&eacute;l&eacute; correspondre &agrave; nos souhaits, nous avons trouv&eacute; les h&ocirc;tels entre tr&egrave;s bons et bons ( emplacement , qualit&eacute;, propret&eacute; ) sauf celui du venredi 9/02 Ile de Khone ( bungalow d&eacute;class&eacute; donnant sur des poubelles et personnel d&eacute;sagr&eacute;able.)</p>

<p>Les chauffeurs ont tous &eacute;t&eacute; serviables et prudents. Merci d&rsquo;avoir remplac&eacute; rapidement voiture et chauffeur apr&egrave;s l&rsquo;accident.</p>

<p>Les guides, notre premier guide &laquo; KI &laquo; excellent, comp&eacute;tent, disponible, serviable, de bonne humeur et ne manquant jamais de nous donner de lui m&ecirc;me des informations int&eacute;ressantes sur les sujets du voyage.</p>

<p>Le second, beaucoup plus jeune , serviable, disponible, de bonne volont&eacute;&nbsp; mais n&rsquo;a pas su commenter les sites, temples, paysages ...que nous visitions.</p>

<p>Le voyage : &eacute;norme d&eacute;ception &agrave; l&rsquo;arriv&eacute;e &agrave; Vientiane , personne pour nous accueillir vers 11 du matin. Il nous a fallu t&eacute;l&eacute;phoner&nbsp; et attendre plus d&rsquo;une heure, pour que nous soit envoy&eacute; un chauffeur qui n&rsquo;a trouv&eacute; &agrave; dire que &laquo; je croyais que c&rsquo;&eacute;tait 11h du soir &laquo;.</p>

<p>A l&rsquo;arriv&eacute;e &agrave; Luang Prabang aucune instruction quant &agrave; l&rsquo;heure du d&eacute;part pour l&rsquo;excursion du lendemain.</p>

<p>De plus l&rsquo;excursion &agrave; v&eacute;lo du vendredi 16/02 &agrave; Nong Khiaw a &eacute;t&eacute; annul&eacute;e faute de v&eacute;los.</p>

<p>Nous n&rsquo;avons vu de tout le s&eacute;jour, personne d&rsquo;Amica Travel &nbsp;pour un briefing, ce qui est habituel dans ce type de voyage ,notamment &nbsp;&agrave; Luang Prabang ou vous avez votre Agence , malgr&eacute; notre demande.</p>

<p>Enfin, et c&rsquo;est inadmissible, Amica Travel a &eacute;t&eacute; ferm&eacute;e du 14 au 20 fevrier inclus , nombreux mails pour nous pr&eacute;venir au dernier moment, dont celui du 18/02( ci-joint)&nbsp;&nbsp;alors que nous nous &eacute;tions assur&eacute;s aupr&egrave;s de vous de savoir si de voyager pendant la F&ecirc;te du Nouvel An Chinois posait un probl&egrave;me&nbsp; (notre mail du 18/12/2017 r&eacute;pondu le 22/12/2017).&nbsp;</p>

<p>Il est inimaginable qu&rsquo;une Agence de voyage ayant des touristes en circuit n&rsquo;ait pas un ou une responsable joignable physiquement facilement en cas de probl&egrave;me. Pour nous c&rsquo;est une faute. Nous avons compris que notre charg&eacute;e de relation client &eacute;tait au Vietnam ?&nbsp;</p>

<p>En effet le 17/02 au matin M.A.Serruques a &eacute;t&eacute; pris d&rsquo;une forte intoxication et n&rsquo;a pas pu prendre avec son &eacute;pouse l&rsquo;avion Vietnam Airways de la soir&eacute;e comme pr&eacute;vu.</p>

<p>Il a du m&ecirc;me faire l&rsquo;objet d&rsquo;une hospitalisation le 18/02 &agrave; l&rsquo;Hopital de Luang Prabang&nbsp;</p>

<p>Le couple Serruques a du quitter l&rsquo;hotel Dalabua et trouver avec difficult&eacute; un h&ocirc;tel pour 2 nuits.</p>

<p>Il n&rsquo;y avait personne pour s&rsquo;occuper d&rsquo;annuler les deux places de retour LP/H/P que Amica Travel nous avait r&eacute;serv&eacute;es sur Vietnam Aiways via votre correspondant en France.</p>

<p>M.A Serruques vous a demand&eacute; de traiter avec votre correspondant Vietnam Airlines pour lui rembourser le voyage retour pour lui m&ecirc;me et son &eacute;pouse . A cette demande vous r&eacute;pondez selon mail&nbsp; du 19/02 joint que c&rsquo;est &agrave; nous de traiter avec VOTRE correspondant.</p>

<p>Nous insistons pour que vous r&eacute;alisiez cette d&eacute;marche et nous montrer ainsi l&rsquo;int&eacute;r&ecirc;t que vous pouvez porter &agrave; un de&nbsp;vos clients malade sur place.</p>

<p>Je vous rappelle que nous nous &eacute;tions recommand&eacute;s de nos amis M. Philippe Brault et M. Philippe Martin pour le choix d&rsquo;Amica Travel.</p>

<p>Sinc&egrave;rement,</p>

<p>============</p>

<p><strong>QHKH trả lời:</strong></p>

<p>Bonjour Madame et Monsieur Serruques,</p>

<p>Nous nous permettons de revenir vers vous via cette lettre pour vous expliquer les raisons de nos d&eacute;faillances lors de votre voyage :</p>

<p>- Pour votre arriv&eacute;e, le guide s&rsquo;est excus&eacute; de n&rsquo;avoir pas pu vous accueillir avec le chauffeur, &agrave; l&rsquo;heure indiqu&eacute;e, car il s&rsquo;est tromp&eacute; d&rsquo;horaire. Le guide s&rsquo;en ai excus&eacute;.</p>

<p>- Concernant votre excursion en v&eacute;lo du vendredi 16/02 &agrave; Nong Khiaw, celle-ci n&rsquo;a pas pu se r&eacute;aliser car les v&eacute;los restants &eacute;taient en panne. Nous vous avons propos&eacute; un repas en compensation, pour vous faire &eacute;viter une balade non s&eacute;curis&eacute;e.</p>

<p>- Nous avons appr&eacute;ci&eacute; votre demande de visite de notre bureau de Luang Prabang, mais malheureusement ce jour-l&agrave;, l&rsquo;effectif &eacute;tait restreint pour la semaine de cong&eacute;s du T&ecirc;t, le nouvel an lunaire.</p>

<p>- Enfin, concernant votre intoxication alimentaire, nous en sommes sinc&egrave;rement navr&eacute;s que &ccedil;a se soit pass&eacute; comme &ccedil;a. Nous esp&eacute;rons que vous alliez mieux depuis. Pour les cas d&rsquo;urgence, nous sollicitons nos voyageurs &agrave; nous contacter directement par t&eacute;l&eacute;phone, afin que nous puissions r&eacute;agir tr&egrave;s rapidement.</p>

<p>Sachez que votre retour nous a &eacute;t&eacute; pr&eacute;cieux, car il permettra d&rsquo;am&eacute;liorer sans cesse nos services.</p>

<p>Pour renouveler nos excuses, nous vous avons exp&eacute;di&eacute; un cadeau personnalis&eacute; et nous esp&eacute;rons qu&rsquo;il vous plaira.</p>

<p>Nous esp&eacute;rons vous revoir bient&ocirc;t parmi nous,</p>

<p>Belle journ&eacute;e &agrave; vous !</p>

<p>Bien amica-lement,</p>

<p>====================</p>

<p>Tặng g&oacute;i qu&agrave; bien-&ecirc;tre cho kh&aacute;ch, bao gồm:&nbsp;</p>

<p>1. Hộp sơn m&agrave;i&nbsp;</p>

<p>2. Cafe Vn</p>

<p>3. Tr&agrave; hoa</p>

<p>4. Savon + tinh dầu&nbsp;</p>

<p>T&acirc;m thư gửi kh&aacute;ch:&nbsp;</p>

<p>Bonjour Mme &amp; M. SERRUQUES,</p>

<p>Nous sommes d&eacute;sol&eacute;s des probl&egrave;mes que vous avez rencontr&eacute;s pendant votre voyage au Laos. Est-ce vous allez mieux? Nous sommes inquiets de votre sant&eacute;. Avec l&#39;espoir que vous serez en bonne sant&eacute;, nous vous offrons ces cadeaux :</p>

<p>&bull; D&#39;origine naturelle et riche en vitamines, ce th&eacute; aux cinq fleurs qui est consid&eacute;r&eacute; comme un bon rem&egrave;de pour la sant&eacute;, offre de nombreux usages: r&eacute;duction du stress et de la fatigue, am&eacute;lioration de la condition physique, renforcement de la r&eacute;sistance,...</p>

<p>&bull; Voici un savon fait main, avec un parfum doux qui vous fera sentir d&eacute;tendu.</p>

<p>&bull; Enfin, une tasse de caf&eacute; vietnamien chaque matin vous donnera de l&rsquo;&eacute;nergie pour la journ&eacute;e !</p>

<p>Nous avons r&eacute;serv&eacute; toute notre attention dans ces petits cadeaux et les avons tous mis dans une bo&icirc;te laqu&eacute;e - un produit de grande marque connue depuis quatre g&eacute;n&eacute;rations &agrave; Hanoi.</p>

<p>Nous esp&eacute;rons que vous appr&eacute;cierez ces cadeaux personnalis&eacute;s. Nous vous souhaitons un bon r&eacute;tablissement.</p>

<p>Sinc&egrave;rement,</p>
    </div>


    <h3 id="title-582">Khách trúng độc ở Lào</h3>
    <div id="body-582">
        <p><strong>Email khách viết:</strong><br />
            Merci pour vos v&oelig;ux.</p>
<p>Nous vous souhaitons &eacute;galement une tr&egrave;s bonne ann&eacute;e 2018.</p>
<p>En ce qui concerne le voyage, nous sommes au regret de ne lui attribuer qu&#39;une note de 6/10 car seulement 4 jours sur les 8 ont &eacute;t&eacute; conformes au programme.</p>
<p>Vous trouverez nos commentaires dans le fichier joint.</p>
<p>Dans le devis page 3, il &eacute;tait pr&eacute;vu un chauffeur tous les jours . Nous pensions donc que les bagages seraient transport&eacute;s chez l&#39;habitant. Il n&#39;&eacute;tait nulle part pr&eacute;cis&eacute; que nous n&#39;aurions pas acc&egrave;s &agrave; nos valises pendant 3 jours et que nous devrions port&eacute;s nos affaires. Si nous avions &eacute;t&eacute; inform&eacute; &agrave; l&#39;avance, nous aurions pr&eacute;par&eacute; diff&eacute;remment nos sacs et mieux support&eacute; le froid.</p>
<p>D&#39;autre part, &agrave; la lecture du programme, nous esp&eacute;rions marcher sur des sentiers et non sur des pistes poussi&eacute;reuses emprunt&eacute;es par des motos. Pour nous, randonner est synonyme de calme et de nature. De plus, la moiti&eacute; des visites des villages pr&eacute;vues les jours 2, 3 et 4 n&#39;&eacute;taient pas sur le trajet. Le guide francophone n&#39;ayant jamais fait ce circuit n&#39;avait pas d&#39;explication et le guide local a affirm&eacute; que le programme n&#39;&eacute;tait pas &agrave; jour depuis plusieurs ann&eacute;es. Ceci d&eacute;tonne un manque de coordination dans vos services.</p>
<p>Point positif, la nourriture pr&eacute;par&eacute;e par les guides &eacute;taient excellentes.</p>
<p>En conclusion, le programme n&#39;ayant pas &eacute;t&eacute; respect&eacute;, le co&ucirc;t du s&eacute;jour n&#39;est pas justifi&eacute; et nous sommes tr&egrave;s d&eacute;&ccedil;us.</p>
<p>G Despierre<br />
------------------<br />
Ravis d&#39;apprendre que vous prenez en compte nos commentaires et que vos &eacute;quipes travaillent &agrave; r&eacute;soudre les diff&eacute;rents probl&egrave;mes afin que les prochains circuits soient conformes au descriptif. En attendant, en ce qui nous concerne,un geste commercial de votre part en d&eacute;dommagement du non respect du programme serait tr&egrave;s appr&eacute;ci&eacute;. Cordialement.</p>

<p><strong>QHKH trả lời:</strong></p>
<p>Bonjour Madame Despierre,&nbsp;</p>
<p>Nous vous remercions sinc&egrave;rement pour votre retour. Encore une fois, nous regrettons que votre voyage n&rsquo;ait pas &eacute;t&eacute; &agrave; la hauteur de vos esp&eacute;rances, &eacute;tant donn&eacute; que la satisfaction du client est le but ultime d&#39;Amica Travel. Nous tenons &agrave; nous excuser et &agrave; vous apporter quelques explications :</p>
<p>- Premi&egrave;rement, en ce qui concerne le programme du voyage, nous veillons &agrave; respecter scrupuleusement l&rsquo;itin&eacute;raire pr&eacute;vu, car au deuxi&egrave;me jour du voyage, vous voulez visiter un festival hmong, mais dans un pays comme le Laos, la logistique &eacute;tant difficile, nous ne pouvons pas faire cette bifurcation de derni&egrave;re minute, car nous allions arriver trop tard &agrave; Xeng Kham.&nbsp;<br />
- Deuxi&egrave;mement, comme vous l&rsquo;avez vu de vos propres yeux, le Laos est un pays en voie de d&eacute;veloppement, ce qui explique l&rsquo;&eacute;tat de certaines routes. La seule chose que nous pouvons vous promettre c&rsquo;est de tenir compte de cette remarque pour proposer des parcours moins accident&eacute;s pour nos voyageurs et pr&eacute;voir des personnes disponibles pour vous aider &agrave; porter les bagages.&nbsp;</p>

<p>Compte tenu des circonstances, et apr&egrave;s analyse de votre plainte des d&eacute;fauts d&rsquo;organisation, nous d&eacute;cidons de vous faire un geste commercial avec le remboursement de la somme de 300 USD.&nbsp;</p>

<p>Nous esp&eacute;rons que pour cette fois-ci d&rsquo;avoir enfin r&eacute;pondu &agrave; vos attentes.</p>

<p>Bien amica-lement,</p>
    </div>

</div>