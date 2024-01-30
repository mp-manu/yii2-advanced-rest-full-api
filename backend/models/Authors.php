<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "authors".
 *
 * @property int $id
 * @property string $name
 * @property int|null $birth_year
 * @property string|null $country
 */
class Authors extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['birth_year'], 'integer'],
            [['name', 'country'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя автора',
            'birth_year' => 'Год рожения',
            'country' => 'Страна',
        ];
    }

    static public function search($params)
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

        if (isset($params['name'])) {
            $query->andFilterWhere(['like', 'name', $params['name']]);
        }

        if (isset($params['birth_year'])) {
            $query->andFilterWhere(['birth_year' => $params['birth_year']]);
        }

        if (isset($params['country'])) {
            $query->andFilterWhere(['like', 'country', $params['country']]);
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
