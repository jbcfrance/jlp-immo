<?php
class accesPage extends Page {
	
	public function traitementPage() {
		if(isset($_POST) and !empty($_POST)){
			$aPost = array();
			foreach($_POST as $kPost=>$vPost){
				$aPost[$kPost] = $vPost;	
			}
		}
		
		$this->aVars[$this->aVars['sAction']] = array();
		
		if(method_exists(&$this, $this->aVars['sAction'])){
			$this->{$this->aVars['sAction']}(); 
		}else{
			$this->{'login'}();
		}
		
		
	}
	
	public function login(){
		$this->setLayout('blankLayout.htm');
		if(isset($_POST['login']) && isset($_POST['password'])){
			$oAdmin = ADMIN::SELECT()->WHERE('admin_login',$_POST['login'])->getOne();
			if(isset($oAdmin)){
				if($oAdmin->getAdmin_Password()==md5($_POST['password'])){
					$_SESSION['isLogged'] = 1;
					$_SESSION['userName'] = $oData->admin_login;
					$this->redirect('dashboard','dashboard');
				}else{
					$_SESSION['isLogged'] = 0;
					$_SESSION['logErreur'] = " Mauvais mot de passe ";
					$this->redirect('dashboard','accueil');
				}
			}else{
				$_SESSION['isLogged'] = 0;
				$_SESSION['logErreur'] = " Mauvais login";
				$this->redirect('dashboard','accueil');
			}
		}else{
			$this->redirect('dashboard','accueil');
		}
	}
	
	public function logout(){
		$this->setLayout('blankLayout.htm');
		$_SESSION['isLogged'] = 0;
		$this->redirect('dashboard','accueil');
	}	
}
?>