<?php
class EVPublicController extends MvcPublicController {

    public function index_json() {
        $errors = new WP_Error();

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                // Create
                $status = $this->create_object();
                break;

            default:
                // Read list
                $status = $this->read_objects();
                break;
        }

        if (isset($status) && is_wp_error($status)) {
            $error_code = $status->get_error_code();
            $errors->add($error_code, $status->get_error_message($error_code));
        }

        if (!empty($errors->errors)) {
            $this->set('errors', $errors);
        }
    }

    public function show_json() {
        $errors = new WP_Error();

        $id = $this->params['id'];
        if (isset($id)) {
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'DELETE':
                    // Delete
                    $status = $this->delete_object($id);
                    break;

                case 'PUT':
                    // Update
                    $status = $this->update_object($id);
                    break;

                default:
                    // Read individual
                    $status = $this->read_object($id);
                    break;
            }
        }

        if (is_wp_error($status)) {
			error_log('ERROR');
            $error_code = $status->get_error_code();
			error_log($error_code);
			error_log($status->get_error_message($error_code));
            $errors->add($error_code, $status->get_error_message($error_code));
        }

        if (!empty($errors->errors)) {
            $this->set('errors', $errors);
        }
    }

    protected function create_object() {
		$json = file_get_contents('php://input');
		$data = json_decode($json);
        if (isset($data)) {
            $status = $this->model->create($data);
            if (!is_wp_error($status)) {
                $object = $this->model->find_by_id($status);
                $this->set('object', $object);
            }
        } else {
            $status = new WP_Error('invalid_request', 'No data specified');
        }
        return $status;
    }

    protected function read_objects() {
        return $this->set_objects();
    }

    protected function read_object($id) {
        $status = $this->model->find_by_id($id);
        if (!is_wp_error($status)) {
            $this->set('object', $status);
        }
        return $status;
    }

    protected function delete_object($id) {
		$result = $this->model->delete($id);
		return $result;
    }

    protected function update_object($id) {
        $d = @file_get_contents('php://input');
        $data = json_decode($d);
        if (!empty($data)) {
            $data->id = $id;
            $status = $this->model->save($data);
            if (!is_wp_error($status)) {
                $this->set('object', $this->model->find_by_id($id));
            }
        }
        return $status;
    }

    public function set_objects() {
		$this->process_params_for_search();
        $status = $this->model->paginate($this->params);
        if (!is_wp_error($status)) {
			$this->set('objects', $status['objects']);
	        $this->set_pagination($status);
        } else {
            return $status;
        }
    }

	public function user_can() {
		$flag = false;
		if (array_key_exists('cap', $this->params)) {
			$cap = $this->params['cap'];
			if ($cap === 'read_venues' || $cap === 'read_events')
				$flag = true;
			else
				$flag = current_user_can($this->params['cap']);
		} 

		$this->set('msg', $flag);
	}
			
}
?>
