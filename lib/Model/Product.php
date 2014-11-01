<?php

namespace xShop;

class Model_Product extends \Model_Table{
	public $table='xshop_products';
	public $table_alias='Item';

	function init(){
		parent::init();	
		$this->hasOne('xShop/Supplier','supplier_id');
		$this->hasOne('xShop/Manufacturer','manufacturer_id');
		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->addField('name')->mandatory(true);
		$this->addField('sku')->PlaceHolder('Insert Unique Referance Code')->caption('Code')->hint('Place your unique product code ')->mandatory(true);
		$this->addField('short_description')->type('text')->display(array('form'=>'RichText'));
		$this->addField('description')->type('text')->display(array('form'=>'RichText'));
		$this->addField('original_price')->mandatory(true);
		$this->addField('sale_price')->type('int')->mandatory(true);
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d'));				
		$this->addField('expiry_date')->type('date');
		$this->addField('meta_title');
		$this->addField('meta_description')->type('text');
		$this->addField('rank_weight')->defaultValue(0)->hint('Higher Rank Weight Product Display First')->mandatory(true);
		$this->addField('tags')->type('text')->PlaceHolder('Comma Separated Value');
		$this->addField('allow_comments')->type('boolean');
		$this->addField('allow_attachment')->type('boolean');
		$this->addField('comment_api')->setValueList(
														array('disqus'=>'Disqus')
														);

		$this->addField('search_string')->type('text')->system(true);
		$this->addField('show_offer')->type('boolean');
		$this->addField('show_detail')->type('boolean')->defaultValue(true);
		$this->addField('allow_enquiry')->type('boolean');
		$this->addField('allow_saleable')->type('boolean');
		$this->addField('show_price')->type('boolean');
		$this->addField('show_manufacturer_detail')->type('boolean');
		$this->addField('show_supplier_detail')->type('boolean');
		
		$this->addField('add_custom_button')->type('boolean');
		$this->addField('custom_button_text');
		$this->addField('custom_button_url')->placeHolder('subpage name like registration etc.');
		
		$this->addField('enquiry_send_to_self')->type('boolean');
		$this->addField('enquiry_send_to_supplier')->type('boolean');
		$this->addField('enquiry_send_to_manufacturer')->type('boolean');
		$this->addField('product_enquiry_auto_reply')->type('boolean');

		$this->addField('is_publish')->type('boolean')->defaultValue(true);
		$this->addField('new')->type('boolean')->caption('mark_new')->defaultValue(true);
		$this->addField('feature')->type('boolean')->caption('mark_featured');
		$this->addField('latest')->type('boolean')->caption('mark_latest');
		$this->addField('mostviewed')->type('boolean')->caption('mark_most_viewed');
		
		$this->hasMany('xShop/CategoryProduct','product_id');
		$this->hasMany('xShop/ProductImages','product_id');
		$this->hasMany('xShop/CustomFields','product_id');
		$this->hasMany('xShop/Attachments','product_id');
		$this->hasMany('xShop/ProductEnquiry','product_id');
		
		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');	
	}

	function beforeSave($m){
		// todo checking SKU value must be unique
		$product_old=$this->add('xShop/Model_Product');
		if($this->loaded())
			$product_old->addCondition('id','<>',$this->id);

		$product_old->addCondition('sku',$this['sku']);		
		// $product_old->addCondition(
		// 	$product_old->_dsql()->orExpr()
		// 		->where('sku',$this['sku'])
		// 		->where('rank_weight',$this['rank_weight'])
		// 	);

		// $product_old->setOrder('rank_weight','desc');
		$product_old->tryLoadAny();
		//TODO Rank Weight Auto Increment 
		if($product_old->loaded())
			throw new \Exception("Product Code is Allready Exist");


		//do inserting search string for full text search
		// $p_model=$this->add('xShop/Model_Product');
		$this['search_string']= implode(" ", $this->getCategory($this['id'])). " ".
								$this["name"]. " ".
								$this['sku']. " ".
								$this['short_description']. " ".
								$this["description"]. " ".
								$this["meta_title"]. " ".
								$this["meta_description"]. " ".
								$this['sale_price']
							;

	}	

	function getCategory($product_id){
		$cat_pro_model=$this->add('xShop/Model_CategoryProduct');
		$cat_pro_model->addCondition('product_id',$product_id);
		$cat_name=array();
		foreach ($cat_pro_model as $j) {
			$cat_name[]=$cat_pro_model->ref('category_id')->get('name');
		}
		return $cat_name;				
	}

	function beforeDelete(){

	}

	function updateSearchString($product_id){
		if($this->loaded()){
			$this['search_string']= implode(" ", $this->getCategory($this['id'])). " ".
								$this["name"]. " ".
								$this['sku']. " ".
								$this['short_description']. " ".
								$this["description"]. " ".
								$this["meta_title"]. " ".
								$this["meta_description"]. " ".
								$this['sale_price']
							;						
			$this->update();
		}
	}

	function sendEnquiryMail($to_mail,$name=null,$contact=null,$email_id=null,$message=null,$form=null,$product_name,$product_code,$reply_email='0'){
						
		$tm=$this->add( 'TMail_Transport_PHPMailer' );
		$msg=$this->add( 'SMLite' );
		if(!$reply_email){
			$msg->loadTemplate( 'mail/xShop_productenquiry' );		
			$msg_body = "<h3>Enquiry Related to Product:".$product_name."<br> Product Code ".$product_code."</h3>";			
			$msg_body .= "<b>Name : </b>".$name."<br>";
			$msg_body .= "<b>Email id :</b>".$email_id."<br>";
			$msg_body .= "<b>Contact No :</b>".$contact."<br>";
			$msg_body .= "<b>Message : </b>".$message."<br>";
			$msg->trySet('epan',$this->api->current_website['name']);
			$msg->setHTML('custome_form',$msg_body);
			$subject ="You Got An  Enquiry !!!";			
		}else{
			$config_model=$this->add('xShop/Model_Configuration');
			$config_model->tryLoadAny();

			$subject =$config_model['subject'];
			$msg->loadTemplate( 'mail/xShop_productenquiryreply' );		
			$msg_body = $config_model['message'];
			// throw new \Exception("Error Processing Request".$msg_body);			
			$msg_body .= '<b>Product_name : </b>'.$product_name."<br>";
			$msg_body .= '<b>Product_code : </b>'.$product_code."<br>";
			$msg->setHTML('enquiry_product_reply_detail',$msg_body);
		}

		$email_body=$msg->render();
		// throw new \Exception($to_mail);
		
		if($to_mail){
				$tm->send( $to_mail, "", $subject, $email_body ,false,null);
				// throw new \Exception("Error Processing Request mail in send", 1);
			}

	}

	function updateContent($id,$content){
		if($this->loaded())
			throw new \Exception("Model_loaded at time of product");

		$this->load($id);
		$this['description']=$content;
		$this->save();
		return 'true';
	}

	function getProduct($id){
		$this->load($id);				
		return $this;
	}
}

