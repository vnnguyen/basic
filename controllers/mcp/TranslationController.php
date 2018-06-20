<?

namespace app\controllers\mcp;

use \common\models\Field;
use \common\models\ListItem;
use \common\models\Listt;
use \common\models\Translation;

use Yii;
use yii\data\Pagination;
use yii\web\HttpException;

class TranslationController extends \app\controllers\MyController
{
    // Translation index
    public function actionIndex($id = 0)
    {
        $sql = 'SELECT * FROM i18n_source_messages ORDER BY id DESC LIMIT 10';
        $recentlyAddedText = Yii::$app->db->createCommand($sql)
            ->queryAll();
        return $this->render('translation_index', [
            'recentlyAddedText'=>$recentlyAddedText,
        ]);
    }

    // Add new translation string
    public function actionC()
    {
        $theForm = new \app\models\TranslationForm;
        if ($theForm->load(Yii::$app->request->post()) && $theForm->validate()) {
            // Tim xem co source message chua
            $sql = 'SELECT id FROM i18n_source_messages WHERE category=:c AND message=:m LIMIT 1';
            $msgId = Yii::$app->db->createCommand($sql, [
                ':c'=>$theForm->cat,
                ':m'=>$theForm->source_message,
            ])->queryScalar();
            if (!$msgId) {
                Yii::$app->db->createCommand()
                    ->insert('i18n_source_messages', [
                        'category'=>$theForm->cat,
                        'message'=>$theForm->source_message,
                    ])->execute();
            } else {
                Yii::$app->db->createCommand()
                    ->update('i18n_source_messages', [
                        'category'=>$theForm->cat,
                        'message'=>$theForm->source_message,
                    ], [
                        'id'=>$msgId,
                    ])->execute();
            }
            foreach ($theForm->message as $lang=>$message) {
                if ($message != '') {
                    Yii::$app->db->createCommand()
                        ->insert('i18n_messages', [
                            'id'=>Yii::$app->db->getLastInsertID(),
                            'language'=>$lang,
                            'translation'=>$message,
                        ])->execute();
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('mcp', 'New message has been added: ').$theForm->source_message);
            return $this->redirect('/mcp/translations/c');
        }

        return $this->render('translation_c', [
            'theForm'=>$theForm,
        ]);
    }

    // Translate a cat
    public function actionCat($cat = '')
    {
        if ($cat == '') {
            $cat = 'nav';
        }
        $sql = 'SELECT *, (SELECT translation FROM i18n_messages WHERE language="vi" AND id=i18n_source_messages.id LIMIT 1) AS translation FROM i18n_source_messages WHERE category=:cat ORDER BY message';
        $theMessages = Yii::$app->db->createCommand($sql, [':cat'=>$cat])->queryAll();
        return $this->render('translation_cat', [
            'theMessages'=>$theMessages,
            'cat'=>$cat,
        ]);
    }

    // Translate fields
    public function actionFields($lang = '', $cat = '')
    {
        if ($lang == '') {
            $lang = Yii::$app->language;
        }

        $theFields = Field::find()
            ->orderBy('name')
            ->asArray()
            ->all();

        // SYS translations
        $sysLabelTrans = Translation::find()
            ->select(['cat', 'source', 'translation'])
            ->where(['account_id'=>0, 'cat'=>'field_label', 'language'=>$lang])
            ->indexBy('source')
            ->asArray()
            ->all();
        $sysHelpTrans = Translation::find()
            ->select(['cat', 'source', 'translation'])
            ->where(['account_id'=>0, 'cat'=>'field_help', 'language'=>$lang])
            ->indexBy('source')
            ->asArray()
            ->all();

        if (Yii::$app->request->isPost) {
            if (isset($_POST['label'], $_POST['help'])
                && is_array($_POST['label'])
                && is_array($_POST['help'])
                ) {
                foreach ($_POST['label'] as $key=>$val) {
                    if (!isset($sysLabelTrans[$key]) && $val != '') {
                        // Insert
                        Yii::$app->db->createCommand()->insert('translations', [
                            'account_id'=>0,
                            'updated_dt'=>NOW,
                            'updated_by'=>USER_ID,
                            'language'=>$lang,
                            'cat'=>'field_label',
                            'source'=>$key,
                            'translation'=>$val,
                            ])->execute();
                    } elseif (isset($sysLabelTrans[$key]) && $val != $sysLabelTrans[$key]) {
                        // Update
                        Yii::$app->db->createCommand()->update('translations', [
                            'updated_dt'=>NOW,
                            'updated_by'=>USER_ID,
                            'translation'=>$val,
                            ], [
                            'language'=>$lang,
                            'cat'=>'field_label',
                            'source'=>$key,
                            ])->execute();
                    }
                }
                foreach ($_POST['help'] as $key=>$val) {
                    if (!isset($sysHelpTrans[$key]) && $val != '') {
                        // Insert
                        Yii::$app->db->createCommand()->insert('translations', [
                            'account_id'=>0,
                            'updated_dt'=>NOW,
                            'updated_by'=>USER_ID,
                            'language'=>$lang,
                            'cat'=>'field_help',
                            'source'=>$key,
                            'translation'=>$val,
                            ])->execute();
                    } elseif (isset($sysHelpTrans[$key]) && $val != $sysHelpTrans[$key]) {
                        // Update
                        Yii::$app->db->createCommand()->update('translations', [
                            'updated_dt'=>NOW,
                            'updated_by'=>USER_ID,
                            'translation'=>$val,
                            ], [
                            'language'=>$lang,
                            'cat'=>'field_help',
                            'source'=>$key,
                            ])->execute();
                    }
                }
                Yii::$app->session->setFlash('success', Yii::t('a', 'Translation has been updated'));
                return $this->redirect('/mcp/translations');
            }
        }

        return $this->render('translation_fields', [
            'lang'=>$lang,
            'cat'=>$cat,

            'theFields'=>$theFields,
            'sysLabelTrans'=>$sysLabelTrans,
            'sysHelpTrans'=>$sysHelpTrans,
        ]);
    }

    // Translate list
    public function actionLists($id = null, $lang = '')
    {
        if ($lang == '') {
            $lang = Yii::$app->language;
        }

        if (!isset($id)) {
            $theLists = Listt::find()
                ->orderBy('grouping, sorder')
                ->asArray()
                ->all();
            return $this->render('translation_list_index', [
                'theLists'=>$theLists,
                'lang'=>$lang,
            ]);
        }

        // Single list
        $theList = Listt::find()
            ->where(['account_id'=>ACCOUNT_ID, 'id'=>$id])
            ->where(['id'=>$id])
            ->with([
                'items',
                'items.parent',
                ])
            ->one();
        if (!$theList) {
            throw new HttpException(404, 'List not found');
        }

        if (Yii::$app->request->isPost) {
            if (isset($_POST['list']) && isset($_POST['lang'])
                && isset($_POST['id']) && is_array($_POST['id'])
                && isset($_POST['trans']) && is_array($_POST['trans'])
                ) {
                // \fCore::expose($_POST); exit;
                for ($i = 0; $i < count($_POST['item']); $i ++) {
                    $sql2 = 'UPDATE translations SET language=:lang, translation=:text WHERE id=:id LIMIT 1';
                    /*Yii::$app->db->createCommand($sql2, [
                        ':no'=>trim($_POST['no'][$i]),
                        ':alley'=>trim($_POST['alley'][$i]),
                        ':street'=>trim($_POST['street'][$i]),
                        ':id'=>$_POST['id'][$i],
                        ])->execute();*/
                }
                return $this->redirect('/mcp/translations/lists');
            }
        }

        return $this->render('translation_list_single', [
            'theList'=>$theList,
            'lang'=>$lang,
        ]);
    }

    // Translate app messages
    public function actionMessages($cat = '', $lang = '')
    {
        if ($lang == '') {
            $lang = Yii::$app->language;
        }
        if ($cat == '') {
            // $sql = 'SELECT DISTINCT category FROM at_language_source ORDER BY category';
            // $theCats = Yii::$app->db->createCommand($sql)->queryAll();
            $theCats = [
                ['category'=>'p', 'name'=>'Property'],
                ['category'=>'v', 'name'=>'Valuation'],
                ['category'=>'a', 'name'=>'Account CP'],
                ['category'=>'app', 'name'=>'Application wide'],
            ];
            return $this->render('translation_message_index', [
                'theCats'=>$theCats,
                'lang'=>$lang,
            ]);
        }

        //$text = include('/var/www/my.evalpro.vn/messages/vi/p.php');
        // $sql = 'SELECT * FROM at_language_source WHERE category=:cat ORDER BY message';
        //$theMessages = Yii::$app->db->createCommand($sql, [':cat'=>$cat])->queryAll();

        $file = Yii::getAlias('@app').'/messages/'.$lang.'/'.$cat.'.php';
        if (!file_exists($file)) {
            throw new HttpException(404, 'Source language messages not found.');
        }

        $theMessages = include($file);
        return $this->render('translation_message_single', [
            'cat'=>$cat,
            'lang'=>$lang,
            'theMessages'=>$theMessages,
        ]);
    }

    // 160520: Cuong to edit to new address format
    public function actionCuongAddr($page = 0)
    {
        // Huan & Cuong
        if (!in_array(USER_ID, [1, 19])) {
            throw new HttpException(403);
        }
        $query = \common\models\Property::find()
            ->where(['account_id'=>ACCOUNT_ID]);
        $countQuery = clone $query;
        $pagination = new \yii\data\Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>20,
        ]);
        $thePx = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        if (Yii::$app->request->isPost) {
            if (isset($_POST['id']) && is_array($_POST['id'])
                && isset($_POST['no']) && is_array($_POST['no'])
                && isset($_POST['alley']) && is_array($_POST['alley'])
                && isset($_POST['street']) && is_array($_POST['street'])
                ) {
                // \fCore::expose($_POST); exit;
                for ($i = 0; $i < count($_POST['id']); $i ++) {
                    $sql2 = 'UPDATE at_properties SET addr_no=:no, addr_alley=:alley, addr_street=:street WHERE id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql2, [
                        ':no'=>trim($_POST['no'][$i]),
                        ':alley'=>trim($_POST['alley'][$i]),
                        ':street'=>trim($_POST['street'][$i]),
                        ':id'=>$_POST['id'][$i],
                        ])->execute();
                }
                return $this->redirect('/tool/cuong-addr?page='.((int)$page + 1));
            }
        }
        return $this->render('tool_cuong-addr', [
            'thePx'=>$thePx,
            'pagination'=>$pagination,
        ]);
    }

    // Text between
    private function tb($str, $start, $end)
    {
        $pos = strpos($str, $start);
        if (false === $pos) {
            return '';
        }
        $str = StringHelper::byteSubstr($str, $pos + StringHelper::byteLength($start));
        $pos2 = strpos($str, $end);
        if (false === $pos2) {
            return '';
        }
        return StringHelper::byteSubstr($str, 0, $pos2);
    }

    // Test audo download of cafeland.vn page
    public function actionAutodownCafeland($url = '')
    {
        if (isset($_POST['url']) && substr($_POST['url'], 0, 27) == 'http://cafeland.vn/nha-dat/') {
            $url = $_POST['url'];
            //$dom = new Dom;
            //$dom->loadFromUrl($url);
            //\fCore::expose($dom);
            //exit;
            //$html = $dom->outerHtml;

            //$pos = strpos($html, '<div class="container" style="max-width:1022px;margin-top:10px">');
            //$html = substr($html, $pos);
            //\fCore::expose($html);
            //exit;

            $html = Yii::$app->request->post('html', '');

            $r = [];
            $r[] = $this->tb($html, '<label>Mã tài sản: ', '</label>');
            $r[] = $this->tb($html, '<label>Vị trí: ', '</label>');
            $r[] = $this->tb($html, '<label>Diện tích: ', '</label>');
            $r[] = $this->tb($html, '<label>Pháp lý: ', '</label>');
            $r[] = $this->tb($html, '<label>Ngày đăng: ', '</label>');
            $r[] = $this->tb($html, '<label>Người liên hệ: ', '</label>');
            $r[] = $this->tb($html, "showfullphone(this,'", "')");
            $r[] = $this->tb($html, '<label>Địa chỉ: ', '</label>');

            $x = [];
            $x[] = $this->tb($html, '<td style="width:30%">Diện tích sử dụng: ', '</td>');
            $x[] = $this->tb($html, '<td style="width:45%">Loại địa ốc: ', '</td>');
            $x[] = $this->tb($html, '<td>Chiều ngang trước: ', '</td>');
            $x[] = $this->tb($html, '<td>Đường trước nhà: ', '</td>');
            $x[] = $this->tb($html, '<td>Chiều dài: ', '</td>');
            $x[] = $this->tb($html, '<td>Tuyến đường: ', '</td>');
            $x[] = $this->tb($html, '<td>Số lầu: ', '</td>');
            $x[] = html_entity_decode($this->tb($html, 'id="sl_mota" disabled >', '</textarea>'));
            \fCore::expose($r);
            \fCore::expose($x);
            exit;
/*
            $p = $dom->find('label.chuindam')[0];
            $a = $dom->find('label.chuindam')[1];
            $ix = $dom->find('div.carousel-inner img');
            $matin = $dom->find('div.lopline label')[0];
            $vitri = $dom->find('div.lopline label')[1];
            $dientich = $dom->find('div.lopline label')[2];
            $phaply = $dom->find('div.lopline label')[3];
            $duan = $dom->find('div.lopline label')[4];
            $ngaydang = $dom->find('div.lopline label')[5];
            $nguoi = $dom->find('div.lopline label')[5];
            $dt = $dom->find('div.lopline label')[5];
            $ngaydang = $dom->find('div.lopline label')[5];
            $mota = $dom->find('textarea#sl_mota')[0];
            echo 'PRICE=', $p->text;
            echo '<br>AREA=', $a->text;
            echo '<br>MA TIN=', $matin->text;
            echo '<br>VI TRI=', $vitri->text;
            echo '<br>DIEN TICH=', $dientich->text;
            echo '<br>PHAP LY=', $phaply->text;
            echo '<br>DU AN=', $duan->text;
            echo '<br>NGAY=', $ngaydang->text;
            echo '<br>MO TA=', nl2br($mota->text);
            foreach ($ix as $cnt=>$i) {
                $src = $i->getAttribute('src');
                echo '<br>IMG=', $src;
                // $image = file_get_contents($src);
                // file_put_contents('/var/www/my.evalpro.vn/runtime/test-image-'.$cnt.'.jpg', $image);
            }
            exit;
            */
        }

        return $this->render('tool_autodown-cafeland', [
            'url'=>$url,
        ]);

    }
}
