<h2>Venues</h2>

<?php foreach ($objects as $object): ?>

	<?php $this->render_view('_item', array('locals' => array('object' => $object))); ?>

<?php endforeach; ?>

<?php echo $this->pagination(); ?>