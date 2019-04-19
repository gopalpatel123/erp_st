<?php
namespace App\Controller;
use Cake\Event\Event;
use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow([ 'logout', 'add', 'loginApi']);
    }

	public function logout()
	{
		return $this->redirect($this->Auth->logout());
	}
	
   public function login()
    {
		$this->viewBuilder()->layout('login');
        if ($this->request->is('post')) 
		{
            $user = $this->Auth->identify();
            if ($user) 
			{
				$user=$this->Users->get($user['id'], [
					'contain' => ['CompanyUsers']
				]);
				$user->session_company_id=$user->company_users[0]->company_id;
				$user->session_location_id=$user->company_users[0]->location_id;
				$userid=$user->id;
				unset($user->company_users);
				$company=$this->Users->CompanyUsers->Companies->get($user->session_company_id, [
					'contain' => ['FinancialYears'=>function($q){
						return $q->where(['FinancialYears.status'=>'open'])->order(['FinancialYears.fy_from'=>'ASC']);
					}]
				]);
				$location=$this->Users->CompanyUsers->Locations->get($user->session_location_id);
				$location_name=$location->name;
				$fyValidFrom=$company->financial_years[0]->fy_from;
				foreach($company->financial_years as $financial_year){
					$fyValidTo=$financial_year->fy_to;
				}
				$user->fyValidFrom=$fyValidFrom;
				$user->fyValidTo=$fyValidTo;
				unset($company->financial_years);
				$user->session_company=$company;
				$user->id=$userid;
				$user->location_name=$location_name;
                $this->Auth->setUser($user);
				
				//pr($user->session_company); exit;
				return $this->redirect(['action' => 'selectCompanyYear']);
				//return $this->redirect(['controller'=>'Users','action' => 'Dashboard']);
            }
            $this->Flash->error(__('Invalid Username or Password'));
        }
		$user = $this->Users->newEntity();
        $this->set(compact('user'));
    }

	public function selectCompanyYear($financialYear_id=null)
    {
			$this->viewBuilder()->layout('login');
			$company_id=$this->Auth->User('session_company_id');
			
			$financialYears = $this->paginate($this->Users->FinancialYears->find()->where(['company_id'=>$company_id,'status' =>'Open']));
			$user=$this->Auth->User();
			 if(!empty($financialYear_id)){
				
			$this->request->allowMethod(['post', 'delete']);
			$user->financialYear_id=$financialYear_id;
			return $this->redirect(['controller'=>'Users','action' => 'Dashboard']);
			}
			/*$financialYears = $this->paginate($this->FinancialYears->find()->where(['company_id'=>$st_company_id,'status' =>'Open']));
			
			$count=0;
			foreach($financialYears as $data){
					$count++;
			}
				if($count==1){
					foreach($financialYears as $financialYear){
						$this->request->session()->write('st_year_id',$financialYear->id);
						break;
					}
					return $this->redirect('/Dashboard');
				} */
			
			$this->set(compact('financialYears'));
			$this->set('_serialize', ['financialYears']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['CompanyUsers']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }
    public function masterSetup()
    {        
    	$this->viewBuilder()->layout('index_layout');
    }
	public function voucherSetup()
    {        
    	$this->viewBuilder()->layout('index_layout');
    }
	   public function reports()
    {        
    	$this->viewBuilder()->layout('index_layout');
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function dashboard()
    {
        $this->viewBuilder()->layout('index_layout');
    }

    public function loginApi()
    {
        $user = $this->Auth->identify();
        if ($user) {
            $this->Auth->setUser($user);
            $company_user = $this->Users->CompanyUsers->find()->where(['user_id'=>$this->Auth->user('id')])->first();

            $success = true;
            $message = "User found successfully";
            $response = $user;
            $response['company_id'] = $company_user->company_id;
            $response['location_id'] = $company_user->location_id;
        }
        else
        {
            $success = false;
            $message = "Username Or Password Is Wrong";
        }
            
        $this->set(compact(['response','success','message']));
        $this->set('_serialize', ['success','message','response']);
    }
}
