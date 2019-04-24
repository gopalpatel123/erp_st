<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\View\Helper\BarcodeHelper;
/**
 * Items Controller
 *
 * @property \App\Model\Table\ItemsTable $Items
 *
 * @method \App\Model\Entity\Item[] paginate($object = null, array $settings = [])
 */
class ItemsController extends AppController
{

	public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['getItem']);
    }

	function arrayToCsvDownload($array, $filename = "export.csv", $delimiter=";") 
	{
		// open raw memory as file so no temp files needed, you might run out of memory though
		$f = fopen('php://memory', 'w'); 
		// loop over the input array
		foreach ($array as $line) { 
			// generate csv lines from the inner arrays
			fputcsv($f, $line, $delimiter); 
		}
		// reset the file pointer to the start of the file
		fseek($f, 0);
		// tell the browser it's going to be a csv file
		header('Content-Type: application/csv');
		// tell the browser we want to save it instead of displaying it
		header('Content-Disposition: attachment; filename="'.$filename.'";');
		// make php send the generated csv lines to the browser
		fpassthru($f);
	}
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$search=$this->request->query('search');
		$this->paginate = [
            'contain' => ['Units', 'StockGroups'],
			'limit' => 100
        ];
        $items = $this->paginate($this->Items->find()->where(['Items.company_id'=>$company_id])->where([
		'OR' => [
            'Items.name LIKE' => '%'.$search.'%',
			//...
			 'Items.item_code LIKE' => '%'.$search.'%',	
			 //...
			 'Items.hsn_code LIKE' => '%'.$search.'%',
			 
			'Units.name LIKE' => '%'.$search.'%'
		 ]]));

        $this->set(compact('items','search'));
        $this->set('_serialize', ['items']);
    }
	
	
	 
    /**
     * View method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $item = $this->Items->get($id, [
            'contain' => ['Units', 'StockGroups']
        ]);

        $this->set('item', $item);
        $this->set('_serialize', ['item']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
        $item = $this->Items->newEntity();
		$company_id  = $this->Auth->User('session_company_id');
		$location_id = $this->Auth->User('session_location_id');
		$this->request->data['company_id'] =$company_id;
		if ($this->request->is('post')) {
			$item = $this->Items->patchEntity($item, $this->request->getData());
			$quantity = $this->request->data['quantity'];

			$gst_type = $item->kind_of_gst;
			if($gst_type=='fix')
			{
				$first_gst_figure_id        = $item->first_gst_figure_id;
				$item->second_gst_figure_id = $first_gst_figure_id;
				$item->gst_amount           = 0;
			}
			if($item->barcode_decision==1){ 
				if($company_id==1){
					$random_num=rand(1000000,10000000);
					$item->item_code='SAREE'.$random_num;
				}else if($company_id==2){
					$random_num=rand(1000000,10000000);
					$item->item_code='TEXTI'.$random_num;
				}else if($company_id==3){
					$random_num=rand(1000000,10000000);
					$item->item_code='GARME'.$random_num;
				}
				$item->item_code=strtoupper($item->item_code);
				$data_to_encode = $item->item_code;
			}else{
				$item->item_code=strtoupper($item->provided_item_code);
				$data_to_encode = strtoupper($item->provided_item_code);
			}
			$item->sales_rate_update_on = $this->Auth->User('session_company')->books_beginning_from;
			//pr($item->item_code);
			//pr($data_to_encode); exit;
            if ($this->Items->save($item))
			{
				$barcode = new BarcodeHelper(new \Cake\View\View());
				
					
				// Generate Barcode data
				$barcode->barcode();
				$barcode->setType('C128');
				$barcode->setCode($data_to_encode);
				$barcode->setSize(20,100);
				$barcode->hideCodeType('N');
					
				// Generate filename     
				$file = 'img/barcode/'.$item->id.'.png';
					
				// Generates image file on server    
				$barcode->writeBarcodeFile($file);
			
			
				$transaction_date=$this->Auth->User('session_company')->books_beginning_from;
				if($quantity>0)
				{
					$itemLedger = $this->Items->ItemLedgers->newEntity();
					$itemLedger->item_id            = $item->id;
					$itemLedger->transaction_date   = date("Y-m-d",strtotime($transaction_date));
					$itemLedger->quantity           = $this->request->data['quantity'];
					$itemLedger->rate               = $this->request->data['rate'];
					$itemLedger->amount             = $this->request->data['amount'];
					$itemLedger->status             = 'in';
					$itemLedger->is_opening_balance = 'yes';
					$itemLedger->company_id         = $company_id;
					$itemLedger->location_id        = $location_id;
					$this->Items->ItemLedgers->save($itemLedger);
				}
				
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'add']);
            }
            $this->Flash->error(__('The item could not be saved. Please, try again.'));
        }
        $units = $this->Items->Units->find('list')->where(['company_id'=>$company_id]);
        $stockGroups = $this->Items->StockGroups->find('list')->where(['company_id'=>$company_id]);
        $shades = $this->Items->Shades->find('list')->where(['company_id'=>$company_id]);
        $sizes = $this->Items->Sizes->find('list')->where(['company_id'=>$company_id]);
        $gstFigures = $this->Items->GstFigures->find('list')->where(['GstFigures.company_id'=>$company_id]);
        $this->set(compact('item', 'units', 'stockGroups','sizes','shades','gstFigures'));
        $this->set('_serialize', ['item']);
    }
	
	public function appIndex()
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$search=$this->request->query('search');
		$this->paginate = [
            'contain' => ['Units', 'StockGroups'],
			'limit' => 100
        ];
        $items = $this->paginate($this->Items->find()->where(['Items.company_id'=>$company_id,'sales_for IN'=>['online/offline','online']])->where([
		'OR' => [
            'Items.name LIKE' => '%'.$search.'%',
			//...
			 'Items.item_code LIKE' => '%'.$search.'%',	
			 //...
			 'Items.hsn_code LIKE' => '%'.$search.'%',
			 
			'Units.name LIKE' => '%'.$search.'%'
		 ]]));

        $this->set(compact('items','search'));
        $this->set('_serialize', ['items']);
    }

	
	public function appAdd(){
		
		$this->viewBuilder()->layout('index_layout');
		$item = $this->Items->newEntity();
		$company_id  = $this->Auth->User('session_company_id');
		$location_id = $this->Auth->User('session_location_id');
		$this->request->data['company_id'] =$company_id;
		if ($this->request->is('post')) {
			$item = $this->Items->patchEntity($item, $this->request->getData());
			$quantity = $this->request->data['quantity'];
			$image_url=$this->request->getData('image_url');
			$gst_type = $item->kind_of_gst;
			if($gst_type=='fix')
			{
				$first_gst_figure_id        = $item->first_gst_figure_id;
				$item->second_gst_figure_id = $first_gst_figure_id;
				$item->gst_amount           = 0;
			}
			if($item->barcode_decision==1){
				$item->item_code=strtoupper(uniqid());
				$data_to_encode = $item->item_code;
			}else{
				$item->item_code=strtoupper($item->provided_item_code);
				$data_to_encode = strtoupper($item->provided_item_code);
			}
			$item->sales_rate_update_on = $this->Auth->User('session_company')->books_beginning_from;
			//$sub_category_id = $item->sub_category_id;
			//$stock_group_id = $item->stock_group_id;
			//$item->stock_group_id = $sub_category_id;
			//$item->sub_category_id = $stock_group_id;
          // pr($this->request->getData('item_image_rows'));exit;
		 // pr($this->request->getData('item_image_rows'));exit;
		 //pr($item);exit;
			if ($this->Items->save($item))
			{
				if(!empty($this->request->getData('item_image_rows'))){
					foreach($this->request->getData('item_image_rows') as $item_image_row){ 
						if(!empty($item_image_row['image_path']['tmp_name'])){
							$item_errors=$item_image_row['image_path']['error'];
							if(empty($item_errors))
							{
								$item_extt=explode('/',$item_image_row['image_path']['type']);
								$item_item_images='itemrows'.time().'.'.$item_extt[1];
							}
							
							$keyname1 = 'ItemRows/'.$item->id.'/'.$item_item_images;
							$this->AwsFile->putObjectFile($keyname1,$item_image_row['image_path']['tmp_name'],$item_image_row['image_path']['type']);
							$query = $this->Items->ItemImageRows->query();
							$query->insert(['item_id', 'image_path','status'])
							->values([
										'item_id' => $item->id,
										'image_path' => $keyname1,
										'status' => 'Active'
										]);
							$query->execute(); 	
						}
						
					}
				}
				
				if(!empty($image_url['tmp_name'])){
						$item_error=$image_url['error'];
						if(empty($item_error))
							{
								$item_ext=explode('/',$image_url['type']);
								$item_item_image='item'.time().'.'.$item_ext[1];
							}
				
						$keyname = 'Item/'.$item->id.'/'.$item_item_image;
						$this->AwsFile->putObjectFile($keyname,$image_url['tmp_name'],$image_url['type']);
				
					$query = $this->Items->query();
					$query->update()
					->set([
						'image_url' => $keyname
						])
					->where(['id' => $item->id])
					->execute();
				}
				
				
				$barcode = new BarcodeHelper(new \Cake\View\View());
				
					
				// Generate Barcode data
				$barcode->barcode();
				$barcode->setType('C128');
				$barcode->setCode($data_to_encode);
				$barcode->setSize(20,100);
				$barcode->hideCodeType('N');
					
				// Generate filename     
				$file = 'img/barcode/'.$item->id.'.png';
					
				// Generates image file on server    
				$barcode->writeBarcodeFile($file);
			
			
				$transaction_date=$this->Auth->User('session_company')->books_beginning_from;
				if($quantity>0)
				{
					$itemLedger = $this->Items->ItemLedgers->newEntity();
					$itemLedger->item_id            = $item->id;
					$itemLedger->transaction_date   = date("Y-m-d",strtotime($transaction_date));
					$itemLedger->quantity           = $this->request->data['quantity'];
					$itemLedger->rate               = $this->request->data['rate'];
					$itemLedger->amount             = $this->request->data['amount'];
					$itemLedger->status             = 'in';
					$itemLedger->is_opening_balance = 'yes';
					$itemLedger->company_id         = $company_id;
					$itemLedger->location_id        = $location_id;
					$this->Items->ItemLedgers->save($itemLedger);
				}
				
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'appAdd']);
            }
			
            $this->Flash->error(__('The item could not be saved. Please, try again.'));
        }
        
        $units = $this->Items->Units->find('list')->where(['company_id'=>$company_id]);
        $stockGroups = $this->Items->StockGroups->find()->where(['company_id'=>$company_id,'StockGroups.is_status'=>'app']);
        //$stockGroups = $this->Items->StockGroups->ParentStockGroups->find('list')->where(['company_id'=>$company_id,'ParentStockGroups.is_status'=>'app','ParentStockGroups.parent_id IS NULL']);
		
		 $options=[];
		$totSize=0;
		foreach($stockGroups as $stockgroup){
			$stockgroupsIds = $this->Items->StockGroups
							->find('children', ['for' => $stockgroup->id])
							->find('all');
			$totSize=(sizeof($stockgroupsIds->toArray()));
			if($totSize==0){
				$options[]=['text'=>$stockgroup->name,'value'=>$stockgroup->id];
			}
			
		} 
		//pr($options);exit;
        $shades = $this->Items->Shades->find('list')->where(['company_id'=>$company_id]);
        $brands = $this->Items->AppBrands->find('list')->where(['status'=>'Active']);
        $sizes = $this->Items->Sizes->find('list')->where(['company_id'=>$company_id]);
        $gstFigures = $this->Items->GstFigures->find('list')->where(['GstFigures.company_id'=>$company_id]);
        $this->set(compact('item', 'units', 'stockGroups','sizes','shades','gstFigures','options','brands'));
        $this->set('_serialize', ['item']);
	}
	
	
	public function appEdit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $item = $this->Items->get($id, [
            'contain' => ['ItemLedgers' => function($q) {
				return $q->where(['ItemLedgers.is_opening_balance'=>'yes']);
			},'ItemImageRows']
        ]);
        $itemPurchaseData=$this->Items->ItemLedgers->find()->where(['item_id'=>$id,'status'=>'In','grn_id > '=>0])->select('rate')->first();
        $itemPurchaseRate=@$itemPurchaseData->rate;
		$company_id=$this->Auth->User('session_company_id');
		$location_id = $this->Auth->User('session_location_id');
		
        if ($this->request->is(['patch', 'post', 'put'])) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
			$image_url=$this->request->getData('image_url');
			$image_url_exist=$this->request->getData('image_url_exist');
			
			
				if(!empty($image_url['tmp_name']))
				{
					$this->request->data['image_url']=$image_url;			 
				}
				else
				{
					if(!empty($this->request->data['image_url_exist']))
					{
						$item->image_url=$image_url_exist;	
					}
					else
					{
						$item->image_url='';
					}
				}
			
			
			
			$gst_type = $item->kind_of_gst;
			if($gst_type=='fix')
			{
				$first_gst_figure_id        = $item->first_gst_figure_id;
				$item->second_gst_figure_id = $first_gst_figure_id;
				$item->gst_amount           = 0;
			}
			$item->sales_rate_update_on = $this->Auth->User('session_company')->books_beginning_from;
			//pr($item);exit;
			if ($this->Items->save($item)) {
				//pr($this->request->getData('item_image_rows'));exit;
				if(!empty($this->request->getData('item_image_rows'))){
						$this->Items->ItemImageRows->deleteAll(['item_id'=>$item->id]);
					foreach($this->request->getData('item_image_rows') as $item_image_row){ 
					
						$image_path_exist = $item_image_row['image_path_exist'];
						if(!empty($item_image_row['image_path']['tmp_name'])){
							$item_errors=$item_image_row['image_path']['error'];
							if(empty($item_errors))
							{
								$item_extt=explode('/',$item_image_row['image_path']['type']);
								$item_item_images='itemrows'.time().'.'.$item_extt[1];
							}
							
							$keyname1 = 'ItemRows/'.$item->id.'/'.$item_item_images;
							$this->AwsFile->putObjectFile($keyname1,$item_image_row['image_path']['tmp_name'],$item_image_row['image_path']['type']);
							if(!empty($image_path_exist)){
								$this->AwsFile->deleteMatchingObjects($image_path_exist);
							}
						}else{
							
							$keyname1 = $image_path_exist;
							
						}
					
						$query = $this->Items->ItemImageRows->query();
						$query->insert(['item_id', 'image_path','status'])
						->values([
									'item_id' => $item->id,
									'image_path' => $keyname1,
									'status' => 'Active'
									]);
						$query->execute(); 	
					}
				}
				
				if(!empty($image_url['tmp_name'])){
						$item_error=$image_url['error'];
						if(empty($item_error))
							{
								$item_ext=explode('/',$image_url['type']);
								$item_item_image='item'.time().'.'.$item_ext[1];
							}
						if(empty($files['error']))
						{
							$keyname = 'Item/'.$item->id.'/'.$item_item_image;
							$this->AwsFile->putObjectFile($keyname,$image_url['tmp_name'],$image_url['type']);
							if(!empty($image_url_exist)){
								$this->AwsFile->deleteMatchingObjects($image_url_exist);
							}
							
						}
					$query = $this->Items->query();
					$query->update()
					->set([
						'image_url' => $keyname
						])
					->where(['id' => $item->id])
					->execute();
				}
				
				
				
				
				if($item->quantity>0)
				{
					$transaction_date=$this->Auth->User('session_company')->books_beginning_from;
					$query_delete = $this->Items->ItemLedgers->query();
						$query_delete->delete()
						->where(['item_id' => $id,'is_opening_balance'=>'yes','company_id'=>$company_id])
						->execute();
						
					$itemLedger = $this->Items->ItemLedgers->newEntity();
					$itemLedger->item_id            = $item->id;
					$itemLedger->transaction_date   = date("Y-m-d",strtotime($transaction_date));
					$itemLedger->quantity           = $this->request->data['quantity'];
					$itemLedger->rate               = $this->request->data['rate'];
					$itemLedger->amount             = $this->request->data['amount'];
					$itemLedger->status             = 'in';
					$itemLedger->is_opening_balance = 'yes';
					$itemLedger->company_id         = $company_id;
					$itemLedger->location_id        = $location_id;
					$this->Items->ItemLedgers->save($itemLedger);
				}
				$this->Flash->success(__('The item has been saved.'));
				

                return $this->redirect(['action' => 'appIndex']);
            }
			else
			{ 
				$this->Flash->error(__('The item could not be saved. Please, try again.'));
			}
        }
        $units = $this->Items->Units->find('list')->where(['company_id'=>$company_id]);
         $stockGroups = $this->Items->StockGroups->find()->where(['company_id'=>$company_id,'StockGroups.is_status'=>'app']); 
		
		// $stockGroups = $this->Items->StockGroups->ParentStockGroups->find('list')->where(['company_id'=>$company_id,'ParentStockGroups.is_status'=>'app','ParentStockGroups.parent_id IS NULL']);
		 
		  //$stockGroupss = $this->Items->StockGroups->ParentStockGroups->find()->where(['company_id'=>$company_id,'ParentStockGroups.is_status'=>'app','ParentStockGroups.parent_id IS NULL','id'=>$item->stock_group_id]);
		
		 $options=[];
		$totSize=0;
		foreach($stockGroups as $stockgroup){
			$stockgroupsIds = $this->Items->StockGroups
							->find('children', ['for' => $stockgroup->id])
							->find('all');
			$totSize=(sizeof($stockgroupsIds->toArray()));
			if($totSize==0){
				$options[]=['text'=>$stockgroup->name,'value'=>$stockgroup->id];
			}
			
		} 
		//pr($stockgroupsIds->toArray());exit;
		 $brands = $this->Items->AppBrands->find('list')->where(['status'=>'Active']);
		$shades = $this->Items->Shades->find('list')->where(['company_id'=>$company_id]);
        $sizes = $this->Items->Sizes->find('list')->where(['company_id'=>$company_id]);
		$gstFigures = $this->Items->GstFigures->find('list')->where(['GstFigures.company_id'=>$company_id]);
        $this->set(compact('item', 'units', 'stockGroups','sizes','shades','gstFigures','itemPurchaseRate','options','brands'));
        $this->set('_serialize', ['item']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
		$this->viewBuilder()->layout('index_layout');
        $item = $this->Items->get($id, [
            'contain' => ['ItemLedgers' => function($q) {
				return $q->where(['ItemLedgers.is_opening_balance'=>'yes']);
			}]
        ]);
        $itemPurchaseData=$this->Items->ItemLedgers->find()->where(['item_id'=>$id,'status'=>'In','grn_id > '=>0])->select('rate')->first();
        $itemPurchaseRate=@$itemPurchaseData->rate;
		$company_id=$this->Auth->User('session_company_id');
		$location_id = $this->Auth->User('session_location_id');
		
        if ($this->request->is(['patch', 'post', 'put'])) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
			
			$gst_type = $item->kind_of_gst;
			if($gst_type=='fix')
			{
				$first_gst_figure_id        = $item->first_gst_figure_id;
				$item->second_gst_figure_id = $first_gst_figure_id;
				$item->gst_amount           = 0;
			}
			$item->sales_rate_update_on = $this->Auth->User('session_company')->books_beginning_from;
			$sub_category_id = $item->sub_category_id;
			$stock_group_id = $item->stock_group_id;
			$item->stock_group_id = $sub_category_id;
			$item->sub_category_id = $stock_group_id;
			if ($this->Items->save($item)) {
				if($item->quantity>0)
				{
					$transaction_date=$this->Auth->User('session_company')->books_beginning_from;
					$query_delete = $this->Items->ItemLedgers->query();
						$query_delete->delete()
						->where(['item_id' => $id,'is_opening_balance'=>'yes','company_id'=>$company_id])
						->execute();
						
					$itemLedger = $this->Items->ItemLedgers->newEntity();
					$itemLedger->item_id            = $item->id;
					$itemLedger->transaction_date   = date("Y-m-d",strtotime($transaction_date));
					$itemLedger->quantity           = $this->request->data['quantity'];
					$itemLedger->rate               = $this->request->data['rate'];
					$itemLedger->amount             = $this->request->data['amount'];
					$itemLedger->status             = 'in';
					$itemLedger->is_opening_balance = 'yes';
					$itemLedger->company_id         = $company_id;
					$itemLedger->location_id        = $location_id;
					$this->Items->ItemLedgers->save($itemLedger);
				}
				$this->Flash->success(__('The item has been saved.'));
				

                return $this->redirect(['action' => 'index']);
            }
			else
			{ 
				$this->Flash->error(__('The item could not be saved. Please, try again.'));
			}
        }
        $units = $this->Items->Units->find('list')->where(['company_id'=>$company_id]);
        $stockGroups = $this->Items->StockGroups->find('list')->where(['company_id'=>$company_id]);
		$shades = $this->Items->Shades->find('list')->where(['company_id'=>$company_id]);
        $sizes = $this->Items->Sizes->find('list')->where(['company_id'=>$company_id]);
		$gstFigures = $this->Items->GstFigures->find('list')->where(['GstFigures.company_id'=>$company_id]);
        $this->set(compact('item', 'units', 'stockGroups','sizes','shades','gstFigures','itemPurchaseRate'));
        $this->set('_serialize', ['item']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
		
        $this->request->allowMethod(['post', 'delete']);
        $item = $this->Items->get($id);
        if ($this->Items->delete($item)) {
            $this->Flash->success(__('The item has been deleted.'));
        } else {
            $this->Flash->error(__('The item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
	
	public function uplodeCsv()
    {
		$this->viewBuilder()->layout('index_layout');
        $uplode_csv = $this->Items->newEntity();
		
		if ($this->request->is('post')) 
		{
			
			$csv = $this->request->data['csv'];
			if(!empty($csv['tmp_name']))
			{
				
				$ext = substr(strtolower(strrchr($csv['name'], '.')), 1); //get the extension 
				
				$arr_ext = array('csv'); 									   
				if (in_array($ext, $arr_ext)) 
				{
								
					$f = fopen($csv['tmp_name'], 'r') or die("ERROR OPENING DATA");
					$batchcount=0;
					$records=0;
					while (($line = fgetcsv($f, 4096, ';')) !== false) 
					{
						$numcols = count($line);
						$test[]=$line;
						++$records;
					}
					foreach($test as $test1)
					{ 
					
						 $data = explode(",",$test1[0]);
						 $item = $this->Items->newEntity();
						 $item->name           = $data[0];
						 $item->item_code      = $data[1]; 
						 $item->hsn_code       = $data[2];
						 $item->unit_id        = $data[3];
						 $item->stock_group_id = $data[4];
						 $item->company_id     = $data[5];
						 $this->Items->save($item);
					} 
					fclose($f);
					$records;

					move_uploaded_file($csv['tmp_name'], WWW_ROOT . '/csv/csv_'.date("d-m-Y").'.'.$ext);
				}
			   
				
			}
		}
        $this->set(compact('uplode_csv'));
        $this->set('_serialize', ['uplode_csv']);
    }
	
	public function checkUnique($provided_item_code){
		
		$company_id=$this->Auth->User('session_company_id');
		$itemcode = $this->Items->find()->where(['Items.item_code'=>$provided_item_code,'Items.company_id'=>$company_id]);
		
		$data['is_unique'] = "yes";
		echo json_encode($data);
		
		exit;
	}
	
	public function generateBarcode(){
		
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$item = $this->Items->newEntity();
		if ($this->request->is('put','post','patch')) {
			$item_name = $this->Items->patchEntity($item, $this->request->getData());
			
			//$itemids=array_filter($item_name->item_name);
			$encodeitemids=json_encode($item_name);
			return $this->redirect(['action' => 'viewBarcode', $encodeitemids]);
			
		}
			
		$items = $this->Items->find()
			->where(['Items.company_id'=>$company_id]);
					
		$itemOptions=[];
		foreach($items as $item)
		{
			$itemOptions[]=['text' =>$item->item_code.' '.$item->name, 'value' => $item->id, 'gst_figure_tax_name'=>@$item->gst_figure->name];
		}
        $this->set(compact('items','item','itemOptions'));
        $this->set('_serialize', ['item']);
	}
	
	public function generateBarcodeForCartoon(){
		
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$item = $this->Items->newEntity();
		if ($this->request->is('put','post','patch')) {
			$item_name = $this->Items->patchEntity($item, $this->request->getData());
			
			//$itemids=array_filter($item_name->item_name);
			$encodeitemids=json_encode($item_name);
			return $this->redirect(['action' => 'viewBarcodeForCartoon', $encodeitemids]);
			
		}
			
		$items = $this->Items->find()
			->where(['Items.company_id'=>$company_id]);
					
		$itemOptions=[];
		foreach($items as $item)
		{
			$itemOptions[]=['text' =>$item->item_code.' '.$item->name, 'value' => $item->id, 'gst_figure_tax_name'=>@$item->gst_figure->name];
		}
        $this->set(compact('items','item','itemOptions'));
        $this->set('_serialize', ['item']);
	}
	
	public function viewBarcode($encodeitemids=null){
		$items=json_decode($encodeitemids);
		
		$this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$item_barcodes=[];
		
		foreach($items->item_name as $item){
			for($q=0; $q<$item->quantity; $q++){
				$item_barcodes[] = $this->Items->get($item->item_id, [
					'contain'=>['Shades','Sizes','StockGroups'=>['ParentStockGroups']]
				]);
			}
			
		}
		//pr($item_barcodes); exit;
        $this->set(compact('item_barcodes','company_id'));
        $this->set('_serialize', ['items']);
	}

	public function viewBarcodeForCartoon($encodeitemids=null){
		$items=json_decode($encodeitemids);
		$no_of_cartoon=$items->no_of_cartoon;
		$no_of_quantity=$items->item_name[0]->quantity;
		//pr($items);
		///pr($items->item_name[0]); exit;
		$this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
		$item_barcodes=[];
		
		$item_barcodes[] = $this->Items->get($items->item_name[0]->item_id, [
				'contain'=>['Shades','Sizes','StockGroups'=>['ParentStockGroups']]
		]);
		//pr($item_barcodes); exit;
        $this->set(compact('item_barcodes','company_id','no_of_cartoon','no_of_quantity'));
        $this->set('_serialize', ['items']);
	}

	 public function getItems($id = null)
    { 
		$this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
        //$stockGroup = $this->StockGroups->get($id);
		$itemDatas=$this->Items->find('list')->where(['Items.company_id'=>$company_id,'Items.stock_group_id' => $id]); //pr($stockSubgroups); exit;
		$this->set(compact('itemDatas'));
    } 
	
	public function getEditItems($id = null)
    { 
		$this->viewBuilder()->layout('');
		$company_id=$this->Auth->User('session_company_id');
        //$stockGroup = $this->StockGroups->get($id);
		$item_id=$this->request->query('item_id');
		
		$Items=$this->Items->find()->where(['Items.company_id'=>$company_id,'Items.id' => $item_id]); 
		$options=[];
		foreach($Items as $item){ 
			if($item->id == $item_id){
				$value=$item->id;
			}
				$options[]=['text'=>$item->name,'value'=>$item->id];
			
			
		}
		//pr($options); exit;
		$this->set(compact('options','value'));
    } 
	
	public function getItem()
	{
		$item_code = $this->request->getData('barcode');
		$company_id = $this->request->getData('company_id');
		$location_id = $this->request->getData('location_id');

		$item = $this->Items->find()->where(['item_code'=>$item_code])->contain(['Shades','Sizes']);

		if (!empty($item->toArray())) 
		{
			if ($this->Items->ItemLedgers->exists(['item_id'=>$item->first()->id,'company_id'=>$company_id,'location_id'=>$location_id]))
			{
				$success = true;
				$message = "Data Found Successfully";
				$product_detail = $item->first();
				$query = $this->Items->ItemLedgers->find();
				$query->select(['item_in'=>$query->func()->sum('quantity')])
						->where(['status'=>'in','item_id'=>$item->first()->id,'company_id'=>$company_id,'location_id'=>$location_id]);
				$item_in = $query->first()->item_in;

				$query = $this->Items->ItemLedgers->find();
				$query->select(['item_out'=>$query->func()->sum('quantity')])
						->where(['status'=>'out','item_id'=>$item->first()->id,'company_id'=>$company_id,'location_id'=>$location_id]);
				$item_out = $query->first()->item_out;

				$max_quantity = $item_in - $item_out;
			}

			else
			{
				$success = false;
		        $message = "Item is not related to this location";
			}
		}
		else
		{
			$success = false;
            $message = "No Such Item Found";
		}

		$this->set(compact(['product_detail','success','message','max_quantity']));
        $this->set('_serialize', ['success','message','max_quantity','product_detail']);
	}
}
