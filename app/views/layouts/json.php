<?php
header("Content-Type: application/json", true);

function remove_sys_props($object) {
    if (is_object($object) || is_array($object)) {
        foreach ($object as $key => $value) {
            if (substr($key, 0, 2) === '__') {
                unset($object->$key);
            } else {
                if (is_object($value) || is_array($value)) {
                    remove_sys_props($value);
                }
            }
        }
    }
}

$data = new StdClass;

if (isset($errors)) {
    $data->status = "ERROR";
    $data->errors = array();
    foreach ($errors->get_error_codes() as $error_code) {
        foreach ($errors->get_error_messages($error_code) as $error_message) {
            $data->errors[$error_code][] = $error_message;
        }
    }
} else {
    $data->status = "OK";
}

if (isset($msg)) {
    $data->msg = $msg;
}

if (isset($object)) {
    remove_sys_props($object);
    $data->object = $object;
} else if (isset($objects)) {
    remove_sys_props($objects);
    $data->objects = $objects;
    if (isset($this->pagination)) {
        $data->current_page = $this->pagination['current'];
        $data->total_pages = $this->pagination['total'];
    }
}

$encoded = json_encode($data);
echo $encoded;
?>
