<?php

namespace app\controllers\b2b;

use common\models\Client;
use common\models\Company;
use common\models\Country;
use common\models\Venue;
use common\models\Search;
use common\models\Ct;
use common\models\Cpt;
use common\models\Day;
use common\models\Kase;
use common\models\Inquiry;
use app\models\Message;
use common\models\ProfileTA;
use common\models\Sysnote;
use common\models\Tour;
use common\models\User;
use common\models\Product;
use common\models\Booking;
use common\models\Task;
use common\models\SampleTourDay;
use common\models\SampleTourProgram;
use Mailgun\Mailgun;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\GridView;
use yii\data\Pagination;
use yii\web\HttpException;

class ClientController extends \app\controllers\MyController
{
    // List of clients
    public function actionIndex($name = '')
    {
        $query = Client::find()
            ->with([
                'metas'=>function($q){
                    return $q->select(['rid', 'name', 'value']);
                },
                'cases'=>function($q){
                    return $q->select(['id', 'company_id']);
                },
                'cases.bookings'=>function($q) {
                    return $q->select(['id', 'case_id', 'status']);
                },
            ]);
        if ($name != '') {
            $query->andWhere(['like', 'name', $name]);
        }

        $theClients = $query
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('client_index', [
            'theClients'=>$theClients,
            'name'=>$name,
        ]);
    }

    /**
     * Add a new client
     */
    public function actionC($type = 'org')
    {
        if (!in_array(USER_ID, [1, 1087, 11724, 15860])) {
            throw new HttpException(404, Yii::t('x', 'Access denied.'));
        }

        $theClient = new Client;
        $theClient->owner_id = USER_ID;

        $data = [
            'tel'=>[],
            'email'=>[],
            'url'=>[],
            'addr'=>[],
        ];

        if (Yii::$app->request->isPost) {
            // Load from POST
            if (!isset($_POST['name'])) {
                $_POST['name'] = [];
            }
            $cntAddr = 0;
            $cntTel = 0;
            foreach ($_POST['name'] as $i=>$name) {
                if (in_array($name, ['tel', 'fax'])) {
                    $data['tel'][] = [
                        'name'=>$name,
                        'value'=>$_POST['full'][$cntTel] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'full'=>$_POST['full'][$cntTel] ?? '',
                        'format'=>'tel',
                    ];
                    $cntTel ++;
                } elseif (in_array($name, ['email'])) {
                    $data['email'][] = [
                        'name'=>$name,
                        'value'=>$_POST['value'][$i] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'email',
                    ];
                } elseif (in_array($name, ['facebook', 'linkedin', 'website', 'url', 'link'])) {
                    $data['url'][] = [
                        'name'=>$name,
                        'value'=>$_POST['value'][$i] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'url',
                    ];
                } elseif (in_array($name, ['address'])) {
                    $addr_line_1 = $_POST['addr_line_1'][$cntAddr] ?? '';
                    $addr_line_2 = $_POST['addr_line_2'][$cntAddr] ?? '';
                    $addr_city = $_POST['addr_city'][$cntAddr] ?? '';
                    $addr_state = $_POST['addr_state'][$cntAddr] ?? '';
                    $addr_postal = $_POST['addr_postal'][$cntAddr] ?? '';
                    $addr_country = $_POST['addr_country'][$cntAddr] ?? '';

                    $value = implode("\n", [$addr_line_1, $addr_line_2, $addr_city, $addr_state, $addr_postal, $addr_country]);

                    $data['addr'][] = [
                        'name'=>$name,
                        'value'=>$value,
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'address',
                        'addr_line_1'=>$addr_line_1,
                        'addr_line_2'=>$addr_line_2,
                        'addr_city'=>$addr_city,
                        'addr_state'=>$addr_state,
                        'addr_postal'=>$addr_postal,
                        'addr_country'=>$addr_country,
                    ];
                    $cntAddr ++;
                }
            }
        }

        if ($theClient->load(Yii::$app->request->post()) && $theClient->validate()) {
            $theClient->created_dt = NOW;
            $theClient->created_by = USER_ID;

            // Logo
            if (isset($_POST['slim']) && is_array($_POST['slim'])) {
                $slim = json_decode($_POST['slim'][0], true);
                // Move file
                if (isset($slim['path']) && $slim['path'] != '') {
                    $uploadDir = 'upload/companies/'.substr($theClient->created_dt, 0, 7);
                    if (!is_dir(Yii::getAlias('@webroot').'/'.$uploadDir)) {
                        mkdir(Yii::getAlias('@webroot').'/'.$uploadDir);
                    }
                    $oldAvatar = $slim['path'];
                    $newAvatar = str_replace('assets/slim_1.1.1/server/tmp/', $uploadDir.'/', $slim['path']);
                    rename(Yii::getAlias('@webroot').$oldAvatar, Yii::getAlias('@webroot').$newAvatar);
                    $theClient->image = Yii::getAlias('@web').$newAvatar;
                }
            }

            $theClient->save(false);

            foreach ($data as $type=>$group) {
                foreach ($group as $item) {
                    Yii::$app->db->createCommand()->insert('metas', [
                        'created_dt'=>NOW,
                        'created_by'=>USER_ID,
                        'updated_dt'=>NOW,
                        'updated_by'=>USER_ID,
                        'rtype'=>'client',
                        'rid'=>$theClient->id,
                        'name'=>$item['name'],
                        'value'=>$item['value'],
                        'note'=>$item['note'],
                        'format'=>$item['format'],
                    ])->execute();
                }
            }
            return $this->redirect('/b2b/clients/r/'.$theClient->id);
        }

        $ownerList = User::find()
            ->where(['status'=>'on'])
            ->select(['id', 'name'=>'nickname'])
            ->asArray()
            ->all();

        $countryList = Country::find()
            ->where(['status'=>'on'])
            ->select(['code', 'name'=>'name_en'])
            ->orderBy('name')
            ->asArray()
            ->all();

        return $this->render('client_u', [
            'theClient'=>$theClient,
            'ownerList'=>$ownerList,
            'countryList'=>$countryList,
            'data'=>$data,
            'type'=>$type,
        ]);
    }

    // Login cho client SI
    public function actionR($id = 0, $view = 'cases')
    {
        $theClient = Client::find()
            ->where(['id'=>$id])
            ->with([
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'owner'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'cases'=>function($q) {
                    $q->select(['id', 'name', 'status', 'deal_status', 'created_at', 'owner_id', 'company_id', 'is_priority'])->orderBy('created_at DESC');
                },
                'cases.owner'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'cases.bookings'=>function($q) {
                    $q->andWhere(['status'=>'won']);
                },
                'cases.bookings.product'=>function($q) {
                    $q->select(['id', 'day_from', 'day_count', 'pax', 'op_name', 'op_code', 'op_finish', 'client_ref'])->andWhere(['op_status'=>'op']);
                },
                'metas'=>function($q){
                    return $q->select(['rid', 'name', 'value', 'note', 'format'])->orderBy('format, name');
                },
                'contacts'=>function($q){
                    return $q->select(['id', 'name', 'gender', 'byear', 'email', 'country_code', 'image'])->orderBy('fname, lname');
                },
                ])
            ->asArray()
            ->one();
        if (!$theClient) {
            throw new HttpException(404, 'Account not found');
        }
        
        return $this->render('client_r', [
            'theClient'=>$theClient,
            'view'=>$view,
        ]);
    }

    /**
     * Edit a client
     */
    public function actionU($id)
    {
        $theClient = Client::find()
            ->where(['id'=>$id])
            ->with([
                'metas'=>function($q){
                    return $q->select(['id', 'rid', 'name', 'value', 'note', 'format']);
                },
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->one();

        if (!$theClient) {
            throw new HttpException(404, Yii::t('x', 'Data not found.'));
        }

        // Nhung, Doan Ha, Ta Ha
        // Thuy Hang; then removed
        if (!in_array(USER_ID, [1, 1087, 11724, 15860, $theClient->created_by, $theClient->owner_id])) {
            throw new HttpException(404, Yii::t('x', 'Access denied.'));
        }

        $data = [
            'tel'=>[],
            'email'=>[],
            'url'=>[],
            'addr'=>[],
            'info'=>[],
        ];

        $infoMetaNames = ['info_type_of_cooperation', 'info_client_service', 'info_tour_operation',
                'info_payment_conditions', 'info_bank_accounts', 'info_urgent_contact', 'info_debt'];

        if (!Yii::$app->request->isPost) {
            // Load from DB
            foreach ($theClient['metas'] as $meta) {
                if (in_array($meta['format'], ['tel'])) {
                    $data['tel'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                        'full'=>$meta['value'],
                    ];
                } elseif (in_array($meta['format'], ['email'])) {
                    $data['email'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                    ];
                } elseif (in_array($meta['format'], ['url'])) {
                    $data['url'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                    ];
                } elseif (in_array($meta['format'], ['address'])) {
                    $addr = explode("\n", $meta['value']);
                    $data['addr'][] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>$meta['note'],
                        'addr_line_1'=>$addr[0] ?? '',
                        'addr_line_2'=>$addr[1] ?? '',
                        'addr_city'=>$addr[2] ?? '',
                        'addr_state'=>$addr[3] ?? '',
                        'addr_postal'=>$addr[4] ?? '',
                        'addr_country'=>$addr[5] ?? '',
                    ];
                } elseif (in_array($meta['name'], $infoMetaNames)) {
                    $data['info'][$meta['name']] = [
                        'name'=>$meta['name'],
                        'value'=>$meta['value'],
                        'note'=>'',
                        'format'=>'text',
                    ];
                    $theClient->{$meta['name']} = $meta['value'];
                }
            }
        } else {
            // Load from POST
            if (!isset($_POST['name'])) {
                $_POST['name'] = [];
            }
            $cntAddr = 0;
            $cntTel = 0;
            foreach ($_POST['name'] as $i=>$name) {
                if (in_array($name, ['tel', 'fax'])) {
                    $data['tel'][] = [
                        'name'=>$name,
                        'value'=>$_POST['full'][$cntTel] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'tel',
                        'full'=>$_POST['full'][$cntTel] ?? '',
                    ];
                    $cntTel ++;
                } elseif (in_array($name, ['email'])) {
                    $data['email'][] = [
                        'name'=>$name,
                        'value'=>$_POST['value'][$i] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'email',
                    ];
                } elseif (in_array($name, ['facebook', 'linkedin', 'website', 'url', 'link'])) {
                    $data['url'][] = [
                        'name'=>$name,
                        'value'=>$_POST['value'][$i] ?? '',
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'url',
                    ];
                } elseif (in_array($name, ['address'])) {
                    $addr_line_1 = $_POST['addr_line_1'][$cntAddr] ?? '';
                    $addr_line_2 = $_POST['addr_line_2'][$cntAddr] ?? '';
                    $addr_city = $_POST['addr_city'][$cntAddr] ?? '';
                    $addr_state = $_POST['addr_state'][$cntAddr] ?? '';
                    $addr_postal = $_POST['addr_postal'][$cntAddr] ?? '';
                    $addr_country = $_POST['addr_country'][$cntAddr] ?? '';

                    $value = implode("\n", [$addr_line_1, $addr_line_2, $addr_city, $addr_state, $addr_postal, $addr_country]);

                    $data['addr'][] = [
                        'name'=>$name,
                        'value'=>$value,
                        'note'=>$_POST['note'][$i] ?? '',
                        'format'=>'address',
                        'addr_line_1'=>$addr_line_1,
                        'addr_line_2'=>$addr_line_2,
                        'addr_city'=>$addr_city,
                        'addr_state'=>$addr_state,
                        'addr_postal'=>$addr_postal,
                        'addr_country'=>$addr_country,
                    ];
                    $cntAddr ++;
                }
            }
        }


        if ($theClient->load(Yii::$app->request->post()) && $theClient->validate()) {
            foreach ($infoMetaNames as $metaName) {
                $data['info'][$metaName] = $theClient->$metaName;
                $data['info'][$metaName] = [
                    'name'=>$metaName,
                    'value'=>$theClient->$metaName,
                    'note'=>'',
                    'format'=>'text',
                ];
            }

            // \fCore::expose($theClient);
            // \fCore::expose($data);
            // exit;

            $theClient->updated_dt = NOW;
            $theClient->updated_by = USER_ID;

            // Logo
            if (isset($_POST['slim']) && is_array($_POST['slim'])) {
                $slim = json_decode($_POST['slim'][0], true);
                // Move file
                if (isset($slim['path']) && $slim['path'] != '') {
                    $uploadDir = 'upload/companies/'.substr($theClient->created_dt, 0, 7);
                    if (!is_dir(Yii::getAlias('@webroot').'/'.$uploadDir)) {
                        mkdir(Yii::getAlias('@webroot').'/'.$uploadDir);
                    }
                    $oldAvatar = $slim['path'];
                    $newAvatar = str_replace('assets/slim_1.1.1/server/tmp/', $uploadDir.'/', $slim['path']);
                    rename(Yii::getAlias('@webroot').$oldAvatar, Yii::getAlias('@webroot').$newAvatar);
                    $theClient->image = Yii::getAlias('@web').$newAvatar;
                }
            }

            $theClient->save(false);
            // Save meta
            $cnt = 0;
            $max = count($theClient['metas']);
            // \fCore::expose($data); exit;
            foreach ($data as $type=>$group) {
                foreach ($group as $item) {
                    if (isset($theClient['metas'][$cnt])) {
                        Yii::$app->db->createCommand()->update('metas', [
                            'name'=>$item['name'],
                            'value'=>$item['value'],
                            'note'=>$item['note'],
                            'format'=>$item['format'],
                        ], [
                            'id'=>$theClient['metas'][$cnt]['id'],
                        ])->execute();
                    } else {
                        Yii::$app->db->createCommand()->insert('metas', [
                            'created_dt'=>NOW,
                            'created_by'=>USER_ID,
                            'updated_dt'=>NOW,
                            'updated_by'=>USER_ID,
                            'rtype'=>'client',
                            'rid'=>$theClient->id,
                            'name'=>$item['name'],
                            'value'=>$item['value'],
                            'note'=>$item['note'],
                            'format'=>$item['format'],
                        ])->execute();
                    }
                    $cnt ++;
                }
            }
            if ($cnt <= $max) {
                for ($i = $cnt; $i < $max; $i ++) {
                    // Delete item
                    Yii::$app->db->createCommand()->delete('metas', [
                        'id'=>$theClient['metas'][$i]['id'],
                    ])->execute();
                }
            }
            return $this->redirect('/b2b/clients/r/'.$theClient['id']);
        }

        $ownerList = User::find()
            ->where(['status'=>'on'])
            ->select(['id', 'name'=>'nickname'])
            ->asArray()
            ->all();

        $countryList = Country::find()
            ->where(['status'=>'on'])
            ->select(['code', 'name'=>'name_en'])
            ->orderBy('name')
            ->asArray()
            ->all();

        // \fCore::expose($countryList); exit;

        return $this->render('client_u', [
            'theClient'=>$theClient,
            'ownerList'=>$ownerList,
            'countryList'=>$countryList,
            'data'=>$data,
        ]);
    }


    // Login cho client SI
    public function actionLogin($id = 0)
    {
        $theClient = Client::find()
            ->where(['id'=>$id])
            ->one();
        if (!$theClient) {
            throw new HttpException(404, 'Account not found');
        }

        if ($theClient->load(Yii::$app->request->post()) && $theClient->validate()) {
            $theClient->updated_dt = NOW;
            $theClient->updated_by = USER_ID;

            if ($theClient->newpassword != '') {
                $theClient->password = Yii::$app->security->generatePasswordHash($theClient->newpassword);
            }
            $theClient->save(false);
            return $this->redirect('@web/b2b/clients');
        }

        return $this->render('client_login', [
            'theClient'=>$theClient,
        ]);
    }

}