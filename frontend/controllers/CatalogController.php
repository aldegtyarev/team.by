<?php

namespace frontend\controllers;

use Yii;

use common\models\Category;
use common\models\User;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;

class CatalogController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
	{
		$categories = Category::find()->where('id <> 1 AND depth < 3')->orderBy('lft, rgt')->all();
		
		$cats_l1 = [];

		foreach($categories as $c){
			if($c->parent_id == 1)	$cats_l1[] = [
				'id'=>$c->id,
				'name'=>$c->name,
				'alias'=>$c->alias,
				'path'=>$c->path,
				'children'=>[],
			];
		}		
		
		foreach($cats_l1 as &$c_l1){
			foreach($categories as $c){
				if($c->parent_id == $c_l1['id']) {
					$c_l1['children'][] = [
						'id'=>$c->id,
						'name'=>$c->name,
						'alias'=>$c->alias,
						'path'=>$c->path,
					];
				}
			}
		}
		//echo'<pre>';print_r($cats_l1);echo'</pre>';
		
		return $this->render('index', [
			'categories' => $cats_l1,
		]);
		
	}
	
    public function actionCategory($category)
    {
		//получаем поле для сортировки
		$orderBy = Yii::$app->request->post('orderby', '');
		if($orderBy != '') {
			Yii::$app->response->cookies->add(new \yii\web\Cookie([
				'name' => 'catlist-orderby',
				'value' => $orderBy,
			]));
			
			//return $this->redirect(['category', 'category'=>$category]);
		}	else	{
			$orderBy = Yii::$app->request->cookies->getValue('catlist-orderby', 'fio');
		}
		
		//строим выпадающий блок для сортировки
		$ordering_arr = Yii::$app->params['catlist-orderby'];
		$ordering_items = [];
		foreach($ordering_arr as $k=>$i) {
			if($k == $orderBy)	{
				$current_ordering = ['name'=>$i, 'field'=>$k];
			}	else	{
				$ordering_items[] = [
					'label'=>$i,
					'url' => '#',
					'linkOptions' => ['data-sort' => $k],
				];
			}
				
		}
		
         //echo'<pre>';print_r($category);echo'</pre>';
		// Ищем категорию по переданному пути
        $category = Category::find()
			->where(['path' => $category])
			->one();			

        if ($category === null)
            throw new CHttpException(404, 'Not found');
		
		//получаем всех родителей данной категории для хлебной крошки
		$parents = $category->parents()->all();
		
		//получаем потомков категории
		$children = $category->children()->all();
		
		//получаем массив ИД категорий для поиска аккаунтов в них
		$cat_ids = [$category->id];
		foreach($children as $k=>$c)	{
			if($c->depth < 3)	{				
				$cat_ids[] = $c->id;
			}	else	{
				//на 3-м уровне у нас идут виды работ. Их нужно исключить
				unset($children[$k]);
			}
		}
			
		
		
		
		$DataProvider = new ActiveDataProvider([
			'query' => User::find()
				->distinct(true)
				->join('INNER JOIN', '{{%user_categories}} AS uc', 'uc.user_id = {{%user}}.id')
				->where(['uc.category_id'=>$cat_ids])
				->orderBy('{{%user}}.'.$orderBy.' ASC'),
			
			'pagination' => [
				'pageSize' => Yii::$app->params['catlist-per-page'],
				//'pageSize' => 2,
				'pageSizeParam' => false,
			],
		]);
		
		$specials = Category::find()->where('depth > 2')->orderBy('lft, rgt')->all();
		$specials = ArrayHelper::map($specials, 'id', 'name');
		
		$categories_history = Yii::$app->session->get('categories_history', []);
		//echo'<pre>';print_r($categories_history);echo'</pre>';
		foreach($DataProvider->models as $model)	{
			$categories_history[$model->id] = $category->id;
		}
		
		Yii::$app->session->set('categories_history', $categories_history);

		return $this->render('category', [
			'category'=>$category,
			'parents'=>$parents,
			'children'=>$children,
			'dataProvider'=>$DataProvider,
			'current_ordering'=>$current_ordering,
			'ordering_items'=>$ordering_items,
			'specials'=>$specials,
		]);
    }    
 
    public function actionShow($category, $id)
    {
        $categories_history = Yii::$app->session->get('categories_history', []);
		
		
        //echo'<pre>';print_r($id);echo'</pre>';
		
		$model = User::findOne($id);		
		if ($model === null) throw new CHttpException(404, 'Аккаунт с данным ID отсутстсвует в базе');		
		
		//echo'<pre>';print_r($model->userCategories[0]);echo'</pre>'; die;
		
		if(count($categories_history))	{
			$category = Category::find()
				->where(['id' => $categories_history[$id]])
				->one();

			if($category === null) throw new NotFoundHttpException('Ошибка категории');
		}	else	{
			
			$category = Category::find()
				->where(['id' => $model->userCategories[0]->category_id])
				->one();

			if($category === null) throw new NotFoundHttpException('Ошибка категории');
				
		}
		
		//echo'<pre>';print_r($model);echo'</pre>';
		//echo'<pre>';print_r($category);echo'</pre>';
		
		//получаем всех родителей данной категории для хлебной крошки
		$parents = $category->parents()->all();
		
		//получаем потомков категории
		$children = $category->children()->all();
		
		
		//$model = $this->loadModel($id)
 
       // $this->render('show', array('model'=>$model));
        return $this->render('show', [
			'model'=>$model,
			'category'=>$category,
			'parents'=>$parents,
			'children'=>$children,			
		]);

    }
	
	
	
	
	
    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
