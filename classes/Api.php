<?php 
class Api{
	private $key;
	private $conn;

	public function __construct($key){  //Magic method
		global $conn;
		$this->key = $key;
		$this->conn = $conn;
	}

	public function init(){
		$output = array();
		if(!empty($_GET['key'])){
			$res = $this->authenticate($_GET['key']);
			if($res == false){
				$output['success'] = false;
				$output['error'] = 'Authentication failed';
				return json_encode($output);
			}else{
				if(!empty($_GET['action'])){
					switch ($_GET['action']) {
						case 'journal':
							//Get items
							$items = array();
							$res=mysqli_query($this->conn, "SELECT * FROM `news`");
							while($item = mysqli_fetch_object($res)) {
								$items[] = $item; //Append to array
							}
							//Set output
							$output['success'] = true;
							$output['result'] = $items;
							return json_encode($output);
							break;
						case 'gallery':
							//Get items
							$items = array();
							$res=mysqli_query($this->conn, "SELECT * FROM `gallery`");
							while($item = mysqli_fetch_object($res)) {
								$items[] = $item; //Append to array
							}
							//Set output
							$output['success'] = true;
							$output['result'] = $items;
							return json_encode($output);
							break;
						case 'page':
							//Get items
							$items = array();
							$res=mysqli_query($this->conn, "SELECT * FROM `pages`");
							while($item = mysqli_fetch_object($res)) {
								$items[] = $item; //Append to array
							}
							//Set output
							$output['success'] = true;
							$output['result'] = $items;
							return json_encode($output);
							break;
						default:
							$output['success'] = false;
							$output['error'] = 'Action is not valid';
							return json_encode($output);
							break;
					}
				}else{
					$output['success'] = false;
					$output['error'] = 'Action has not been provided';
					return json_encode($output);
				}
			}
		}else{
			$output['success'] = false;
			$output['error'] = 'Key has not been provided';
			return json_encode($output);
		}
	}

	public function authenticate($key){
		if($key===$this->key){
			return true;
		}else{
			return false;
		}
	}
}