<?php
class Venue extends MvcModel {
	var $display_field = 'name';
	var $table = '{prefix}venues';
	
	var $has_many = array(
		'Event' => array(
			'foreign_key' => 'venue_id'
		)
	);

	public function create($data) {
		if (current_user_can('create_venues')) {
			return parent::create($data);
		} else {
			return new WP_Error('invalid_access', 'User has insufficient privileges to create a venue');
		}
    }

    public function save($data) {
        if (current_user_can('edit_venues')) {
            return parent::save($data);
        } else {
            return new WP_Error('invalid_access', 'User has insufficient privileges to edit a venue');
        }
    }

	public function delete($id) {
        $options = array(
            'conditions' => array($this->primary_key => $id)
        );
        return $this->delete_all($options);
    }

    public function delete_all($options=array()) {
        if (current_user_can('delete_venues')) {
            return parent::delete_all($options);
        } else {
            return new WP_Error('invalid_access', 'User has insufficient privileges to delete venues');
        }
    }
}
?>
