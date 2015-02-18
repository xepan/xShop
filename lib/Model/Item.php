<?php

namespace xShop;

class Model_Item extends \Model_Table{
	public $table='xshop_items';
	public $table_alias='Item';

	function init(){
		parent::init();
		
		$this->hasOne('xShop/Application','application_id');
		$this->hasOne('xShop/MemberDetails','designer_id');
		$this->hasOne('xShop/Quotation','quotation_id');

		$this->hasOne('xHR/Employee','created_by_id')->defaultValue($this->api->current_employee->id)->system(true);
		//for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		// Basic Field
		$this->addField('name')->mandatory(true)->group('b~6')->sortable(true);
		$this->addField('sku')->PlaceHolder('Insert Unique Referance Code')->caption('Code')->hint('Place your unique Item code ')->mandatory(true)->group('b~3')->sortable(true);
		$this->addField('is_publish')->type('boolean')->defaultValue(true)->group('b~1')->sortable(true);
		$this->addField('is_party_publish')->type('boolean')->defaultValue(true)->group('b~2')->sortable(true);

		$this->addField('original_price')->type('money')->mandatory(true)->group('c~6~Basic Price');
		$this->addField('sale_price')->type('money')->mandatory(true)->group('c~6')->sortable(true);
		$this->addField('short_description')->type('text')->group('c~12');
		
		$this->addField('rank_weight')->defaultValue(0)->hint('Higher Rank Weight Item Display First')->mandatory(true)->group('d~4');
		$this->addField('created_at')->type('date')->defaultValue(date('Y-m-d'))->group('d~4');
		$this->addField('expiry_date')->type('date')->group('d~4');
		$this->addField('description')->type('text')->display(array('form'=>'RichText'))->group('z~12');
		
		// Price and Qtuanitity Management
		$this->addField('minimum_order_qty')->type('int')->group('e~3~Basic Quantity Options')->defaultValue(1);
		$this->addField('maximum_order_qty')->type('int')->group('e~3');
		$this->addField('qty_unit')->group('e~3')->defaultValue('pcs');
		$this->addField('qty_from_set_only')->type('boolean')->group('e~3')->defaultValue(false);
		
		//Item Allow Optins
		$this->addField('is_saleable')->type('boolean')->group('f~2~<i class=\'fa fa-cog\' > Item Allow Options</i>');
		$this->addField('is_downloadable')->type('boolean')->group('f~2');
		$this->addField('is_rentable')->type('boolean')->group('f~2');
		$this->addField('is_designable')->type('boolean')->group('f~2');
		$this->addField('is_template')->type('boolean')->defaultValue(false)->group('f~2');
		$this->addField('is_enquiry_allow')->type('boolean')->group('f~2');
		$this->addField('is_attachment_allow')->type('boolean')->group('f~2');
		
		//Item Display Options
		$this->addField('show_detail')->type('boolean')->defaultValue(true)->group('g~2~Item Display Options');
		$this->addField('show_price')->type('boolean')->group('g~2');
		$this->addField('is_visible_sold')->type('boolean')->hint('If Product remains visible after sold')->group('g~2');
		$this->hasOne('xShop/ItemOffer','offer_id')->group('g~2');
		$this->addField('offer_position')->setValueList(array('top:0;-left:0;'=>'TopLeft','top:0;-right:0;'=>'TopRight','bottom:0;-left:0;'=>'BottomLeft','bottom:0;-right:0;'=>'BottomRight'))->group('g~2');
		
		//Marked
		$this->addField('new')->type('boolean')->caption('New')->defaultValue(true)->group('h~3~<i class=\'fa fa-cog\' > Marked Options</i>');
		$this->addField('feature')->type('boolean')->caption('Featured')->group('h~3');
		$this->addField('latest')->type('boolean')->caption('Latest')->group('h~3');
		$this->addField('mostviewed')->type('boolean')->caption('Most Viewed')->group('h~3');

		//Enquiry Send To
		$this->addField('enquiry_send_to_admin')->type('boolean')->group('i~3~<i class=\'fa fa-cog\' > Enquiry Send To</i>');
		$this->addField('Item_enquiry_auto_reply')->caption('Item Enquiry Auto Reply')->type('boolean')->group('i~3');
		
		//Item Comment Options
		$this->addField('allow_comments')->type('boolean')->group('j~4~<i class=\'fa fa-cog\'> Item Comment Options</i>');
		$this->addField('comment_api')->setValueList(
														array('disqus'=>'Disqus')
														)->group('j~4');

		//Item Other Options	
		$this->addField('add_custom_button')->type('boolean')->group('k~2~<i class=\'fa fa-cog\'> Item Other Options</i>');
		$this->addField('custom_button_label')->group('k~4');
		$this->addField('custom_button_url')->placeHolder('subpage name like registration etc.')->group('k~6');
		$this->addField('theme_code')->hint('To club same theme code items in one')->group('k~6')->sortable(true);
		$this->addField('reference')->PlaceHolder('Any Referance')->hint('Use URL for external link')->group('k~6')->sortable(true);
		
		//Item Stock Options	
		$this->addField('negative_qty_allowed')->type('number');

		// Item WaterMark
		$this->add('filestore/Field_Image','watermark_image_id');
		$this->addField('watermark_text')->type('text')->group('o~5~bl');
		$this->addField('watermark_position')->enum(array('TopLeft','TopRight','BottomLeft','BottomRight','Center','Left Diagonal','Right Diagonal'));
		$this->addField('watermark_opacity');
		
		//Item Designs
		$this->addField('designs')->type('text')->group('o~5~bl');
	
		//Search String
		$this->addField('search_string')->type('text')->system(true);

		//Item SEO
		$this->addField('meta_title');
		$this->addField('meta_description')->type('text');
		$this->addField('tags')->type('text')->PlaceHolder('Comma Separated Value');
		
		$this->hasMany('xShop/CategoryItem','item_id');
		$this->hasMany('xShop/ItemAffiliateAssociation','item_id');
		$this->hasMany('xShop/ItemImages','item_id');
		$this->hasMany('xShop/Attachments','item_id');
		$this->hasMany('xShop/ItemEnquiry','item_id');
		$this->hasMany('xShop/OrderDetails','item_id');
		$this->hasMany('xShop/ItemSpecificationAssociation','item_id');
		$this->hasMany('xShop/CustomFieldValueFilterAssociation','item_id');
		$this->hasMany('xShop/ItemCustomFieldAssos','item_id');
		$this->hasMany('xShop/ItemReview','item_id');
		$this->hasMany('xShop/ItemMemberDesign','item_id');
		$this->hasMany('xShop/ItemDepartmentAssociation','item_id');

		$this->hasMany('xShop/QuantitySet','item_id');
		$this->hasMany('xShop/CustomRate','item_id');

		$this->addExpression('theme_code_group_expression')->set('(IF(ISNULL('.$this->table_alias.'.theme_code),'.$this->table_alias.'.id,'.$this->table_alias.'.theme_code))');
			
		$this->addHook('beforeSave',$this);
		$this->addHook('afterInsert',$this);
		$this->addHook('beforeDelete',$this);
		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave($m){
		// todo checking SKU value must be unique
		$item_old=$this->add('xShop/Model_Item');
		if($this->loaded())
			$item_old->addCondition('id','<>',$this->id);
		$item_old->tryLoadAny();

		if($item_old['sku'] == $this['sku'])
			throw $this->exception('Item Code is Allready Exist','Growl')->setField('sku');

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

		if(($this->dirty['sale_price'] or $this->dirty['original_price']) and $this['id'] > 0){
			$this->updateDefaultQuantitySet();
		}
		// TOOOOOOOOOOOODOOOOOOOOOOO check for the uodate item designer if ==================
		// if($this->loaded() and $this->dirty['designer_id']){	
		// 	$this->updateItemDesigner();
		// }
	}

	function afterInsert($obj,$new_item_id){
		$new_item =  $this->add('xShop/Model_Item')->load($new_item_id);
		$new_item->updateDefaultQuantitySet();

		if(!$new_item['designer_id']) return;
		$new_item->updateItemDesigner();
	}

	function updateDefaultQuantitySet(){
		//Default Qty Set
		$qty_set_model = $this->ref('xShop/QuantitySet')->addCondition('is_default',true)->tryLoadAny();
		$qty_set_model['old_price'] = $this['original_price'];
		$qty_set_model['price'] = $this['sale_price'];
		$qty_set_model['qty'] = 1;
		$qty_set_model['name'] = "Default";
		$qty_set_model['is_default'] = true;
		$qty_set_model->save();
	}

	function updateItemDesigner(){
		if(!$this->loaded())
			return;
		// if designable add as with admin => member's design too
		$designer = $this->add('xShop/Model_MemberDetails');
		$designer->addCondition('id',$this['designer_id']);
		$designer->tryLoadAny();
		$target = $this->item = $this->add('xShop/Model_ItemMemberDesign');
		$target['item_id'] = $this['id'];
		$target['member_id'] = $designer['id'];
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
		$m->ref('xShop/ItemCustomFieldAssos')->deleteAll();	
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
		$associate_customfields= $this->ref('xShop/ItemCustomFieldAssos')->addCondition('is_active',true)->_dsql()->del('fields')->field('customfield_id')->getAll();
		return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($associate_customfields)),false);
		// return $associate_customfields;
	}

	function customFields(){
		return $this->add('xShop/Model_CustomFields')->addCondition('id',$this->getAssociatedCustomFields());
	}

	function getAssociatedAffiliate(){
		$associated_affiliate = $this->ref('xShop/ItemAffiliateAssociation')->addCondition('is_active',true)->_dsql()->del('fields')->field('affiliate_id')->getAll();
		return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($associated_affiliate)),false);
	}

	function addCustomField($customfield_id){
		$old_model = $this->add('xshop/Model_ItemCustomFieldAssos');
		$old_model->addCondition('item_id',$this->id);
		$old_model->addCondition('customfield_id',$customfield_id);
		$old_model->addCondition('is_allowed',false);
		$old_model->tryLoadAny();
		if($old_model->loaded()){
			$old_model['is_allowed'] = true;
			$old_model->saveandUnload();
		}else{
			$cat_item_cf_model = $this->add('xshop/Model_ItemCustomFieldAssos');
			$cat_item_cf_model['customfield_id'] = $customfield_id;
			$cat_item_cf_model['item_id'] = $this->id;
			$cat_item_cf_model['is_allowed'] = true;
			$cat_item_cf_model->saveandUnload();
		}	
	}
	
	function updateCustomField($item_id=null){
		
		$category_item_model = $this->add('xShop/Model_CategoryItem');
		$category_item_model->addCondition('item_id',$this['id']);
		$category_item_model->tryLoadAny();

		foreach ($category_item_model as $junk) {
			$category_customfield_model = $this->add('xShop/Model_ItemCustomFieldAssos');
			$category_customfield_model->addCondition('category_id',$junk['category_id']);
			
			foreach ($category_customfield_model as $junk) {
				$model = $this->add('xshop/Model_ItemCustomFieldAssos');
				$model->addCondition('item_id',$this['id']);
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

	/*
		$custom_field_values=array(
			'Color'=>'Red',
			'Size'=>'9'
		)	
	*/

	function  getPrice($custom_field_values_array, $qty, $rate_chart='retailer'){
		$quantitysets = $this->ref('xShop/QuantitySet')->setOrder(array('custom_fields_conditioned desc','qty desc','is_default asc'));

		foreach ($quantitysets as $junk) {
			// check if all conditioned match AS WELL AS qty
			$cond = $quantitysets->ref('xShop/QuantitySetCondition');
			$cond->title_field = 'name';
			foreach ($cond as $junk) {
				$value = explode("::",$cond['custom_field_value']);
				$value = $value[1];
				$value = trim($value);
				// echo $custom_field_values_array[$cond['customfield']] ." != ". $value;
				if($custom_field_values_array[$cond['customfield']] != $value)
					continue 2;
			}

			if($qty < $quantitysets['qty']) continue;
			break;
		}

		return array('original_price'=>$quantitysets['old_price']?:$quantitysets['price'],'sale_price'=>$quantitysets['price']);
		// return array('original_price'=>rand(1000,9999),'sale_price'=>rand(100,999));

			// return array default_price
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

	function getAmount($custom_field_values_array, $qty, $rate_chart='retailer'){
		$price = $this->getPrice($custom_field_values_array, $qty, $rate_chart);
		return array('original_amount'=>$price['original_price'] * $qty,'sale_amount'=>$price['sale_price'] * $qty);

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

	function activeAffiliate($affiliate_id){
		if($this->loaded() and !$affiliate_id)
			throw new \Exception("Item Model Must be Loaded ");

		$aff = $this->add('xShop/Model_Affiliate')->tryLoad($affiliate_id);

		$old_model = $this->add('xShop/Model_ItemAffiliateAssociation');
		$old_model->addCondition('item_id',$this->id);
		$old_model->addCondition('affiliate_id',$aff['id']);
		$old_model->addCondition('is_active',false);		
		$old_model->tryLoadAny();
		if($old_model->loaded()){
			$old_model['is_active'] = true;
			$old_model->saveandUnload();
		}else{
			$item_aff_model = $this->add('xShop/Model_ItemAffiliateAssociation');
			$item_aff_model['item_id'] = $this->id;
			$item_aff_model['affiliate_id'] = $affiliate_id;
			$item_aff_model['affiliatetype_id'] = $aff['affiliatetype_id'];
			$item_aff_model['is_active'] = true;
			$item_aff_model->saveandUnload();
		}
		
	}

	function getQtySet(){
		if(!$this->loaded())
			throw new \Exception("Item Model Must be Loaded");
		/*
		qty_set: {
				Values:{
				value:{
					name:'Default',
					qty:1,
					old_price:100,
					price:90,
					conditions:{
							custom_fields_condition_id:'custom_field_value_id'
						}
				}
			}
		},
		*/
		$qty_set_array = array();
		//load Associated Quantity Set
			$qty_set_model = $this->ref('xShop/QuantitySet');
			//foreach qtySet get all Condition
				foreach ($qty_set_model as $junk){
					$qty_set_array[$qty_set_model['id']]['name'] = $qty_set_model['name'];
					$qty_set_array[$qty_set_model['id']]['qty'] = $qty_set_model['qty'];
					$qty_set_array[$qty_set_model['id']]['old_price'] = $qty_set_model['old_price'];
					$qty_set_array[$qty_set_model['id']]['price'] = $qty_set_model['price'];
					$qty_set_array[$qty_set_model['id']]['conditions'] = array();
					
					//Load QtySet Condition Model
					$condition_model =$this->add('xShop/Model_QuantitySetCondition')->addCondition('quantityset_id',$qty_set_model['id']);
						//foreach condition 
						foreach ($condition_model as $junk) {
							$single_condition_array = array();
							$single_condition_array[$condition_model['customfield']] = $condition_model->ref('custom_field_value_id')->get('name');
							$qty_set_array[$qty_set_model['id']]['conditions']= array_merge($qty_set_array[$qty_set_model['id']]['conditions'], $single_condition_array);
						}
				}

		return $qty_set_array;		
	}

	function getBasicCartOptions(){
				//Get All Item Associated Custom Field
		$custom_filed_array = array();
		$custom_fields = $this->getAssociatedCustomFields();
		foreach ($custom_fields as $custom_field_id){
			$cf_model = $this->add('xShop/Model_CustomFields')->load($custom_field_id);
			$cf_value_array = $cf_model->getCustomValue($this->id);
			$custom_filed_array[$cf_model['name']] = array(
													'type'=>$cf_model['type'],
													'values' => $cf_value_array
												);
		}

		//Get All Item Qnatity Set 
		$qty_set_array = array();
		$qty_set_array = $this->getQtySet();

		$options = array();

		$options['item_id'] = $this->id;
		$options['qty_from_set_only'] = $this['qty_from_set_only'];
		$options['qty_set'] = $qty_set_array;
		$options['custom_fields'] = $custom_filed_array;
		return $options;
	}

	// function submit(){
	// 	return "dsfsfdsdF";
	// }

	function getAssociatedDepartment(){
		if(!$this->loaded())
			throw new \Exception("Item Model Must be Loaded",'Department Association');
			
		$associated_department = $this->ref('xShop/ItemDepartmentAssociation')->addCondition('is_active',true)->_dsql()->del('fields')->field('department_id')->getAll();
		return iterator_to_array(new \RecursiveIteratorIterator(new \RecursiveArrayIterator($associated_department)),false);
	}

	function associatedDepartments(){
		return $this->add('xProduction/Model_Department')->addCondition('id',$this->getAssociatedDepartment());
	}


}	

