<?php
class ControllerModuleSuperSeo extends Controller {
	private $error = array();
	
	public function index() {
		$data = array();

        $data = array_merge($data, $this->load->language('module/super_seo'));

		$this->document->setTitle($this->language->get('sseo_heading_title'));

		$this->load->model('setting/setting');


		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'),
				'separator' => false
		);

		$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_extension'),
		'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		);
	
		$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('sseo_heading_title'),
		'href'      => $this->url->link('extension/module/ultimate_seo', 'user_token=' . $this->session->data['user_token'])
		);

		$data['button_save'] = $this->language->get('sseo_save');
		$data['button_cancel'] = $this->language->get('sseo_cancel');
		$data['button_delete'] = $this->language->get('sseo_delete');

		$data['codeinspires_url'] = $this->url->link('extension/module/super_seo/support_page', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['codeinspires'] = $this->config->get('codeinspires');

		if (isset($this->request->post['useo_meta_num'])) {
			$data['useo_meta_num'] = $this->request->post['useo_meta_num'];
		} else {
			$data['useo_meta_num'] = $this->config->get('useo_meta_num');
		}

		if (isset($this->request->post['useo_auto_meta'])) {
			$data['useo_auto_meta'] = $this->request->post['useo_auto_meta'];
		} else {
			$data['useo_auto_meta'] = $this->config->get('useo_auto_meta');
		}
		if ($data['useo_auto_meta'] == 'yes') $data['useo_auto_meta'] = 'checked';
		else $data['useo_auto_meta'] = '';

		$data['action'] = $this->url->link('extension/module/super_seo', 'user_token=' . $this->session->data['user_token'], 'SSL');
		
		$data['description'] = $this->language->get('sseo_description');
		$data['description_route'] = $this->language->get('sseo_description_route');
		$data['description_url'] = $this->language->get('sseo_description_url');

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['heading_title'] = $this->language->get('sseo_heading_title');

		$data['modules'] = array();
		
		if (isset($this->request->post['sseo_module'])) {
			$data['modules'] = $this->request->post['sseo_module'];
		} elseif ($this->config->get('sseo_module')) { 
			$data['modules'] = $this->config->get('sseo_module');
		}

		$query = $this->db->query("SELECT * FROM ". DB_PREFIX ."url_alias WHERE query LIKE 'route=%'");
		$data['super_seo_urls'] = array();
		foreach ($query->rows as $row) {
			$qr = explode('route=',$row['query']);
			if (isset($qr[1]) ) $qr = $qr[1];
			else $qr = $qr[0];
			$url = '&id='.$row['url_alias_id'];
			$url = $this->url->link('extension/module/super_seo/delete', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
			array_push($data['super_seo_urls'],array('query' => $qr, 'keyword' => $row['keyword'], 'delete' => $url ) );
		}

		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sseo', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('sseo_success_text');
			$description_route = 'route='.trim($this->request->post['route']);
			$description_url = trim($this->request->post['url']);	
			$this->db->query("INSERT INTO ". DB_PREFIX ."url_alias SET query = '". $this->db->escape($description_route) ."', keyword = '". $this->db->escape($description_url) ."'");
	        $this->response->redirect($this->url->link('extension/module/super_seo', 'user_token=' . $this->session->data['user_token'] , 'SSL'));

		}

		$data['user_token'] = $this->session->data['user_token'];
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/super_seo', $data));

	}

	public function delete() {
	$this->load->language('extension/module/super_seo');
		if (!$this->user->hasPermission('modify', 'extension/module/super_seo')) {
			$this->session->data['error'] = $this->language->get('sseo_no_permission');
		$this->response->redirect($this->url->link('extension/module/super_seo', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}	
		$id = $this->request->get['id'];
		$query = $this->db->query("DELETE FROM ". DB_PREFIX ."url_alias WHERE url_alias_id = '". (int)$id ."'");
		if ($query)
			$this->session->data['success'] = $this->language->get('sseo_success_delete');
		$this->response->redirect($this->url->link('extension/module/super_seo', 'user_token=' . $this->session->data['user_token'], 'SSL'));

	}

	private function validate() {
		if (isset($this->request->post['route'])) 
			$description_route = trim($this->request->post['route']);
		else $description_route = null;
		if (isset($this->request->post['url'])) 
			$description_url = trim($this->request->post['url']);
		else $description_url = null;

		if (!$this->user->hasPermission('modify', 'extension/module/super_seo')) {
			$this->error['warning'] = $this->language->get('sseo_no_permission');
		}
		else if (empty($description_route) || empty($description_url) ){
			$this->error['warning'] = $this->language->get('sseo_specify');;
		}
		else {
			$query = $this->db->query("SELECT * FROM ".DB_PREFIX."url_alias WHERE query = 'route=".$this->db->escape($description_route)."'");
			if($query->num_rows) {
				$this->error['warning'] = $this->language->get('sseo_same_route');
			}
			$query = $this->db->query("SELECT * FROM ".DB_PREFIX."url_alias WHERE query = 'keyword=".$this->db->escape($description_url)."'");
			if($query->num_rows) {
				$this->error['warning'] = $this->language->get('sseo_same_keyword');
			}
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function support_page () {
		$this->load->language('extension/module/super_seo');
		if (!$this->user->hasPermission('modify', 'extension/module/super_seo')) {
			$this->session->data['error'] = $this->language->get('sseo_no_permission');
		}
		else {
			$codeinspires = $this->config->get('codeinspires');
			if (isset($codeinspires) && $codeinspires == 1) {
				$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value`='0' WHERE `key`='codeinspires'");
			}
			else if (isset($codeinspires) && $codeinspires == 0) {
				$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value`='1' WHERE `key`='codeinspires'");
			}
		}
			$this->response->redirect($this->url->link('extension/module/super_seo', 'user_token=' . $this->session->data['user_token'], 'SSL'));

	}	

	public function install() {
		$this->db->query("DELETE FROM ". DB_PREFIX . "setting WHERE `key`='codeinspires'");
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','codeinspires','codeinspires','1','0')");
	}

// install script
public function install_script() {
$this->languninstall();
$write_errors = array();
$catalog=DIR_CATALOG . 'index.php';
$admin=DIR_APPLICATION . 'index.php';

if(!is_writeable($catalog)) {
$write_errors[] = $catalog .' not writeable (Change File Permissions)';
}
// if(empty($write_errors)){
// 	$search='if (!$store_query->num_rows) {';
// 		$replace='
// 	if (isset($request->get["_route_"])) { // seo_language define
// 	$seo_path = explode(\'/\',$request->get["_route_"]);
// 	foreach ($seo_path as $seo_part) {
// 		if (array_key_exists($seo_part,$languages)) {
// 			$session->data[\'language\'] = $seo_part;
// 		}
// 	}
// }
// 	';
// 	$catalogsuccess=$this->replace($catalog,$search,$replace);
}


//uninstall script
public function uninstall_script() {

$write_errors = array();
$catalog=DIR_CATALOG . 'index.php';
$admin=DIR_APPLICATION . 'index.php';

if(!is_writeable($catalog)) {
$write_errors[] = $catalog .' not writeable (Change File Permissions)';
}
// if(empty($write_errors)){
// 	$replace='if (!$store_query->num_rows) {';
// 		$search='
// 	if (isset($request->get["_route_"])) { // seo_language define
// 	$seo_path = explode(\'/\',$request->get["_route_"]);
// 	foreach ($seo_path as $seo_part) {
// 		if (array_key_exists($seo_part,$languages)) {
// 			$session->data[\'language\'] = $seo_part;
// 		}
// 	}
// }
// 	';
// 	$catalogsuccess=$this->replace($catalog,$search,$replace);
// }

// if(!is_writeable($admin)) {
// $write_errors[] =$admin . ' not writeable (Change File Permissions)';
// }
// if(empty($write_errors)){
// 	$replace='$config->set(\'config_language_id\', $languages[$config->get(\'config_admin_language\')][\'language_id\']);';
// 	$search='
// 	if (isset($request->get["_route_"])) { // seo_language define
// 	$seo_path = explode(\'/\',$request->get["_route_"]);
// 	foreach ($seo_path as $seo_part) {
// 		if (array_key_exists($seo_part,$languages)) {
// 			$session->data[\'language\'] = $seo_part;
// 		}
// 	}
// }
// 	';
// 	$adminsuccess=$this->replace($admin,$search,$replace);
// }
// if(isset($catalogsuccess && $adminsuccess)){
// 	echo 'Super Seo Installed';
// }
// foreach($write_errors as $error){
// 	echo $error;
// 	}
}

 private function replace($file,$search,$replace){
	 
//open file and get data
$data = file_get_contents($file);

// do tag replacements or whatever you want
$data = str_replace($search, $replace, $data);

//save it back:
$success=file_put_contents($file, $data);
return $success;

 }
	
	public function uninstall() {
$this->db->query("DELETE FROM ". DB_PREFIX . "setting WHERE `key`='codeinspires'");
	}

}