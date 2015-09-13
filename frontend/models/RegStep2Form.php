<?php
namespace common\models;
namespace frontend\models;

use Yii;
use yii\base\Model;

use yii\helpers\ArrayHelper;

use common\models\Region;
use common\models\Category;

use yii\web\UploadedFile;



/**
 * RegStep2 form
 */
class RegStep2Form extends Model
{
    public $region = 4;	// 4 - это ИД Минска
    public $about;
    public $education;
    public $experience;
    public $price_list;
    public $avatar;
    //public $region_parent_id = 2; // 2 - это ИД Минской области
    //public $region_name;
    public $specialization;
    public $categories = [];
    public $category1;
    public $price = [];
    public $unit = [];
	
    public $awards = [];
    public $examples = [];
    public $usluga = [];
	
	public $regions = [];
	public $ratios = [];	
	
	public $to_client;
	public $license;
	public $youtube;



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[[/*'region', 'region_parent_id',*/ 'category1', 'to_client'], 'integer'],
			
			
			['about', 'required'],
            ['about', 'string', 'min' => 3, 'max' => 2048],
            
			['education', 'string', 'min' => 3, 'max' => 2048],
            
			['experience', 'required'],
			['experience', 'string', 'min' => 3, 'max' => 2048],
			
            ['price_list', 'string', 'min' => 3, 'max' => 255],
			
			['avatar', 'required', 'message'=>'Загрузите свое фото для анкеты'],
            ['avatar', 'string', 'min' => 3, 'max' => 255],
            
			
           // ['region_name', 'string', 'min' => 3, 'max' => 255],
			
            ['specialization', 'string', 'min' => 3, 'max' => 200],
			
			['category1', 'required', 'message'=>'Выберите вид услуг'],
			//['price', 'validateEmptyPrices'],
			[['categories', 'price'], 'safe'],
			
			//['examples', 'required', 'message'=>'Загрузите примеры ваших работ'],
			[['examples', 'unit'], 'each', 'rule' => ['string']],
			
			['awards', 'each', 'rule' => ['string']],
			['usluga', 'each', 'rule' => ['string']],
			
			['regions', 'each', 'rule' => ['integer']],
			['ratios', 'each', 'rule' => ['double']],			
			
			['license', 'string', 'min' => 3, 'max' => 255],
			['youtube', 'url'],
			
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'about' => 'Коротко о себе',
            'education' => 'Ваше образование',
            'experience' => 'Опыт работы',
            'price_list' => 'Вы можете загрузить прайс',
            'avatar' => 'Загрузите фото для анкеты',
            'region' => 'Укажите регион',
            //'region_parent_id' => 'Область',
            //'region_name' => 'Город',
            'category1' => 'Выберите раздел ваших услуг',
            'price' => 'Стоимость работ',
            'unit' => 'за',
            'awards' => 'Награды, дипломы',
            'examples' => 'Примеры ваших работ',
            'to_client' => 'Осуществляем выезд к клиенту',
            'specialization' => 'Специализация',
            'license' => 'Лицензия',
			'youtube' => 'Видеообращение',
            //'' => '',
        ];
    }
	
    /**
     * @param string $attribute
     * @param array $params
     */
    public function validateEmptyPrices($attribute, $params)
    {
        //if (!$this->hasErrors()) {
			//print_r($this->$attribute);
			//echo'<pre>';var_dump($this->$attribute);echo'</pre>';die;
		
			foreach($this->$attribute as $i) {
				$i = trim($i);
				var_dump($i);
			}
			die;
			/*
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'ERROR_WRONG_CURRENT_PASSWORD'));
            }
			*/
        //}
    }
	
	
    //получает список категорий для выпадающего списка
	//с группировкой по областям
	public function getRegionsDropDownList()
    {
		$categories = Region::find()->where('id <> 1')->orderBy('lft, rgt')->all();
//		$categories1 = ArrayHelper::map($categories, 'id', 'name');
		//print_r($categories[2]->parent_id);
		
//		$categories2 = [];
//		foreach($categories as $row) {
//			if($row->parent_id != 1)
//				$categories2[$categories1[$row->parent_id]][$row->id] = $row->name;
//			
//		}
		foreach($categories as $c){
			$separator = '';
			for ($x=0; $x++ < $c->depth;) $separator .= '-';
			$c->name = $separator.' '.$c->name;
		}
		
		
		//$categories = [0=>'Выберите'] + $categories2;
		$categories = [0=>'Выберите'] + ArrayHelper::map($categories, 'id', 'name');
		return $categories;
    }
	
	//получает список категорий первого уровня для выпадающего списка
	protected function getRegionsLevel1DropDownList()
    {
		$categories = Region::find()->where('id <> 1 AND depth = 1')->orderBy('lft, rgt')->all();
		$categories = ArrayHelper::map($categories, 'id', 'name');
		return $categories;
    }
	
	//получает список категорий первого уровня для выпадающего списка
	protected function getCategoriesLevel1DropDownList()
    {
		$categories = Category::find()->where('id <> 1 AND depth = 1')->orderBy('lft, rgt')->all();
		$categories = ArrayHelper::map($categories, 'id', 'name');
		return $categories;
    }
	
	public function isChecked($id)
    {
		$res = '';
		if(count($this->categories))	{
			foreach($this->categories as $i)	{
				if($i == $id) {
					$res = 'checked';
					break;
				}
			}
		}
		return $res;
    }
	
	public function uslugaIsCheked($id)
    {
		$res = false;
		if(count($this->usluga))	{
			foreach($this->usluga as $i)	{
				if($i == $id) {
					$res = true;
					break;
				}
			}
		}
		return $res;
    }
	
    public function upload()
    {
        if ($this->validate()) { 
            foreach ($this->imageFiles as $file) {
                $file->saveAs( Yii::getAlias('@frontend').'/web/files/' . $file->baseName . '.' . $file->extension);
            }
			
			echo Yii::getAlias('@frontend').'/web/files/';
            return true;
        } else {
			echo'<pre>';print_r($this);echo'</pre>';
            return false;
        }
    }	
	
}
