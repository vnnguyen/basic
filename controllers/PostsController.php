<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;
use yii\data\Pagination;
use common\models\Message as Post;
use common\models\User;
use common\models\Kase;
use common\models\Tour;
use common\models\Product;
use common\models\Venue;
use common\models\Attachment;
use Yii\helpers\ArrayHelper;

class PostsController extends MyController
{
    /**
     * Handles ajax postback
     */
    public function actionAjax($action = '')
    {
        if (!Yii::$app->request->isAjax) {
            die(0);
        }
        // Response in JSON format
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($action == 'upload' && isset($_POST['uid']) && !empty($_FILES["file"])) {
            $names = [];
            foreach ($_FILES["file"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["file"]["tmp_name"][$key];
                    // basename() may prevent filesystem traversal attacks;
                    // further validation/sanitation of the filename may be appropriate
                    $name = basename($_FILES["file"]["name"][$key]);
                    $uploadPath = Yii::getAlias('@runtime').'/user-uploads/'.$_POST['uid'];
                    if (!file_exists($uploadPath)) {
                        \yii\helpers\FileHelper::createDirectory($uploadPath);
                    }
                    if (move_uploaded_file($tmp_name, $uploadPath.'/'.$name)) {
                        $names[] = $name;
                    }
                }
            }
            return [
                'code'=>200,
                'status'=>'ok',
                'message'=>'Files uploaded: '.implode(', ', $names),
            ];
        }

        $post_id = $_POST['post_id'] ?? 0;
        // if (!empty($_POST['post_id']) && $_POST['post_id'] != 0) {
        //     $thePost = Post::find()
        //         ->asArray()
        //         ->one();
        // }

        // Load post for edit form
        if ($action == 'load-post') {
        }

        // Save post
        if ($action == 'save-post') {
            /*
            SCENARIOS
            - New post
                - First post in a thread
                - Reply to a previos post
            - Existing post

            WHAT TO DO
            - Save attachments
            - Save post
            - Notify people (:to and @mention)
            */

            // New post
            if ($post_id == 0) {
                $thePost = new Post;
                $thePost->co = NOW;
                $thePost->cb = USER_ID;
                $thePost->status = 'on';
                $thePost->via = 'web';
                $thePost->priority = 'A1'; // TODO redundant
                $thePost->from_id = USER_ID;
                $thePost->rtype = $_POST['post_rtype'];
                $thePost->rid = $_POST['post_rid'];
                $thePost->n_id = $_POST['post_thread_id'];
            } else {
                $thePost = Post::find()
                    ->where(['id'=>$post_id])
                    ->one();
                if (!$thePost) {
                    throw new HttpException(404, 'Post not found');
                }
                if (!in_array(USER_ID, [1,34718, $thePost->cb, $thePost->ub])) {
                    throw new HttpException(404, 'Access denied');
                }
            }

            // Existing post
            $thePost->uo = NOW;
            $thePost->ub = USER_ID;
            $thePost->title = $_POST['post_title'] ?? '';
            $thePost->body = $_POST['post_body'] ?? '';

            // Only if post is not a reply
            $utag = false;
            $itag = false;
            $iuTags = 'A1';
            if ($thePost->n_id == 0) {
                if (strpos($thePost->title, '#urgent') !== false) {
                    $utag = true;
                    $thePost->title = str_replace('#urgent', '', $thePost->title);
                    $iuTags = str_replace('1', '3', $iuTags);
                }
                if (strpos($thePost->title, '#important') !== false) {
                    $itag = true;
                    $thePost->title = str_replace('#important', '', $thePost->title);
                    $iuTags = str_replace('A', 'C', $iuTags);
                }
            }

            $thePost->priority = $iuTags;
            $thePost->title = trim($thePost->title);

            if ($thePost->save(false)) {

                // Remove attachments
                // post_remove_attactments [1, 2, ..., N] IDs only
                if (!empty($_POST['post_remove_attachments'])) {
                    $removeAttachments = Attachment::find()
                        ->where(['id'=>$_POST['post_remove_attachments'], 'n_id'=>$thePost->id])
                        ->asArray()
                        ->all();
                    foreach ($removeAttachments as $removeAttachment) {
                        // Delete from server
                        $removeAttachmentPath = Yii::getAlias('@webroot').'/upload/user-files/'.substr($removeAttachment['co'], 0, 7).'/file-'.$removeAttachment['cb'].'-'.$removeAttachment['id'].'-'.$removeAttachment['uid'];
                        @\yii\helpers\FileHelper::unlink($removeAttachmentPath);
                        // Delete from DB
                        $sql = 'DELETE FROM at_files WHERE id=:id LIMIT 1';
                        Yii::$app->db->createCommand($sql, [':id'=>$removeAttachment['id']])->execute();
                    }
                }
                // Add attachments
                // post_attactments [name1, name2, ..., nameN] Names only
                if (isset($_POST['post_uid']) && !empty($_POST['post_attachments'])) {

                    $attachmentPath = Yii::getAlias('@runtime').'/user-uploads/'.$_POST['post_uid'];
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
                                ->insert('at_files', [
                                    'co'=>NOW,
                                    'cb'=>USER_ID,
                                    'uo'=>NOW,
                                    'ub'=>USER_ID,
                                    'name'=>$fileName,
                                    'ext'=>$fileExt,
                                    'size'=>$fileSize,
                                    'img_size'=>$fileImgSize,
                                    'uid'=>$fileUid,
                                    'filegroup_id'=>1,
                                    'rtype'=>$thePost['rtype'],
                                    'rid'=>$thePost['rid'],
                                    'n_id'=>$thePost['id'],
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
                                    ->delete('at_files', [
                                        'id'=>$newFileId,
                                    ])
                                    ->execute();
                            }
                        }
                    }
                    // Remove source path
                    \yii\helpers\FileHelper::removeDirectory($attachmentPath);
                }

                // Direct to
                if ($thePost->n_id == 0 && $post_id != 0) {
                    // Delete OLD tos
                    Yii::$app->db->createCommand()->delete('at_message_to', [
                        'message_id'=>$thePost->id
                    ])->execute();
                }
                $replyToIdList = $_POST['post_replyto'] ?? [];
                if (!empty($replyToIdList)) {
                    if ($thePost->n_id == 0) {
                        $nTo = [];
                        foreach ($replyToIdList as $to) {
                            $nTo[] = [$thePost->id, $to];
                        }
                        Yii::$app->db->createCommand()->batchInsert('at_message_to', ['message_id', 'user_id'], $nTo)->execute();
                    }
                }
                // TODO: check validity of list

                // Mentions
                $mentionIdList = [];
                $pos = -1;
                do {
                    // <a href="/mention/ID?mention=user">@NAME</a>
                    $post_body = $thePost->body;
                    $pos = strpos($post_body, '<a href="/mentions/', $pos + 1);
                    if ($pos !== false) {
                        $post_body = substr($post_body, $pos + 19);
                        $pos2 = strpos($post_body, '?mention=user">@');
                        if ($pos2 !== false) {
                            $mentionId = substr($post_body, 0, $pos2);
                            if (is_numeric($mentionId)) {
                                $mentionIdList[] = $mentionId;
                            }
                        }
                    }
                } while ($pos !== false);

                $sql = 'DELETE FROM mentions WHERE post_id=:pi';
                Yii::$app->db->createCommand($sql, [':pi'=>$thePost['id']])->execute();
                foreach ($mentionIdList as $mentionedId) {
                    $sql = 'INSERT INTO mentions (post_id, user_id) VALUES (:pi, :ui)';
                    Yii::$app->db->createCommand($sql, [':pi'=>$thePost['id'], ':ui'=>$mentionId])->execute();
                }

                // Send notification email
                $postUrl = 'https://my.amicatravel.com/posts/'.$thePost->id;
                $relUrl = 'https://my.amicatravel.com/'.$thePost->rtype.'s/r/'.$thePost->rid;
                if ($thePost->rtype == 'company') {
                    $relUrl = 'https://my.amicatravel.com/b2b/clients/r/'.$thePost->rid;
                }

                $mentionIdList = array_merge($replyToIdList, $mentionIdList);
                // return (\fCore::expose($mentionIdList)); exit;

                if (!empty($mentionIdList)) {
                    $mentionedUsers = User::find()
                        ->select(['name', 'email'])
                        ->where(['status'=>'on', 'id'=>$mentionIdList])
                        ->asArray()
                        ->all();
                    if (!empty($mentionedUsers)) {
                        // Subject for email
                        if ($thePost['n_id'] != 0) {
                            $theTopPost = Post::find()
                                ->where(['n_id'=>0, 'id'=>$thePost['n_id']])
                                ->asArray()
                                ->one();
                            if (!$theTopPost) {
                                throw new HttpException(404, 'Top post not found');
                            }
                            $mailSubject = Yii::t('x', 'Reply').': '.$theTopPost['title'];
                            if ($post_id != 0) {
                                $mailSubject = '['.Yii::t('x', 'edited').'] '.$mailSubject;
                            }
                        } else {
                            $mailSubject = $thePost['title'] == '' ? Yii::t('x', '(No title)') : $thePost['title'];
                            if ($itag) {
                                $mailSubject = '#important '.$mailSubject;
                            }
                            if ($utag) {
                                $mailSubject = '#urgent '.$mailSubject;
                            }
                            if ($post_id != 0) {
                                $mailSubject = '['.Yii::t('x', 'edited').'] '.$mailSubject;
                            }
                        }

                        // Subject tail
                        $mailSubjectTail = '';
                        if ($thePost['rtype'] == 'company') {
                            $theClient = \common\models\Client::find()
                                ->select(['id', 'name'])
                                ->where(['id'=>$thePost['rid']])
                                ->asArray()
                                ->one();
                            if ($theClient) {
                                $mailSubjectTail = $theClient['name'];
                            }
                        }

                        if ($thePost['rtype'] == 'venue') {
                            $theVenue = Venue::find()
                                ->select(['id', 'name'])
                                ->where(['id'=>$thePost['rid']])
                                ->asArray()
                                ->one();
                            if ($theVenue) {
                                $mailSubjectTail = $theVenue['name'];
                            }
                        }

                        if ($thePost['rtype'] == 'case') {
                            $theCase = Kase::find()
                                ->select(['id', 'name'])
                                ->where(['id'=>$thePost['rid']])
                                ->asArray()
                                ->one();
                            if ($theCase) {
                                $mailSubjectTail = $theCase['name'];
                            }

                            // Increase last date of B2B case
                            $sql = 'UPDATE at_cases SET last_accessed_dt=:now WHERE id=:id LIMIT 1';
                            Yii::$app->db->createCommand($sql, [':now'=>NOW, ':id'=>$theCase['id']])->execute();
                        }

                        if ($thePost['rtype'] == 'tour') {
                            $theTourOld = Tour::find()
                                ->select(['id', 'ct_id'])
                                ->where(['id'=>$thePost['rid']])
                                ->with([
                                    'product'=>function($q){
                                        return $q->select(['id', 'op_name', 'op_code', 'day_from', 'day_count']);
                                    },
                                    'product.bookings'=>function($q){
                                        return $q->select(['id', 'product_id', 'pax', 'created_by']);
                                    },
                                    'product.bookings.createdBy'=>function($q){
                                        return $q->select(['id', 'name']);
                                    },
                                    'operators'=>function($q){
                                        return $q->select(['id', 'name']);
                                    },
                                ])
                                ->asArray()
                                ->one();

                            if ($theTourOld) {

                                $mailSubject = $theTourOld['product']['op_code'].' - '.$mailSubject;

                                $paxCount = 0;
                                $tourStaff = [];
                                foreach ($theTourOld['product']['bookings'] as $booking) {
                                    $paxCount += $booking['pax'];
                                    $tourStaff[] = $booking['createdBy']['name'];
                                }
                                foreach ($theTourOld['operators'] as $user) {
                                    $tourStaff[] = $user['name'];
                                }
                                $mailSubjectTail = $theTourOld['product']['op_code'].' - '.$theTourOld['product']['op_name'].' - '.$paxCount.'p '.$theTourOld['product']['day_count'].'d '.date('j/n', strtotime($theTourOld['product']['day_from']));

                                if (!empty($tourStaff)) {
                                    $mailSubjectTail .= ' - '.implode(', ', $tourStaff);
                                }

                            }
                        }

                        if ($mailSubjectTail != '') {
                            $mailSubject .= ' | '.$mailSubjectTail;
                        }

                        // Attachment list for email
                        $mailAttachmentList = '';
                        $postAttachments = Attachment::find()
                            ->select(['id', 'name'])
                            ->where(['n_id'=>$thePost['id']])
                            ->limit(100)
                            ->asArray()
                            ->all();
                        if (!empty($postAttachments)) {
                            foreach ($postAttachments as $postAttachment) {
                                $mailAttachmentList .= '<div>+ <a href="/attachments/'.$postAttachment['id'].'">'.$postAttachment['name'].'</a></div>';
                            }
                        }

                        $replyTo = 'post-'.($thePost['n_id'] == 0 ? $thePost['id'] : $thePost['n_id']).'-'.random_int(1000, 9999).'@my.amicatravel.com';
                        $args = [
                            ['from', 'notifications@my.amicatravel.com', Yii::$app->user->identity->name, ' on IMS'],
                            ['reply-to', $replyTo],
                            ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                        ];
                        foreach ($mentionedUsers as $mentionedUser) {
                            $args[] = ['to', $mentionedUser['email'], $mentionedUser['name']];
                        }
                        // $this->mgItMy(
                        //     $mailSubject,
                        //     '//mg/post_mentioned',
                        //     [
                        //         'mailAttachmentList'=>$mailAttachmentList,
                        //         'thePost'=>$thePost,
                        //         'postUrl'=>$postUrl,
                        //         'relUrl'=>$relUrl,
                        //     ],
                        //     $args
                        // );
                    }
                }

                return [
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Post saved.',
                    'redir'=>$thePost->rid != 0 ? ($thePost->rtype == 'company' ? 'b2b/client' : $thePost->rtype).'s/r/'.$thePost->rid : 'posts/'.$thePost['id'],
                ];
            } // if post saved
        }

        // Delete post
        if ($action == 'delete-post') {
        }

        // Invalid action
        throw new HttpException(401);
    }

    public function actionIndex($from = 0, $to = 0, $via = 'all', $month = 'all', $title = '')
    {

        $fromList = [
            '0'=>'Anybody',
            Yii::$app->user->id=>'Me',
        ];
        $toList = [
            '0'=>'Anybody',
            Yii::$app->user->id=>'Me',
        ];
        if (in_array(Yii::$app->user->id, [1,2,3,4,118,695,4432,1351,7756,9881])) {
            $viewAll = true;
            if ($from != 0 && $from != Yii::$app->user->id) {
                $theFromUser = User::find()->where(['id'=>$from])->one();
                if (!$theFromUser) {
                    throw new HttpException(404, 'User not found');
                }
                $fromList[$from] = $theFromUser['name'];
            }
            if ($to != 0 && $to != Yii::$app->user->id) {
                $theToUser = User::find()->where(['id'=>$to])->one();
                if (!$theToUser) {
                    throw new HttpException(404, 'User not found');
                }
                $toList[$to] = $theToUser['name'];
            }
        } else {
            $viewAll = false;
        }

        $viaList = [
            'web'=>'IMS note',
            'email'=>'Email note',
            'form'=>'Web form inquiry',
            'ev'=>'Client space',
        ];

        $monthList = Yii::$app->db
            ->createCommand('SELECT SUBSTRING(uo, 1, 7) AS ym FROM at_messages GROUP BY ym ORDER BY ym DESC')
            ->queryAll();

        $query = Post::find();

        if (strlen($month) == 7) {
            $query->andWhere('SUBSTRING(at_messages.uo,1,7)=:ym', [':ym'=>$month]);
        }

        if (!$viewAll) {
            if ($from == 0 && $to == 0) {
                // Mac dinh note cua toi
                $from = Yii::$app->user->id;
            } else {
                if ($from == 0) {
                    $to = Yii::$app->user->id;
                } else {
                    $from = Yii::$app->user->id;
                }
            }
        }

        if ($from != 0) {
            $query->andWhere(['from_id'=>$from]);
        }

        if ($to != 0) {
            $query->innerJoinWith([
                'sto'=>function($query) {
                    $query->andWhere(['users.id'=>Yii::$app->request->get('to')]);
                    return $query;
                },
            ]);
        }

        if (strlen($title) >= 2) {
            $query->andWhere(['like', 'title', $title]);
        }

        if (in_array($via, ['web', 'email', 'form', 'ev'])) {
            $query->andWhere(['via'=>$via]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>25,
            ]);

        $thePosts = $query
            ->select('via, file_count, at_messages.id, at_messages.co, at_messages.cb, at_messages.uo, at_messages.ub, at_messages.title, at_messages.from_id, at_messages.rtype, at_messages.rid, at_messages.body')
            ->with([
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'from'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'to'=>function($q){
                    return $q->select(['id', 'name'=>'nickname']);
                },
                'files',
                'relatedCase',
                'relatedTour'
                ])
            ->orderBy('uo DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        return $this->render('posts_index', [
            'pagination'=>$pagination,
            'from'=>$from,
            'to'=>$to,
            'via'=>$via,
            'title'=>$title,
            'month'=>$month,
            'thePosts'=>$thePosts,
            'monthList'=>$monthList,
            'viaList'=>$viaList,
            'viewAll'=>$viewAll, // if user can view all
            'fromList'=>$fromList,
            'toList'=>$toList,
        ]);
    }

    public function actionC() {

    }

    public function actionR($id = 0)
    {
        $thePost = Post::find()
            ->where(['id'=>$id])
            ->with([
                'from'=>function($q){
                    return $q->select(['id', 'name', 'image']);
                },
                'to'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'files',
                'replies',
                'replies.from'=>function($q){
                    return $q->select(['id', 'name', 'image']);
                },
                'replies.files',
            ])
            ->asArray()
            ->one();

        if (!$thePost) {
            throw new HttpException(404, 'Post not found');
        }

        if ($thePost['n_id'] != 0) {
            return $this->redirect('@web/posts/'.$thePost['n_id']);
        }

        // People who will be notified when reply is posted
        $replyToIdList = [$thePost['from']['id']];
        foreach ($thePost['to'] as $to) {
            $replyToIdList[] = $to['id'];
        }
        foreach ($thePost['replies'] as $reply) {
            $replyToIdList[] = $reply['from']['id'];
        }
        $replyToIdList = array_unique($replyToIdList);

        // TODO Search for mentioned people/objects to do anything useful
        // if (preg_match_all('/@\[user\-(\d+)\]/', $thePost['body'], $matches)) {
        //     //\fCore::expose($matches[1]);
        //     //exit;
        //     $mentionedPeople = User::find()
        //         ->select(['id', 'name', 'email', 'image'])
        //         ->where(['id'=>$matches[1]])
        //         ->asArray()
        //         ->all();
        // } else {
        //     $mentionedPeople = [];
        // }

        // if ($theReply->load(Yii::$app->request->post()) && $theReply->validate()) {
        //     exit;
        //     // TODO SAVE POST
        //     $theReply->co = NOW;
        //     $theReply->cb = USER_ID;
        //     $theReply->uo = NOW;
        //     $theReply->ub = USER_ID;
        //     $theReply->status = 'on';
        //     $theReply->via = 'web';
        //     $theReply->priority = 'A1';
        //     $theReply->from_id = USER_ID;
        //     $theReply->m_to = 0;
        //     $theReply->title = 'Trả lời: '.$thePost['title'];
        //     $theReply->rtype = $thePost['rtype'];
        //     $theReply->rid = $thePost['rid'];
        //     $theReply->n_id = $thePost['id'];
        //     if (!$theReply->save(false)) {
        //         die('NOTE NOT SAVED');
        //     }
        //     // TODO email
        //     if (!empty($replyToList)) {
        //         $subject = $theReply['title'];

        //         $args = [
        //             ['from', 'notifications@amicatravel.com', Yii::$app->user->identity->nickname, ' on IMS'],
        //             ['reply-to', 'msg-'.$theReply->id.'-'.$theReply->cb.'@my.amicatravel.com'],
        //             ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
        //         ];
        //         foreach ($replyToList as $email) {
        //             if ($email != Yii::$app->user->identity->email) {
        //                 $args[] = ['to', $email];
        //             }
        //         }
        //         if ($thePost['rtype'] == 'company') {
        //             $rType = 'companies/r';
        //         } else {
        //             $rType = $thePost['rtype'].'s/r';
        //         }
        //         $this->mgItMy(
        //             $subject,
        //             '//mg/note_added',
        //             [
        //                 'toList'=>[],
        //                 'thePost'=>$theReply,
        //                 'relUrl'=>'https://my.amicatravel.com/'.$rType.'/'.$thePost['rid'],
        //                 'body'=>$theReply['body'],
        //             ],
        //             $args
        //         );
        //     }
        //     // Return
        //     return $this->redirect('@web/posts/'.$thePost['id']);
        // }

        return $this->render('posts_r', [
            'thePost'=>$thePost,
            'replyToIdList'=>$replyToIdList,
        ]);
    }

    /**
     * Edits a post
     */
    public function actionU($id = 0)
    {
        // Xem post la top post hay reply
        $thePost = Post::find()
            ->where(['id'=>$id])
            ->with([
                'files',
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name', 'image']);
                },
            ])
            ->one();
        if (!$thePost) {
            throw new HttpException(404, 'Post not found');
        }

        if ($thePost['n_id'] != 0) {
            $topPostId = $thePost['n_id'];
        } else {
            $topPostId = $thePost['id'];
        }

        $theTopPost = Post::find()
            ->where(['id'=>$topPostId])
            ->with([
                'from'=>function($q){
                    return $q->select(['id', 'name', 'image']);
                },
                'to'=>function($q){
                    return $q->select(['id', 'name']);
                },
                'files',
                'replies',
                'replies.from'=>function($q){
                    return $q->select(['id', 'name', 'image']);
                },
                'replies.files',
            ])
            ->asArray()
            ->one();

        if (!$theTopPost) {
            throw new HttpException(404, 'Top post not found');
        }

        // People who will be notified when reply is posted
        $replyToIdList = [$theTopPost['from']['id']];
        foreach ($theTopPost['to'] as $to) {
            $replyToIdList[] = $to['id'];
        }
        foreach ($theTopPost['replies'] as $reply) {
            $replyToIdList[] = $reply['from']['id'];
        }
        $replyToIdList = array_unique($replyToIdList);
        if (Yii::$app->request->isAjax) {
            $thePost = Post::find()
            ->where(['id'=>$id])
            ->with([
                'files',
                'updatedBy'=>function($q){
                    return $q->select(['id', 'name', 'image']);
                },
            ])
            ->asArray()
            ->one();
            $replyToIdList = array_diff($replyToIdList, [USER_ID]);

            return json_encode([
                'thePost'=>ArrayHelper::toArray($thePost),
                'replyToIdList'=>$replyToIdList,
            ]);
        } else {
            return $this->render('posts_u', [
                'thePost'=>$thePost,
                'theTopPost'=>$theTopPost,
                'replyToIdList'=>$replyToIdList,
            ]);
        }

    }

    /**
     * Deletes a post
     */
    public function actionD($id = 0)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }

        $thePost = Post::find()
            ->where(['id'=>$id])
            ->with([
                'files',
                'replies',
            ])
            ->one();
        if (!$thePost) {
            throw new HttpException(404, 'Post not found');
        }

        if (
            // Neu la Thu, Chinh, Nguyen & venue
            (!in_array(USER_ID, [9198, 8, 28722, 29739]) || $thePost['rtype'] != 'venue') &&
            (!in_array(USER_ID, [9198, 8, 28722, 29739]) || $thePost['rtype'] != 'company') &&
            // Khong co quyen
            !in_array(USER_ID, [1, $thePost['cb'], $thePost['ub']])
            ) {
            throw new HttpException(403, 'Access denied');
        }

        // Neu post la dau thread va co cau tra loi khac, thi khong xoa
        if ($thePost['n_id'] == 0) {
            $postReplyCount = Post::find()
                ->where(['n_id'=>$thePost['id']])
                ->count();
            if ($postReplyCount > 0) {
                throw new HttpException(403, 'This post has replies. You must delete the replies first.');
            }
        // Neu post la reply va co cau tra loi khac, thi khong xoa
        } else {
            $postReplyCount = Post::find()
                ->where(['n_id'=>$thePost['n_id']])
                ->andWhere('co>:co', [':co'=>$thePost['co']])
                ->count();
            if ($postReplyCount > 0) {
                throw new HttpException(403, 'This post has '.$postReplyCount.' replies. You must delete the replies first.');
            }
        }

        if (Yii::$app->request->isAjax || (isset($_POST['confirm']) && $_POST['confirm'] == 'delete')) {
            foreach ($thePost['files'] as $file) {
                $filePath = Yii::getAlias('@webroot').'/upload/user-files/'.substr($file['co'], 0, 7).'/file-'.$file['cb'].'-'.$file['id'].'-'.$file['uid'];
                @\yii\helpers\FileHelper::unlink($filePath);
            }
            Yii::$app->db->createCommand()->delete('at_files', ['n_id'=>$thePost['id']])->execute();
            Yii::$app->db->createCommand()->delete('at_tasks', ['n_id'=>$thePost['id']])->execute();
            Yii::$app->db->createCommand()->delete('at_message_to', ['message_id'=>$thePost['id']])->execute();
            Yii::$app->db->createCommand()->delete('mentions', ['post_id'=>$thePost['id']])->execute();
            Yii::$app->db->createCommand()->delete('at_messages', ['n_id'=>$thePost['id']])->execute();
            Yii::$app->db->createCommand()->delete('at_messages', ['id'=>$thePost['id']])->execute();

            if (Yii::$app->request->isAjax) {
                return [
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>Yii::t('x', 'Post has been deleted: #').$thePost['id'],
                ];
            }

            if ($thePost['n_id'] != 0) {
                return $this->redirect('/posts/'.$thePost['n_id']);
            }
            if ($thePost['rtype'] == 'company') {
                $thePost['rtype'] = 'companie';
            }

            Yii::$app->session->setFlash('success', Yii::t('x', 'Post has been deleted: #').$thePost['id']);
            if ($thePost['rtype'] == 'company') {
                return $this->redirect('/b2b/clients/r/'.$thePost['rid']);
            }
            return $this->redirect(DIR.$thePost['rtype'].'s/r/'.$thePost['rid']);
        }

        return $this->render('posts_d', [
            'thePost'=>$thePost
        ]);
    }
}
