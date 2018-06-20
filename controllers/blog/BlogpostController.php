<?php

namespace app\controllers\blog;

use common\models\Post;
use common\models\Comment;
use common\models\User;
use yii\data\Pagination;
use yii\web\HttpException;
use Yii;

class BlogpostController extends \app\controllers\MyController
{

    public function actionIndex($cat = 0, $tag = '', $author = 0, $year = '', $search = '')
    {
        $query = Post::find()
            ->where(['acc_id'=>1, 'channel'=>'blog', 'status'=>'on'])
            ->andWhere('online_from <= NOW()');

        if ($cat != 0) {
            $query->andWhere(['cats'=>$cat]);
        }
        if ($tag != '') {
            $query->andWhere(['like', 'tags', $tag]);
        }
        if ($author != 0) {
            $query->andWhere(['author_id'=>$author]);
        }
        if (strlen($year) == 4) {
            $query->andWhere('SUBSTRING(online_from,1,4)=:year', [':year'=>$year]);
        }
        if ($search != '') {
            $query->andWhere(['like', 'title', $search]);
        }

        $countQuery = clone $query;
        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'=>10,
            ]);

        $blogPosts = $query
            ->select(['id', 'cats', 'tags', 'online_from', 'status', 'title', 'author_id', 'summary', 'hits', 'comment_count', 'image', 'is_sticky'])
            ->with(['author'])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('is_sticky, online_from DESC')
            ->all();

        $latestComments = Comment::find()
            ->select(['id', 'created_at', 'created_by', 'rid'])
            ->with(['createdBy', 'blogpost'])
            ->where(['status'=>'on', 'rtype'=>'blogpost'])
            ->orderBy('created_at DESC')
            ->limit(5)
            ->all();

        $sql = 'SELECT tags FROM at_posts WHERE channel="blog" LIMIT 500';
        $tags = Yii::$app->db->createCommand($sql)->queryAll();
        $allTagList = [];
        $allTagListOk = [];
        foreach ($tags as $tags1) {
            $tags2 = explode(',', $tags1['tags']);
            foreach ($tags2 as $tag3) {
                if (trim($tag3) != '') {
                    $allTagList[] = trim($tag3);
                }
            }
        }

        asort($allTagList);
        foreach ($allTagList as $iTag) {
            if (!in_array($iTag, $allTagListOk)) {
                $allTagListOk[] = $iTag;
            }
        }
        $allTagList = $allTagListOk;

        $sql = 'SELECT u.id, u.name FROM persons u, at_posts p WHERE p.channel="blog" AND u.id=p.author_id GROUP BY u.id ORDER BY u.lname, u.fname';
        $allAuthorList = User::findBySql($sql)->all();

        $sql = 'SELECT SUBSTRING(online_from,1,4) AS yr FROM at_posts p WHERE p.channel="blog" AND p.status="on" GROUP BY yr ORDER BY yr DESC';
        $allYearList = Yii::$app->db->createCommand($sql)->queryAll();

        return $this->render('blogposts', [
            'blogPosts'=>$blogPosts,
            'pagination'=>$pagination,
            'cat'=>$cat,
            'tag'=>$tag,
            'author'=>$author,
            'year'=>$year,
            'search'=>$search,
            'allAuthorList'=>$allAuthorList,
            'allTagList'=>$allTagList,
            'allYearList'=>$allYearList,
            'latestComments'=>$latestComments,
        ]);
    }

    public function actionC()
    {
        $theEntry = new Post(['scenario'=>'create']);
        $theEntry->online_from = date('Y-m-d');

        if ($theEntry->load($_POST) && $theEntry->validate()) {
            $theEntry->acc_id = 1;
            $theEntry->channel = 'blog';
            $theEntry->created_at = NOW;
            $theEntry->created_by = USER_ID;
            $theEntry->updated_at = NOW;
            $theEntry->updated_by = USER_ID;
            $theEntry->blog_id = 1;
            $theEntry->author_id = USER_ID;
            $theEntry->is_sticky = 'no';
            $theEntry->status = 'draft';
            $theEntry->save(false);
            return $this->redirect('@web/blog/posts/u/'.$theEntry['id']);
        }

        return $this->render('blogposts_c', [
            'theEntry'=>$theEntry,
        ]);
    }

    public function actionR($id = 0)
    {
        $theEntry = Post::find()
            ->where(['id'=>$id, 'channel'=>'blog'])
            ->with([
                'comments',
                'author',
                'author.profileMember',
                'comments.createdBy'=>function($q) {
                    return $q->select(['id', 'name', 'image']);
                },
            ])
            ->one();

        if (!$theEntry) {
            throw new HttpException(404, 'Entry not found');
        }

        if ($theEntry->status != 'on' && !in_array(USER_ID, [1, $theEntry->author_id])) {
            throw new HttpException(403);
        }

        if ($theEntry['status'] == 'on') {
            $theEntry->updateCounters(['hits'=>1]);
        }

        $postComment = new Comment;
        $postComment->scenario = 'create';

        $latestPosts = Post::find()
            ->select(['id', 'online_from', 'status', 'title', 'author_id', 'summary', 'hits', 'comment_count', 'image'])
            ->with(['author'])
            ->limit(4)
            ->orderBy('online_from DESC')
            ->all();

        if (isset($_POST['email']) && $_POST['email'] != '' && strpos($_POST['email'], '@amicatravel.com') !== false && $theEntry['status'] == 'on') {
            $this->mgIt(
                'news | '.$theEntry['title'],
                '//mg/blogpost_notification',
                [
                    'theEntry'=>$theEntry,
                ],
                [
                    ['from', 'noreply-news@amicatravel.com', 'Amica Travel', 'News'],
                    ['to', $_POST['email']],
                    ['bcc', 'hn.huan@gmail.com', 'Huân', 'H.'],
                ]
            );
            Yii::$app->session->setFlash('success', 'Email has been sent to '.$_POST['email']);
            return $this->redirect(DIR.URI);
        }

        if ($postComment->load($_POST) && $postComment->validate()) {
            // Huan to post noti of another user's comment
            if (USER_ID == 1 && (int)$postComment['body'] != 0) {
                $cmtId = (int)$postComment['body'];
                $theCmt = Comment::findOne($cmtId);
                $this->mgIt(
                    'news | '.$theCmt['createdBy']['name'].' comment trong bài: '.$theEntry['title'],
                    '//mg/post_comment_posted',
                    [
                        'theCmt'=>$theCmt,
                        'theEntry'=>$theEntry,
                    ],
                    [
                        ['from', 'noreply-news@amicatravel.com', 'Amica Travel', 'News'],
                        ['to', 'group.hanoi@amicatravel.com', 'Group Hanoi'],
                        ['to', 'group.saigon@amicatravel.com', 'Group Saigon'],
                    ]
                );
                return $this->redirect(DIR.URI.'#comment-id-'.$theCmt['id']);
            }
            $postComment->updated_at = NOW;
            $postComment->updated_by = USER_ID;
            $postComment->status = 'on';
            $postComment->rtype = 'blogpost';
            $postComment->rid = $id;
            $postComment->ip = USER_IP;
            $postComment->save(false);
            // Update comment counr
            $theEntry->updateCounters(['comment_count'=>1]);
            // Notify author and other commenters
            $this->mgIt(
                'news | '.$postComment['createdBy']['name'].' comment trong bài: '.$theEntry['title'],
                '//mg/post_comment_posted',
                [
                    'theCmt'=>$postComment,
                    'theEntry'=>$theEntry,
                ],
                [
                    ['from', 'noreply-news@amicatravel.com', 'Amica Travel', 'News'],
                    ['to', 'group.hanoi@amicatravel.com', 'Group Hanoi'],
                    ['to', 'group.saigon@amicatravel.com', 'Group Saigon'],
                ]
            );
            // Return
            return $this->redirect(DIR.URI.'#comment-id-'.$postComment->id);
        }

        $sql = 'SELECT u.id, u.name FROM persons u, at_posts p WHERE u.id=p.author_id GROUP BY u.id ORDER BY u.lname, u.fname';
        $allAuthorList = User::findBySql($sql)->all();

        $sql = 'SELECT tags FROM at_posts LIMIT 500';
        $tags = Yii::$app->db->createCommand($sql)->queryAll();
        $allTagList = [];
        $allTagListOk = [];
        foreach ($tags as $tags1) {
            $tags2 = explode(',', $tags1['tags']);
            foreach ($tags2 as $tag3) {
                if (trim($tag3) != '') {
                    $allTagList[] = trim($tag3);
                }
            }
        }

        asort($allTagList);
        foreach ($allTagList as $iTag) {
            if (!in_array($iTag, $allTagListOk)) {
                $allTagListOk[] = $iTag;
            }
        }
        $allTagList = $allTagListOk;

        $sql = 'SELECT u.id, u.name FROM persons u, at_posts p WHERE u.id=p.author_id GROUP BY u.id ORDER BY u.lname, u.fname';
        $allAuthorList = User::findBySql($sql)->all();

        return $this->render('blogposts_r', [
            'theEntry'=>$theEntry,
            'postComment'=>$postComment,
            'allAuthorList'=>$allAuthorList,
            'allTagList'=>$allTagList,
        ]);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws HttpException
     */
    public function actionU($id = 0)
    {

        $theEntry = Post::find()
            ->where(['channel'=>'blog', 'id'=>$id])
            ->one();
        if (!$theEntry) {
            throw new HttpException(404);
        }

        if (!in_array(USER_ID, [1, $theEntry->author_id, $theEntry->updated_by])) {
            throw new HttpException(403, 'You are not allowed to edit this post');
        }

        $theEntry->setScenario('update');

        $authorList = User::find()
            ->select(['id', 'name'])
            ->where(['status'=>'on', 'is_member'=>'yes'])
            ->orWhere(['id'=>$theEntry->author_id])
            ->orderBy('lname, fname')
            ->asArray()
            ->all();

        if ($theEntry->load($_POST) && $theEntry->validate()) {
            if (USER_ID != 1) {
            $theEntry->updated_at = NOW;
            $theEntry->updated_by = USER_ID;
            }
            $theEntry->save(false);
            return $this->redirect('@web/blog/posts/r/'.$theEntry['id']);
        }

        $uploadDir = 'blog/posts/'.substr($theEntry['created_at'], 0, 7).'/'.$theEntry['id'];
        @mkdir(Yii::getAlias('@webroot').'/upload/'.$uploadDir);

        $ckfSessionName = 'blogpost'.$theEntry['id'];
        $ckfSessionValue = [
            'ckfResourceName'=>'upload',
            'ckfResourceDirectory'=>$uploadDir,
        ];
        Yii::$app->session->set('ckfAuthorized', true);
        Yii::$app->session->set('ckfRole', 'user');
        Yii::$app->session->set($ckfSessionName, $ckfSessionValue);

        return $this->render('blogposts_u', [
            'theEntry'=>$theEntry,
            'authorList'=>$authorList,
        ]);
    }

    public function actionD($id = 0)
    {
        $thePost = Post::find()
            ->where(['channel'=>'blog', 'id'=>$id])
            ->one();
        if (!$theEntry) {
            throw new HttpException(404);
        }

        echo('DELETING NOT AVAILABLE YET');
        exit;
    }
}
