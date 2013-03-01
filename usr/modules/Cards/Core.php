<?php

class Cards_Core extends modulePdo {

	protected $objects = array(
		"card" => array(),
	);

	public function fullFill($ids) {
		if (!is_array($ids)) {
			$ids = array($ids);
			$is_alone = true;
		}

		$list = $this->search('card', array('id' => $ids));
		$res = array_combine(misc::array_get_col($list, 'id'), $list);

		if (!empty($is_alone)) {
			return reset($res);
		} else {
			return $res;
		}
	}
}