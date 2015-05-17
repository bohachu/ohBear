<?php

class Admin_Theme_Element_Radio extends Admin_Theme_Menu_Element
{

	protected $option = array(
		'type' => Admin_Theme_Menu_Element::TYPE_RADIO,
	);

	public function render()
	{
		/**
		 * @todo what is selector ?????
		 */
		$selector = '';
		ob_start();
		$id_iterator = 1;
		$value = get_option($this->id);
		echo $this->getElementHeader();
		?>
		<?php foreach ($this->options as $r_value => $r_description): ?>
			<label><input name="<?php echo $this->id; ?>" id="<?php echo $this->id . '_' . $id_iterator++; ?>" type="radio" value="<?php echo $r_value; ?>" <?php echo $selector; ?> <?php if ($value == $r_value || $r_value == $this->getStdValue())
		{
			echo 'checked="checked"';
			} ?> /> <?php echo $r_description; ?></label><br /><br />
		<?php endforeach; ?>
		<?php
		echo $this->getElementFooter();
		$html = ob_get_clean();
		return $html;
	}

	}
?>