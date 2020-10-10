<?php

namespace app\modules\apiLogger\models\search;

use app\modules\apiLogger\models\DbLoggerModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class ApiLoggerSearch
 * @package app\modules\apiLogger\models\search
 */
class DbLoggerModelSearch extends DbLoggerModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['created_at', 'url', 'method', 'request', 'headers', 'answer', 'app_version', 'app_platform'], 'safe'],
            [['duration'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DbLoggerModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'duration' => $this->duration,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'request', $this->request])
            ->andFilterWhere(['like', 'headers', $this->headers])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'app_version', $this->app_version])
            ->andFilterWhere(['like', 'app_platform', $this->app_platform]);

        return $dataProvider;
    }
}
