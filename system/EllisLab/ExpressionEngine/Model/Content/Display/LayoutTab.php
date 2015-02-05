<?php

namespace EllisLab\ExpressionEngine\Model\Content\Display;

class LayoutTab {

	public $id;
	public $title;

	protected $hidden = FALSE;
	protected $fields;

	public function __construct($id, $title, array $fields = array())
	{
		$this->id = $id;
		$this->title = $title;
		$this->fields = $fields;
		return $this;
	}

	public function setFields($fields)
	{
		$this->fields = $fields;
		return $this;
	}

	public function addField($field)
	{
		$this->fields[] = $field;
		return $this;
	}

	public function getFields()
	{
		return $this->fields;
	}

	public function setHidden()
	{
		$this->hidden = TRUE;
		return $this;
	}

	public function isHidden()
	{
		return $this->hidden;
	}

}