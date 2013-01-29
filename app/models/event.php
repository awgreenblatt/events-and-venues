<?php
class Event extends MvcModel {

	var $display_field = 'name';
	var $table = '{prefix}events';

	var $belongs_to = array(
		'Venue' => array('foreign_key' => 'venue_id')
	);

	public function create($data) {
		if (current_user_can('create_events')) {
			return parent::create($data);
		} else {
			return new WP_Error('invalid_access', 'User has insufficient privileges to create an event');
		}
    }

    public function save($data) {
        if (current_user_can('edit_events')) {
            return parent::save($data);
        } else {
            return new WP_Error('invalid_access', 'User has insufficient privileges to edit an event');
        }
    }

	public function delete($id) {
        $options = array(
            'conditions' => array($this->primary_key => $id)
        );
        return $this->delete_all($options);
    }

    public function delete_all($options=array()) {
        if (current_user_can('delete_events')) {
            return parent::delete_all($options);
        } else {
            return new WP_Error('invalid_access', 'User has insufficient privileges to delete events');
        }
    }
}
?>
