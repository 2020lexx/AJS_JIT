<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ProProduct;

/**
 * ProProductSearch represents the model behind the search form about `common\models\ProProduct`.
 */
class ProProductSearch extends ProProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parentID', 'category', 'type', 'mu', 'status'], 'integer'],
            [['code', 'name', 'short_desc', 'long_desc', 'title', 'meta_desc', 'sku', 'cart_desc', 'images', 'min_unit', 'tags', 'timestamp'], 'safe'],
            [['price'], 'number'],
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
        $query = ProProduct::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parentID' => $this->parentID,
            'category' => $this->category,
            'type' => $this->type,
            'price' => $this->price,
            'mu' => $this->mu,
            'status' => $this->status,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'short_desc', $this->short_desc])
            ->andFilterWhere(['like', 'long_desc', $this->long_desc])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta_desc', $this->meta_desc])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'cart_desc', $this->cart_desc])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'min_unit', $this->min_unit])
            ->andFilterWhere(['like', 'tags', $this->tags]);

        return $dataProvider;
    }
}
