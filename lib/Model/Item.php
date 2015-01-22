<?php

namespace xShop;

class Model_Item extends \Model_Table{
	public $table='xshop_items';
	public $table_alias='Item';

	function init(){
		parent::init();
		
		$this->hasOne('xShop/Application','application_id');
		$this->hasOne('xShop/MemberDetails','designer_id');

		//for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		// Basic Field
		$this->addField('name')->mandatory(true)->group('b~6')->sortable(true);
		$this->addField('sku')->PlaceHolder('Insert Unique Referance Code')->caption('Code')->hint('Place your unique Item code ')->mandatory(true)->group('b~3')->sortable(true);
		$this->addField('is_publish')->type('boolean')->defaultValue(true)->group('b~1')->sortable(true);
		$this->addField('is_party_publish')->type('boolean')->defaultValue(true)->group('b~2')->sortable(true);

		$this->addField('original_price')->type('int')->mandatory(true)->group('c~6');
		$this->addField('sale_price')->type('int')->mandatory(true)->group('c~6~bl')->sortable(true);
		$this->addField('short_description')->type('text')->group('c~6');
		
		$this->addField('rank_weight')->defaultValue(0)->hint('Higher Rank Weight Item Display First')->mandatory(true)->group('d~4');
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d'))->group('d~4');
		$this->addField('expiry_date')->type('date')->group('d~4');
		
		// Price and Qtuanitity Management
		$this->addField('minimum_order_qty')->type('int')->mandatory(true)->group('d~3');
		$this->addField('maximum_order_qty')->type('int')->mandatory(true)->group('d~3');
		$this->addField('qty_unit')->mandatory(true)->group('d~3');
		$this->addField('qty_from_set_only')->type('boolean')->group('d~3');
		
		$f = $this->addField('description')->type('text')->display(array('form'=>'RichText'))->group('g~12');
		// $f = $this->addField('theme_code')->hint('To club same theme code items in one')->group('b~4')->sortable(true);
		// $f = $this->addField('reference')->PlaceHolder('Any Referance')->hint('Use URL for external link')->group('b~4')->sortable(true);
		
		
		//Item Allow Optins
		$f = $this->addField('is_attachment_allow')->type('boolean')->group('f~3~<i class=\'fa fa-cog\' > Item Allow Options</i>');
		$f = $this->addField('is_saleable')->type('boolean')->group('f~3');
		$f = $this->addField('is_downloadable')->type('boolean')->group('f~3');
		$f = $this->addField('is_designable')->type('boolean');
		$f = $this->addField('is_rentable')->type('boolean')->group('f~3');
		$f = $this->addField('is_enquiry_allow')->type('boolean')->group('f~3');
		$f = $this->addField('is_template')->type('boolean')->defaultValue(false)->group('f~3');
		
		$f = $this->addField('negative_qty_allowed')->type('number');
		$f = $this->addField('is_visible_sold')->type('boolean')->hint('If Product remains visible after sold');

		//Search String
		$this->addField('search_string')->type('text')->system(true);

		//Item Display Options
		$f = $this->hasOne('xShop/ItemOffer','offer_id');
		$f = $this->addField('offer_position')->setValueList(array('top:0;-left:0;'=>'TopLeft','top:0;-right:0;'=>'TopRight','bottom:0;-left:0;'=>'BottomLeft','bottom:0;-right:0;'=>'BottomRight'));
		
		$f = $this->addField('show_detail')->type('boolean')->defaultValue(true)->group('i~2~Item');
		$f = $this->addField('show_price')->type('boolean')->group('i~2');

		//Marked
		$f = $this->addField('new')->type('boolean')->caption('New')->defaultValue(true)->group('m~3~<i class=\'fa fa-cog\' > Marked Options</i>');
		$f = $this->addField('feature')->type('boolean')->caption('Featured')->group('m~3');
		$f = $this->addField('latest')->type('boolean')->caption('Latest')->group('m~3');
		$f = $this->addField('mostviewed')->type('boolean')->caption('Most Viewed')->group('m~3');
		

		//Enquiry Send To		
		$f = $this->addField('enquiry_send_to_admin')->type('boolean')->group('e~3~<i class=\'fa fa-cog\' > Enquiry Send To</i>');
		// $f = $this->addField('enquiry_send_to_supplier')->caption('Supplier')->type('boolean')->group('e~3');
		// $f= $this->addField('enquiry_send_to_manufacturer')->caption('Manufacturer')->type('boolean')->group('e~3');
		$f = $this->addField('Item_enquiry_auto_reply')->caption('Item Enquiry Auto Reply')->type('boolean')->group('e~3');

		//Item Comment Options
		$f = $this->addField('allow_comments')->type('boolean')->group('com~4~<i class=\'fa fa-cog\'> Item Comment Options</i>');
		$f = $this->addField('comment_api')->setValueList(
														array('disqus'=>'Disqus')
														)->group('com~8');

		//Item Other Options	
		$f = $this->addField('add_custom_button')->type('boolean')->group('o~3~<i class=\'fa fa-cog\'> Item Other Options</i>');
		$f = $this->addField('meta_title')->group('o~3~bl');
		$f = $this->addField('custom_button_text')->group('o~4');
		$f = $this->addField('meta_description')->type('text')->group('o~4~bl');
		$f = $this->addField('custom_button_url')->placeHolder('subpage name like registration etc.')->group('o~5');
		$f = $this->addField('tags')->type('text')->PlaceHolder('Comma Separated Value')->group('o~5~bl');
		
		// Item WaterMark
		$f = $this->add('filestore/Field_Image','watermark_image_id');
		$f = $this->addField('watermark_text')->type('text')->group('o~5~bl');
		$f = $this->addField('watermark_position')->enum(array('TopLeft','TopRight','BottomLeft','BottomRight','Center','Left Diagonal','Right Diagonal'));
		$f = $this->addField('watermark_opacity');
		
		//Item Designs
		$f = $this->addField('designs')->type('text')->group('o~5~bl');

		$this->hasMany('xShop/CategoryItem','item_id');
		$this->hasMany('xShop/ItemAffiliateAssociation','item_id');
		$this->hasMany('xShop/ItemImages','item_id');
		$this->hasMany('xShop/Attachments','item_id');
		$this->hasMany('xShop/ItemEnquiry','item_id');
		$this->hasMany('xShop/OrderDetails','item_id');
		$this->hasMany('xShop/ItemSpecificationAssociation','item_id');
		$this->hasMany('xShop/CustomFieldValueFilterAssociation','item_id');
		$this->hasMany('xShop/CategoryItemCustomFields','item_id');
		$this->hasMany('xShop/ItemReview','item_id');
		$this->hasMany('xShop/ItemMemberDesign','item_id');

		$this->hasMany('xShop/QuantitySet','item_id');
		$this->hasMany('xShop/CustomRate','item_id');

		$this->addExpression('theme_code_group_expression')->set('(IF(ISNULL('.$this->table_alias.'.theme_code),'.$this->table_alias.'.id,'.$this->table_alias.'.theme_code))');
			
		$this->addHook('beforeSave',$this);
		$this->addHook('afterInsert',$this);
		$this->addHook('beforeDelete',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave($m){
		// todo checking SKU value must be unique
		$item_old=$this->add('xShop/Model_Item');
		if($this->loaded())
			$item_old->addCondition('id','<>',$this->id);
		$item_old->tryLoadAny();

		//TODO Rank Weight Auto Increment 
		if($item_old['sku'] == $this['sku'])
			throw $this->Exception('Item Code is Allready Exist','ValidityCheck')->setField('sku');


		//do inserting search string for full text search
		// $p_model=$this->add('xShop/Model_Item');
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

	function afterInsert($obj,$new_item_id){
		$new_item =  $this->add('xShop/Model_Item')->load($new_item_id);

		if(!$new_item['designer_id']) return;

		// if designable add as with admin => member's design too
		$designer = $this->add('xShop/Model_MemberDetails');
		$designer->load($new_item['designer_id']);

		$target = $this->item = $this->add('xShop/Model_ItemMemberDesign');
		$target['item_id'] = $new_item_id;
		$target['member_id'] = $designer->id;
		$target['designs'] = "";
		$target['is_dummy'] = true;
		$target->save();
	}

	function getCategory($item_id=null){
		if(!$item_id) $item_id= $this->id;

		$cat_pro_model=$this->add('xShop/Model_CategoryItem');
		$cat_pro_model->addCondition('item_id',$item_id);
		$cat_name=array();
		foreach ($cat_pro_model as $j) {
			$cat_name[]=$cat_pro_model->ref('category_id')->get('name');
		}
		return $cat_name;				
	}

	function beforeDelete($m){
		$order_count = $m->ref('xShop/OrderDetails')->count()->getOne();
		$item_enquiry_count = $m->ref('xShop/ItemEnquiry')->count()->getOne();
		
		if($this->api->auth->model['type'] and($order_count or $item_enquiry_count)){
			$this->api->js(true)->univ()->errorMessage('Cannot Delete,first delete Orders or Enquiry')->execute();	
		}

		$m->ref('xShop/CategoryItem')->deleteAll();
		$m->ref('xShop/ItemImages')->deleteAll();
		$m->ref('xShop/Attachments')->deleteAll();	 
		$m->ref('xShop/CategoryItemCustomFields')->deleteAll();	
	}

	function updateSearchString($item_id=null){
		if($this->loaded()){
			if(!$item_id) $item_id =$this->id;
			$this['search_string']= implode(" ", $this->getCategory()). " ".
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

	function sendEnquiryMail($to_mail,$name=null,$contact=null,$email_id=null,$message=null,$form=null,$item_name,$item_code,$reply_email='0'){
						
		$tm=$this->add( 'TMail_Transport_PHPMailer' );
		$msg=$this->add( 'SMLite' );
		if(!$reply_email){
			$msg->loadTemplate( 'mail/xShop_itemenquiry' );		
			$msg_body = "<h3>Enquiry Related to Item:".$item_name."<br> Item Code ".$item_code."</h3>";	
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
			$msg->loadTemplate( 'mail/xShop_itemenquiryreply' );		
			$msg_body = $config_model['message'];
			// throw new \Exception("Error Processing Request".$msg_body);			
			$msg_body .= '<b>Item_name : </b>'.$item_name."<br>";
			$msg_body .= '<b>Item_code : </b>'.$item_code."<br>";
			$msg->setHTML('enquiry_item_reply_detail',$msg_body);
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
			throw new \Exception("Model_loaded at time of item");
		$this->load($id);
		$this['description']=$content;
		$this->save();
		return 'true';
	}

	function getItem($id){
		$this->load($id);				
		return $this;
	}

	function getItemCount($app_id){
		if($this->loaded())
			throw new \Exception("Item Model Loaded at Count All Item");
		if($app_id)
			$this->addCondition('application_id',$app_id);
		return $this->count()->getOne();
	}

	function getPublishCount($app_id){
		if($this->loaded())
			throw new \Exception("Item Model Loaded at Count Active Item");	
		if($app_id)
			$this->addCondition('application_id',$app_id);
		return $this->addCondition('is_publish',true)->count();
	}

	function getUnpublishCount($app_id){
		if($this->loaded())
			throw new \Exception("Item Model Loaded at Count Unactive Item");
		if($app_id)
			$this->addCondition('application_id',$app_id);
		return $this->addCondition('is_publish',false)->count();	
	}

	function applicationItems($app_id){
		if(!$app_id)
			$app_id=$this->api->recall('xshop_application_id');	

		$this->addCondition('application_id',$app_id);
		$this->tryLoadAny();
		return $this;
	}

	function getAssociatedCategories(){
		$associated_categories = $this->ref('xShop/CategoryItem')->addCondition('is_associate',true)->_dsql()->del('fields')->field('category_id')->getAll();
		return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($associated_categories)),false);
	}

	function getAssociatedCustomFields(){
		$associate_customfields= $this->ref('xShop/CategoryItemCustomFields')->addCondition('is_allowed',true)->_dsql()->del('fields')->field('customfield_id')->getAll();
		return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($associate_customfields)),false);
	}

	function addCustomField($customfield_id){
		$old_model = $this->add('xshop/Model_CategoryItemCustomFields');
		$old_model->addCondition('item_id',$this->id);
		$old_model->addCondition('customfield_id',$customfield_id);
		$old_model->addCondition('is_allowed',false);
		$old_model->tryLoadAny();
		if($old_model->loaded()){
			$old_model['is_allowed'] = true;
			$old_model->saveandUnload();
		}else{
			$cat_item_cf_model = $this->add('xshop/Model_CategoryItemCustomFields');
			$cat_item_cf_model['customfield_id'] = $customfield_id;
			$cat_item_cf_model['item_id'] = $this->id;
			$cat_item_cf_model['is_allowed'] = true;
			$cat_item_cf_model->saveandUnload();
		}	
	}
	
	function updateCustomField($item_id){

		
		$this->load($item_id);
		$category_item_model = $this->add('xShop/Model_CategoryItem');
		$category_item_model->addCondition('item_id',$item_id);
		foreach ($category_item_model as $junk) {
			$category_customfield_model = $this->add('xShop/Model_CategoryItemCustomFields');
			$category_customfield_model->addCondition('category_id',$junk['category_id']);
			
			foreach ($category_customfield_model as $junk) {
				$model = $this->add('xshop/Model_CategoryItemCustomFields');
				$model->addCondition('item_id',$item_id);
				$model->addCondition('customfield_id',$junk['customfield_id']);
				$model->tryLoadAny();
								
				$model['is_allowed'] = $junk['is_allowed'];
				$model->saveandUnload();
			}

		}
	}

	function specification($specification=null){
		$specs_assos = $this->add('xShop/Model_ItemSpecificationAssociation')->addCondition('item_id',$this->id);
		$specs_j = $specs_assos->join('xshop_specifications','specification_id');
		$specs_j->addField('name');

		if($specification){
			$specs_assos->addCondition('name',$specification);
			$specs_assos->tryLoadAny();
			if($specs_assos->loaded()) return $specs_assos['value'];
			return false;
		}

		return $specs_assos;
	}

	function getAmount($cutome_field_values_array, $qty, $rate_chart='retailer'){
		
		// 1. Check Custom Rate Charts
			/*
				Look $qty >= Qty of rate chart
				get the most field values matched
				having lesser selections of type any or say ...
				when max number of custom fields are having values other than any/%
			*/
		// 2. Custom Field Based Rate Change

		// 3. Quanitity Set

		// 4. Default Price * qty
	}

	function includeCustomeFieldValues($import_fields=array(),$join_type='inner'){
		$custom_fields_j = $this->join('xshop_category_item_customfields.item_id');
		$custom_fields_j->hasOne('xShop/CustomFields','customfield_id');
		$custom_fields_values_j = $custom_fields_j->join('xshop_custom_fields_value.itemcustomfiledasso_id');

		foreach ($import_fields as $key=>$value) {
			if(!is_numeric($key))
				$custom_fields_values_j->addField($key,$value);
			else
				$custom_fields_values_j->addField($value);
		}

	}

}	

