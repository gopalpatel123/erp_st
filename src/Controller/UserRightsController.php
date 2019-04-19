<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UserRights Controller
 *
 * @property \App\Model\Table\UserRightsTable $UserRights
 *
 * @method \App\Model\Entity\UserRight[] paginate($object = null, array $settings = [])
 */
class UserRightsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Pages', 'Users']
        ];
        $userRights = $this->paginate($this->UserRights);

        $this->set(compact('userRights'));
        $this->set('_serialize', ['userRights']);
    }

    /**
     * View method
     *
     * @param string|null $id User Right id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userRight = $this->UserRights->get($id, [
            'contain' => ['Pages', 'Users']
        ]);

        $this->set('userRight', $userRight);
        $this->set('_serialize', ['userRight']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
		$this->viewBuilder()->layout('index_layout');
		$company_id=$this->Auth->User('session_company_id');
		$userRight = $this->UserRights->newEntity();
        if ($this->request->is('post')) {
			$userData=$this->request->getData();
			$this->UserRights->deleteAll(['UserRights.user_id'=>$userData['user_id']]);
			foreach($userData['page_id'] as $data)
			{
				$rightsData = $this->UserRights->query();
								$rightsData->insert(['user_id','page_id'])
										->values([
										'user_id' => $userData['user_id'],
										'page_id' => $data])
					  ->execute();
            }
			 $this->Flash->success(__('The user right has been saved.'));
             return $this->redirect(['action' => 'add']);
	}
	           // $this->Flash->error(__('The user right could not be saved. Please, try again.'));
        $pages = $this->UserRights->Pages->find('All');
        $users = $this->UserRights->Users->find('list', ['limit' => 200]);
        $this->set(compact('userRight', 'pages', 'users'));
        $this->set('_serialize', ['userRight']);
    }
	public function ajaxUserRights($userid=null)
    {
	    $this->viewBuilder()->layout('');
		//$company_id=$this->Auth->User('session_company_id');
		//$stateDetails=$this->Auth->User('session_company');
		//$location_id=$this->Auth->User('session_location_id');
		//$state_id=$stateDetails->state_id;
		$userData = $this->UserRights->Pages->find()
					->contain(['UserRights'=>function($q) use($userid){
						return $q->where(['UserRights.user_id'=>$userid]);
						}])
					->autoFields(true);
					echo '
					<div class="row"><div class="col-md-12"><div class="col-md-4"><h3 style="color:#000; font-size:15px; font-family:georgia"><input type="checkbox" class="checkAll" name="" value="" id="checkAll">Check All</h3></div></div></div>';
					
					            echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Dashboard</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Dashboard'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Sales Invoice</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Sales Invoice'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
					/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Sales Return</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Sales Return'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Grn</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Grn'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Purchase Invoice</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Purchase Invoice'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Purchase Return</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Purchase Return'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
						
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Intra Location Transfer</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Intra Location Transfer'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Generate Item Barcode</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Generate Item Barcode'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";

								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-12' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Report</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Report'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."&nbsp;";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Sales Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Sales Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Purchase Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Purchase Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";

								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Credit Note Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Credit Note Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Debit Note Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Debit Note Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Receipt Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Receipt Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Payment Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Payment Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";

								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Journal Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Journal Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Contra Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Contra Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Stock Journal Voucher</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Stock Journal Voucher'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Stock Group</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Stock Group'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Item</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Item'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Shades</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Shades'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Unit</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Unit'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Size</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Size'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Accounting Group</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Accounting Group'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Ledgers</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Ledgers'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Customer</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Customer'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div>";
								
								echo "<div class='col-md-6'><fieldset><legend>Supplier</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Supplier'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div><br>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>User Rights</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='User Rights'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div>";
								
								/////////
								echo "<div class='row' style='color:#acb3b9; font-size:15px; font-family:georgia'><div class='col-md-12'><div class='form-group'><div class='col-md-6' style='color:#acb3b9; font-size:15px; font-family:georgia'><fieldset><legend>Challan</legend>";
								 foreach($userData->toArray() as $data)
								{ 
							        if(!empty(@$data->user_rights[0]->page_id))
									{?><?php $ckd='checked';?>
									<?php } else {?>
									<?php $ckd='  ';  ?>
									<?php } 
										if($data['module_name']=='Challan'){echo "<input type='checkbox' name='page_id[]' value=".$data['id']." ".$ckd.">&nbsp;".$data['page_name']."";
										}
							    }
								echo "</fieldset></div></div></div></div>";
						exit;
}

    /**
     * Edit method
     *
     * @param string|null $id User Right id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userRight = $this->UserRights->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $userRight = $this->UserRights->patchEntity($userRight, $this->request->getData());
            if ($this->UserRights->save($userRight)) {
                $this->Flash->success(__('The user right has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user right could not be saved. Please, try again.'));
        }
        $pages = $this->UserRights->Pages->find('list', ['limit' => 200]);
        $users = $this->UserRights->Users->find('list', ['limit' => 200]);
        $this->set(compact('userRight', 'pages', 'users'));
        $this->set('_serialize', ['userRight']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Right id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $userRight = $this->UserRights->get($id);
        if ($this->UserRights->delete($userRight)) {
            $this->Flash->success(__('The user right has been deleted.'));
        } else {
            $this->Flash->error(__('The user right could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
