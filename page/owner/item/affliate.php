<?php

class page_xShop_page_owner_item_affliate extends page_xShop_page_owner_main{

	function init(){
		parent::init();

		$application_id=$this->api->recall('xshop_application_id');
		
		if(!$_GET['item_id'])
			return;
		$this->api->stickyGET('item_id');

		$item_model = $this->add('xShop/Model_Item')->load($_GET['item_id']);
		$type_model = $this->add('xShop/Model_AffiliateType')->addCondition('application_id',$application_id);
		$affiliate_model = $this->add('xShop/Model_Affiliate')->addCondition('application_id',$application_id);
		
		$form = $this->add('Form_Stacked');
		$dropdown = $form->addField('dropdown','affiliate_type')->setEmptyText('All');
		$dropdown->setModel($type_model);
		$form->addSubmit()->set('Filter');

		$grid=$this->add('Grid');
		if($_GET['affiliate_type_id']){
			$affiliate_model->addCondition('affiliatetype_id',$_GET['affiliate_type_id']);
		}

		//Selectable Form
		$selectable_form = $this->add('Form');
		$affiliate_field = $selectable_form->addField('hidden','affiliate')->set(json_encode($item_model->getAssociatedAffiliate()));
		$selectable_form->addSubmit('Update');

		//Set Model
		$grid->setModel($affiliate_model,array('logo_url','company_name','name','mobile_no'));
		$grid->addSelectable($affiliate_field);//Make Selectable

		if($selectable_form->isSubmitted()){
			$item_model->ref('xShop/ItemAffiliateAssociation')->_dsql()->set('is_active',0)->update();
			$item_affiliate_model = $this->add('xShop/Model_ItemAffiliateAssociation');
			$selected_affiliate = json_decode($selectable_form['affiliate'],true);
			foreach ($selected_affiliate as $affiliate_id) {
				$item_model->activeAffiliate($affiliate_id);		
			}
			$selectable_form->js(null,$this->js()->univ()->successMessage('Updated'))->execute();
		}

		$grid->addMethod('format_image_thumbnail',function($g,$f){
			$g->current_row_html[$f] = '<img style="height:40px;max-height:40px;" src="'.$g->current_row[$f].'"/>';
		});
		$grid->addFormatter('logo_url','image_thumbnail');
		$grid->addQuickSearch(array('company_name','name','mobile_no'));
		$grid->addPaginator($ipp=50);
		
		if($form->isSubmitted()){
			$form->js(null,$grid->js()->reload(array('affiliate_type_id'=>$form['affiliate_type'])))->execute();
		}
		
	}
}