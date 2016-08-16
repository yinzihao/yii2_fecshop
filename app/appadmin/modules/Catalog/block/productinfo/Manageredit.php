<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
namespace fecshop\app\appadmin\modules\Catalog\block\productinfo;
use Yii;
use fecshop\app\appadmin\modules\AppadminbaseBlockEdit;
use fec\helpers\CUrl;
use fec\helpers\CRequest;
use fecshop\app\appadmin\interfaces\base\AppadminbaseBlockEditInterface;
use fecshop\app\appadmin\modules\Catalog\block\productinfo\index\Attr;
/**
 * block catalog/productinfo
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
class Manageredit  extends AppadminbaseBlockEdit implements AppadminbaseBlockEditInterface
{
	public  $_saveUrl;
	protected $_attr;
	
	public function init(){
		$this->_saveUrl = CUrl::getUrl('catalog/productinfo/managereditsave');
		parent::init();
		$this->_attr = new Attr($this->_one);
		//$this->_param		= $request_param[$this->_editFormData];
	}
	
	public function setService(){
		$this->_service 	= Yii::$service->product;
	}
	
	public function getCurrentProductPrimay(){
		$primaryKey = Yii::$service->product->getPrimaryKey();
		$primaryVal = CRequest::param($primaryKey);
		if($primaryVal){
			return $primaryKey.'='.$primaryVal;
		}
		return '';
	}
	
	# 传递给前端的数据 显示编辑form	
	public function getLastData(){
		
		return [
			'baseInfo' 		=> $this->getBaseInfo(),
			'metaInfo' 		=> $this->getMetaInfo(),
			'groupAttr'		=> $this->getGroupAttr(),
			'descriptionInfo' => $this->getDescriptionInfo(),
			'attrGroup'		=> $this->_attr->getProductAttrGroupSelect(),
			'primaryInfo' 	=> $this->getCurrentProductPrimay(),
			'img_html'		=> $this->getImgHtml(),
			//'editBar' 	=> $this->getEditBar(),
			//'textareas'	=> $this->_textareas,
			//'lang_attr'	=> $this->_lang_attr,
			'saveUrl' 	=> $this->_saveUrl,
		];
	}
	
	
	public function getBaseInfo(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getBaseInfo();
		$editBar = $this->getEditBar($editArr);
		return $this->_lang_attr.$editBar.$this->_textareas;
	}
	
	public function getMetaInfo(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getMetaInfo();
		$editBar = $this->getEditBar($editArr);
		return $this->_lang_attr.$editBar.$this->_textareas;
	}
	
	public function getDescriptionInfo(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getDescriptionInfo();
		$editBar = $this->getEditBar($editArr);
		return $this->_lang_attr.$editBar.$this->_textareas;
	}
	
	
	public function getGroupAttr(){
		$this->_lang_attr = '';
		$this->_textareas = '';
		$editArr = $this->_attr->getGroupAttr();
		//var_dump($editArr);
		//var_dump($this->_one);
		$this->_primaryKey  = $this->_service->getPrimaryKey();
		$id 				= $this->_param[$this->_primaryKey];
		$this->_one = $this->_service->getByPrimaryKey($id);
		if(!empty($editArr)){
			$editBar = $this->getEditBar($editArr);
			return $this->_lang_attr.$editBar.$this->_textareas;
		}
		return '';
		
	}
	
	public function getImgHtml(){
		$main_image = [
			'image' => '/2/14/21471340924755.jpg',
			'label' => 'xxxx',
			'sort_order'=> 1,
		];
		$gallery_image = [
			[
				'image' 	=> '/1/6/16.jpg',
				'label' 	=> 'xxxx',
				'sort_order'=> 5,
			],
			[
				'image' => '/3/14/31471340924495.jpg',
				'label' => 'yyy',
				'sort_order'=> 6,
			]
		];
		$str =
		'<div>
			
			<table class="list productimg" width="100%" >
				<thead>
					<tr>
						<td>图片</td>
						<td>label</td>
						<td>sort_order</td>
						<td>主图</td>
						<td>删除</td>
					</tr>
				</thead>
				<tbody>
					<tr class="p_img" rel="1" style="border-bottom:1px solid #ccc;">
						<td style="width:120px;text-align:center;"><img  rel="'.$main_image['image'].'" style="width:100px;height:100px;" src="'.Yii::$service->product->image->getUrl($main_image['image']).'"></td>
						<td style="width:220px;text-align:center;"><input style="height:10px;width:200px;" type="text" class="image_label" name="image_label"  value="'.$main_image['label'].'" /></td>
						<td style="width:220px;text-align:center;"><input style="height:10px;width:200px;" type="text" class="sort_order"  name="sort_order" value="'.$main_image['sort_order'].'"  /></td>
						<td style="width:30px;text-align:center;"><input type="radio" name="image" checked  value="'.$main_image['image'].'" /></td>
						<td style="padding:0 0 0 20px;"><a class="delete_img btnDel" href="javascript:void(0)">删除</a></td>
					</tr>';
					if(!empty($gallery_image) && is_array($gallery_image)){
						$i=2;
						foreach($gallery_image as $gallery){
							$str .='<tr class="p_img" rel="'.$i.'" style="border-bottom:1px solid #ccc;">
									<td style="width:120px;text-align:center;"><img  rel="'.$gallery['image'].'" style="width:100px;height:100px;" src="'.Yii::$service->product->image->getUrl($gallery['image']).'"></td>
									<td style="width:220px;text-align:center;"><input style="height:10px;width:200px;" type="text" class="image_label" name="image_label"  value="'.$gallery['label'].'" /></td>
									<td style="width:220px;text-align:center;"><input style="height:10px;width:200px;" type="text" class="sort_order"  name="sort_order" value="'.$gallery['sort_order'].'"  /></td>
									<td style="width:30px;text-align:center;"><input type="radio" name="image"   value="'.$gallery['image'].'" /></td>
									<td style="padding:0 0 0 20px;"><a class="delete_img btnDel" href="javascript:void(0)">删除</a></td>
								</tr>';
							$i++;
						}
						
					}
														
		$str .=	'</tbody>
			</table>
		</div>';
		return $str;
	}
	
	
	
	public function getEditArr(){
		return [
			[
				'label'=>'标题',
				'name'=>'title',
				'display'=>[
					'type' => 'inputString',
					'lang' => true,
				],
				'require' => 1,
			],
			
			[
				'label'=>'Url Key',
				'name'=>'url_key',
				'display'=>[
					'type' => 'inputString',
				],
				'require' => 0,
			],
			
			[
				'label'=>'Meta Keywords',
				'name'=>'meta_keywords',
				'display'=>[
					'type' => 'inputString',
					'lang' => true,
				],
				'require' => 0,
			],
			
			[
				'label'=>'Meta Description',
				'name'=>'meta_description',
				'display'=>[
					'type' => 'textarea',
					'lang' => true,
					'rows'	=> 14,
					'cols'	=> 110,
				],
				'require' => 0,
			],
			
			[
				'label'=>'Content',
				'name'=>'content',
				'display'=>[
					'type' => 'textarea',
					'lang' => true,
					'rows'	=> 14,
					'cols'	=> 110,
				],
				'require' => 0,
			],
			
			[
				'label'=>'用户状态',
				'name'=>'status',
				'display'=>[
					'type' => 'select',
					'data' => [
						1 	=> '激活',
						2 	=> '关闭',
					]
				],
				'require' => 1,
				'default' => 1,
			],
		];
	}
	/**
	 * save article data,  get rewrite url and save to article url key.
	 */
	public function save(){
		$request_param 		= CRequest::param();
		$this->_param		= $request_param[$this->_editFormData];
		$this->_param['attr_group'] = CRequest::param('attr_group');
		//var_dump($this->_param['attr_group']);exit;
		/**
		 * if attribute is date or date time , db storage format is int ,by frontend pass param is int ,
		 * you must convert string datetime to time , use strtotime function.
		 */
		// var_dump()
		$this->_service->save($this->_param,'catalog/product/index');
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"save success",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
	}
	
	
	# 批量删除
	public function delete(){
		$ids = '';
		if($id = CRequest::param($this->_primaryKey)){
			$ids = $id;
		}else if($ids = CRequest::param($this->_primaryKey.'s')){
			$ids = explode(',',$ids);
		}
		$this->_service->remove($ids);
		$errors = Yii::$service->helper->errors->get();
		if(!$errors ){
			echo  json_encode(array(
				"statusCode"=>"200",
				"message"=>"remove data  success",
			));
			exit;
		}else{
			echo  json_encode(array(
				"statusCode"=>"300",
				"message"=>$errors,
			));
			exit;
		}
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
}