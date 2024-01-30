<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property string $title
 * @property int $author_id
 * @property int|null $page_count
 * @property string|null $language
 * @property string|null $genre
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author_id'], 'required'],
            [['author_id', 'page_count'], 'integer'],
            [['title', 'language', 'genre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название книги',
            'author_id' => 'Автор',
            'page_count' => 'Число страниц',
            'language' => 'Язык',
            'genre' => 'Жанр',
        ];
    }

    static public function search($params = null)
    {
        $page = Yii::$app->getRequest()->getQueryParam('page');
        $limit = Yii::$app->getRequest()->getQueryParam('limit');
        $order = Yii::$app->getRequest()->getQueryParam('order');

        $search = Yii::$app->getRequest()->getQueryParam('search');

        if (isset($search)) {
            $params = $search;
        }

        $limit = isset($limit) ? $limit : 10;
        $page = isset($page) ? $page : 1;

        $offset = ($page - 1) * $limit;

        $query = Books::find()
            ->select(['id', 'title', 'author_id', 'page_count', 'language', 'genre'])
            ->asArray(true)
            ->limit($limit)
            ->offset($offset);

        if (isset($params['id'])) {
            $query->andFilterWhere(['id' => $params['id']]);
        }

        if (isset($params['title'])) {
            $query->andFilterWhere(['like', 'title', $params['title']]);
        }

        if (isset($params['author_id'])) {
            $query->andFilterWhere(['author_id' => $params['author_id']]);
        }

        if (isset($params['page_count'])) {
            $query->andFilterWhere(['page_count' => $params['page_count']]);
        }

        if (isset($params['language'])) {
            $query->andFilterWhere(['like', 'language', $params['language']]);
        }

        if (isset($params['genre'])) {
            $query->andFilterWhere(['like', 'genre', $params['genre']]);
        }

        if (isset($order)) {
            $query->orderBy($order);
        }


        $additional_info = [
            'page' => $page,
            'size' => $limit,
            'totalCount' => (int)$query->count()
        ];

        return [
            'data' => $query->all(),
            'info' => $additional_info
        ];
    }
}
