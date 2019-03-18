<?php
namespace app\controllers;

use app\models\Attachment;
use app\models\Company;
use app\models\User;
use app\models\Search;
use app\models\Venue;
use app\models\SampleDay;
use app\models\SampleSegment;
use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use yii\web\Response;

class SampleDaysController extends MyController
{
    // 161116 Mai Phuong
    // 161119 Ngoc Anh
    // 161121 Fleur
    // 190302 Added JD, removed Fleur
    public $allowList = [1, 3, 28722, 17401, 1677, 12952, 51011];

    /**
     * Handle ajax requests
     */
    public function actionAjax($action = '', $query = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($action == 'searchday') {
            $search = trim($query);
            if ($search == '') {
                return [];
            }
            $days = SampleDay::find()
                ->select(['id', 'title', 'meals', 'owner', 'tags'])
                ->where(['stype'=>'day'])
                ->where(['or', is_numeric($search) ? ['id'=>$search] : 0, ['like', 'title', $search], ['like', 'tags', $search]])
                ->orderBy('title')
                ->limit(50)
                ->asArray()
                ->all();
            $result = [];
            foreach ($days as $day) {
                $result[] = [
                    'value'=>$day['title'].' ('.$day['meals'].') '.$day['tags'],
                    'name'=>$day['title'],
                    'meals'=>$day['meals'],
                    'id'=>$day['id'],
                ];
            }
            return ['suggestions'=>$result];
        }

        if ($action == 'addday') {
            $dayid = $_POST['dayid'] ?? 0;
            $day = SampleDay::findOne($dayid);
            if ($day) {
                // If not linked, link it
            }
        }

        if ($action == 'delday') {
            $dayid = $_POST['dayid'] ?? 0;
            $segmentid = $_POST['segmentid'] ?? 0;
            $sql = 'DELETE FROM sample_tour_day_segment WHERE day_id=:d AND segment_id=:s';
            Yii::$app->db->createCommand($sql, [':d'=>$dayid, ':s'=>$segmentid])->execute();
            return [
                'status'=>'ok'
            ];
        }

        throw new HttpException(401);
    }

    /**
     * Sample tour days
     */
    public function actionIndex($action = '', $type = '', $to = 0, $at = 0, $orderby = 'updated', $name = '', $tags = '', $show = 'b2c', $language = 'fr', $updatedby = 0)
    {
        if (Yii::$app->request->isAjax && isset($_POST['action'], $_POST['day'])) {
            if ($_POST['action'] == 'nouse') {
                $nm = SampleDay::findOne($_POST['day']);
                if (!$nm) {
                    throw new HttpException(404, 'Sample day not found');
                }
                if (strpos($nm->tags, 'nouse') === false) {
                    $nm->tags .= ', nouse';
                    $nm->save(false);
                }
            }
            return true;
        }

        $query = SampleDay::find()
            ->select(['*', 'updated_dt'=>new \yii\db\Expression('IF(updated_dt IS NULL, created_dt, updated_dt)')]);

        if ($show == 'b2b') {
            $query->andWhere(['owner'=>'si']);
        } else {
            $query->andWhere(['owner'=>'at']);
        }

        if ($updatedby != 0) {
            $query->andWhere(['updated_by'=>$updatedby]);
        }

        if (strpos($tags, 'nouse') === false) {
            $query->andWhere('LOCATE("nouse", tags)=0');
        }

        if ($show == '2015') {
            $query->andWhere('LOCATE("2015", tags)!=0');
        }

        if (strlen($name) > 1) {
            $query->andWhere(['like', 'title', $name]);
        }
        if (strlen($tags) > 1) {
            $tagArray = explode(',', $tags);
            $cnt = 0;
            foreach ($tagArray as $tag) {
                $cnt ++;
                $tagStr = trim($tag);
                if ($tagStr != '') {
                    $query->andWhere('LOCATE(:tag'.$cnt.', tags)!=0', [':tag'.$cnt=>$tagStr]);
                }
            }
        }
        if (strlen($language) > 1) {
            $query->andWhere(['language'=>$language]);
        }

        if ($type == '1') {
            $query->andWhere(['stype'=>'day']);
        } elseif ($type == '2') {
            $query->andWhere(['stype'=>'segment']);
        } elseif ($type == '5') {
            $query->andWhere(['stype'=>'day', 'is_halfday'=>'yes']);
        } elseif ($type == 'ns') {
            $query->andWhere(['is_selectable'=>'no']);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            'route'=>'/'.URI,
        ]);

        $theDays = $query
            ->orderBy($orderby == 'updated' ? 'updated_dt DESC' : 'title')
            ->with([
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name']);
                }
                ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        if ($show == 'b2b') {
            $updatedByList = Yii::$app->db->createCommand('SELECT u.id, u.nickname AS name FROM users u, at_ngaymau nm WHERE owner="si" AND nm.updated_by=u.id GROUP BY u.id ORDER BY lname')->queryAll();
        } else {
            $updatedByList = Yii::$app->db->createCommand('SELECT u.id, u.nickname AS name FROM users u, at_ngaymau nm WHERE owner="at" AND (nm.updated_by=u.id OR nm.created_by=u.id) GROUP BY u.id ORDER BY lname')->queryAll();
        }

        return $this->render('sample-days_index', [
            'pagination'=>$pagination,
            'theDays'=>$theDays,
            'language'=>$language,
            'type'=>$type,
            'name'=>$name,
            'tags'=>$tags,
            'show'=>$show,
            'orderby'=>$orderby,
            'updatedby'=>$updatedby,
            'updatedByList'=>$updatedByList,
        ]);
    }

    public function actionC($id = 0, $segment = 'no') {
        // if (!in_array(USER_ID, $this->allowList)) { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
        //     return $this->redirect('/sample-days');
        // }

        if ($segment == 'yes') {
            return $this->createSegment();
        }

        $theDay = new SampleDay;
        $theDay->scenario = 'sample-days/c';
        $theDay->language = 'fr';
        $theDay->stype = 'day';
        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {
            $theDay->owner = 'at';
            $theDay->created_dt = NOW;
            $theDay->created_by = USER_ID;

            if (empty($_POST['SampleDay']['is_selectable'])) {
                $theDay->is_selectable = 'yes';
            } else {
                $theDay->is_selectable = 'no';
            }

            if (empty($_POST['SampleDay']['is_halfday'])) {
                $theDay->is_halfday = 'no';
            } else {
                $theDay->is_halfday = 'yes';
            }

            $theDay->save(false);

            // Add attachments
            // post_attactments [name1, name2, ..., nameN] Names only
            if (isset($_POST['post_uid']) && !empty($_POST['post_attachments'])) {

                $attachmentPath = Yii::getAlias('@runtime').'/user-uploads/'.$_POST['post_uid'];
                $_POST['post_attachments'] = json_decode($_POST['post_attachments'], true);
                foreach ($_POST['post_attachments'] as $fileName) {
                    $fileExt = strrchr($fileName, '.');
                    $sourceFilePath = $attachmentPath.'/'.$fileName;
                    if (file_exists($sourceFilePath)) {
                        $fileUid = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($sourceFilePath);
                        $imgSize = @getimagesize($sourceFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0].'×'.$imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }

                        // Save to DB
                        Yii::$app->db->createCommand()
                            ->insert('attachments', [
                                'created_dt'=>NOW,
                                'created_by'=>USER_ID,
                                'name'=>$fileName,
                                'ext'=>$fileExt,
                                'size'=>$fileSize,
                                'img_size'=>$fileImgSize,
                                'uid'=>$fileUid,
                                'filegroup_id'=>1,
                                'rtype'=>'sample-day',
                                'rid'=>$theDay->id,
                                'n_id'=>0,
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $destPath = Yii::getAlias('@webroot').'/upload/user-files/'.substr(NOW, 0, 7);
                        if (!file_exists($destPath)) {
                            \yii\helpers\FileHelper::createDirectory($destPath);
                        }
                        // New name
                        $destName = 'file-'.USER_ID.'-'.$newFileId.'-'.$fileUid;
                        $destFilePath = $destPath.'/'.$destName;

                        // Move upload file to new (official) location
                        if (!rename($sourceFilePath, $destFilePath)) {
                            Yii::$app->db->createCommand()
                                ->delete('attachments', [
                                    'id'=>$newFileId,
                                ])
                                ->execute();
                        }
                    }
                }
                // Remove source path
                \yii\helpers\FileHelper::removeDirectory($attachmentPath);
            }

            Yii::$app->session->setFlash('success', Yii::t('x', 'Sample day has been created.'));
            return $this->redirect('/sample-days');
        }

        return $this->render('sample-days_u', [
            'theDay'=>$theDay,
        ]);
    }

    public function actionR($id = 0)
    {
        $theDay = SampleDay::find()
            ->where(['id'=>$id])
            ->with([
                'segments',
                'segments.days',
                'attachments',
                'createdBy'=>function($q) {
                    return $q->select(['id', 'name']);
                },
                'updatedBy'=>function($q) {
                    return $q->select(['id', 'name'=>'nickname']);
                }
            ])
            ->asArray()
            ->one();

        $parentId = $theDay['id'];

        $theSegment = $theDay['stype'] == 'day' ? false : SampleSegment::find()
            ->where(['stype'=>'segment', 'id'=>$id])
            ->with([
                'days'=>function($q){
                    return $q->select(['id', 'meals', 'title', 'body']);
                },
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
            ])
            ->asArray()
            ->one();

        return $this->render('sample-days_r', [
            'theDay'=>$theDay,
            'theSegment'=>$theSegment,
        ]);
    }

    public function actionU($id = 0)
    {
        $theDay = SampleDay::find()
            ->where(['id'=>$id])
            ->with(['attachments'])
            ->one();
        if (!$theDay) {
            throw new HttpException(404, 'Sample day not found.');
        }

        if (!in_array(USER_ID, $this->allowList) || $theDay->owner == 'si') { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            // return $this->redirect('/sample-days/'.$id);
        }

        if ($theDay['stype'] == 'segment') {
            return $this->editSegment($id);
        }

        $theDay->scenario = 'sample-days/u';
        if ($theDay->load(Yii::$app->request->post()) && $theDay->validate()) {

            if (empty($_POST['SampleDay']['is_selectable'])) {
                $theDay->is_selectable = 'yes';
            } else {
                $theDay->is_selectable = 'no';
            }

            if (empty($_POST['SampleDay']['is_halfday'])) {
                $theDay->is_halfday = 'no';
            } else {
                $theDay->is_halfday = 'yes';
            }

            if (USER_ID != 1) { // TEMP
            $theDay->updated_dt = NOW;
            $theDay->updated_by = USER_ID;
            }

            $theDay->save(false);

            // Remove attachments
            // post_remove_attactments [1, 2, ..., N] IDs only
            if (!empty($_POST['post_remove_attachments'])) {
                $_POST['post_remove_attachments'] = json_decode($_POST['post_remove_attachments'], true);
                $removeAttachments = Attachment::find()
                    ->where(['id'=>$_POST['post_remove_attachments'], 'rid'=>$theDay->id, 'rtype'=>'sample-day'])
                    ->asArray()
                    ->all();
                foreach ($removeAttachments as $removeAttachment) {
                    // Delete from server
                    $removeAttachmentPath = Yii::getAlias('@webroot').'/upload/user-files/'.substr($removeAttachment['created_dt'], 0, 7).'/file-'.$removeAttachment['created_by'].'-'.$removeAttachment['id'].'-'.$removeAttachment['uid'];
                    @\yii\helpers\FileHelper::unlink($removeAttachmentPath);
                    // Delete from DB
                    $sql = 'DELETE FROM attachments WHERE id=:id LIMIT 1';
                    Yii::$app->db->createCommand($sql, [':id'=>$removeAttachment['id']])->execute();
                }
            }
            // Add attachments
            // post_attactments [name1, name2, ..., nameN] Names only
            if (isset($_POST['post_uid']) && !empty($_POST['post_attachments'])) {

                $attachmentPath = Yii::getAlias('@runtime').'/user-uploads/'.$_POST['post_uid'];
                $_POST['post_attachments'] = json_decode($_POST['post_attachments'], true);
                foreach ($_POST['post_attachments'] as $fileName) {
                    $fileExt = strrchr($fileName, '.');
                    $sourceFilePath = $attachmentPath.'/'.$fileName;
                    if (file_exists($sourceFilePath)) {
                        $fileUid = Yii::$app->security->generateRandomString(10);
                        $fileSize = filesize($sourceFilePath);
                        $imgSize = @getimagesize($sourceFilePath);
                        if ($imgSize) {
                            $fileImgSize = $imgSize[0].'×'.$imgSize[1];
                        } else {
                            $fileImgSize = '';
                        }

                        // Save to DB
                        Yii::$app->db->createCommand()
                            ->insert('attachments', [
                                'created_dt'=>NOW,
                                'created_by'=>USER_ID,
                                'name'=>$fileName,
                                'ext'=>$fileExt,
                                'size'=>$fileSize,
                                'img_size'=>$fileImgSize,
                                'uid'=>$fileUid,
                                'filegroup_id'=>1,
                                'rtype'=>'sample-day',
                                'rid'=>$theDay->id,
                                'n_id'=>0,
                            ])
                            ->execute();
                        $newFileId = Yii::$app->db->getLastInsertID();
                        // New dir
                        $destPath = Yii::getAlias('@webroot').'/upload/user-files/'.substr(NOW, 0, 7);
                        if (!file_exists($destPath)) {
                            \yii\helpers\FileHelper::createDirectory($destPath);
                        }
                        // New name
                        $destName = 'file-'.USER_ID.'-'.$newFileId.'-'.$fileUid;
                        $destFilePath = $destPath.'/'.$destName;

                        // Move upload file to new (official) location
                        if (!rename($sourceFilePath, $destFilePath)) {
                            Yii::$app->db->createCommand()
                                ->delete('attachments', [
                                    'id'=>$newFileId,
                                ])
                                ->execute();
                        }
                    }
                }
                // Remove source path
                \yii\helpers\FileHelper::removeDirectory($attachmentPath);
            }


            Yii::$app->session->setFlash('success', 'Sample day has been updated.');
            return $this->redirect('/sample-days/'.$theDay->id);
        }

        return $this->render('sample-days_u', [
            'theDay'=>$theDay,
        ]);
    }

    /**
     * Delete sample tour day
     * @todo When deleting a in-segment day, ask for thet day to be removed first
     */
    public function actionD($id = 0) {
        $theDay = SampleDay::findOne($id);
        if (!$theDay) {
            throw new HttpException(404, 'Sample day not found.');
        }

        if (!in_array(USER_ID, $this->allowList) || $theDay->owner == 'si') { // Hieu, Nguyen, Mathieu, 161116 Phuong NM
            return $this->redirect('/sample-days/'.$id);
        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('confirm') == 'delete') {
            if ($theDay->stype == 'segment') {
                $sql = 'DELETE FROM sample_tour_day_segment WHERE segment_id=:s';
                Yii::$app->db->createCommand($sql, [':s'=>$theDay->id])->execute();
            }
            $theDay->delete();
            Yii::$app->session->setFlash('success', 'Sample day has been deleted.');
            return $this->redirect('/sample-days');
        }

        return $this->render('sample-days_d', [
            'theDay'=>$theDay,
        ]);
    }

    /**
     * Create tour segment
     */
    private function createSegment()
    {
        $theSegment = new SampleSegment;
        $theSegment->language = 'fr';
        $theSegment->created_dt = NOW;
        $theSegment->created_by = USER_ID;
        $theSegment->stype = 'segment';

        $theSegment->scenario = 'sample-segments/c';
        if ($theSegment->load(Yii::$app->request->post()) && $theSegment->validate()) {
            $theSegment->updated_dt = NOW;
            $theSegment->updated_by = USER_ID;
            $theSegment->save(false);

            foreach ($_POST['day_id'] ?? [] as $cnt=>$dayid) {
                $sql = 'INSERT INTO sample_tour_day_segment (day_order, day_id, segment_id) VALUES (:o, :d, :s)';
                Yii::$app->db
                    ->createCommand($sql, [':o'=>$cnt, ':d'=>$dayid, ':s'=>$theSegment['id']])
                    ->execute();
            }

            Yii::$app->session->setFlash('success', 'Sample segment has been added.');
            return $this->redirect('/sample-days/'.$theSegment->id);
        }

        return $this->render('sample-segments_u', [
            'theDay'=>$theSegment,
            'theSegment'=>$theSegment,
        ]);
    }

    /**
     * Edit tour segment
     */
    private function editSegment($id)
    {
        $theSegment = SampleSegment::find()
            ->where(['id'=>$id, 'stype'=>'segment'])
            ->one();
        if (!$theSegment) {
            throw new HttpException(404, 'Sample segment not found.');
        }

        if (!in_array(USER_ID, $this->allowList)) {
            return $this->redirect('/sample-days');
        }

        $theSegment->scenario = 'sample-segments/u';
        if ($theSegment->load(Yii::$app->request->post()) && $theSegment->validate()) {
            $theSegment->updated_dt = NOW;
            $theSegment->updated_by = USER_ID;
            $theSegment->save(false);

            $sql = 'DELETE FROM sample_tour_day_segment WHERE segment_id=:s';
            Yii::$app->db
                ->createCommand($sql, [':s'=>$theSegment['id']])
                ->execute();
            foreach ($_POST['day_id'] ?? [] as $cnt=>$dayid) {
                $sql = 'INSERT INTO sample_tour_day_segment (day_order, day_id, segment_id) VALUES (:o, :d, :s)';
                Yii::$app->db
                    ->createCommand($sql, [':o'=>$cnt, ':d'=>$dayid, ':s'=>$theSegment['id']])
                    ->execute();
            }

            Yii::$app->session->setFlash('success', 'Sample segment has been updated.');
            return $this->redirect('/sample-days');
        }

        return $this->render('sample-segments_u', [
            'theDay'=>$theSegment,
            'theSegment'=>$theSegment,
        ]);
    }
}
