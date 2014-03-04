<?php
namespace Core\Db;

	class Criteria {

		private $orders = array();
		
		private $fields = array();
		
		private $page = -1;
		
		private $perPage = -1;
		
		private $table = null;
		
		private $sql = null;
		
		private $distinct = null;
		
		private $expressions = array();
		
		public function getOrders()
		{
			return $this->orders;
		}
		
		public function getFields()
		{
			return $this->fields;
		}
		
		public function getPage()
		{
			return $this->page;
		}
		
		public function getPerPage()
		{
			return $this->perPage;
		}
		
		public function getTable()
		{
			return $this->table;
		}
		
		public function getSql()
		{
			return $this->sql;
		}
		
		public function getDistinct()
		{
			return $this->distinct;
		}
		
		public function getExpressions()
		{
			return $this->expressions;
		}
		
		public static function create($expressions = array())
		{
			return new self($expressions);
		}
		
		public function __construct($expressions = array())
		{
			$this->buildExpressions($expressions);
		}
		
		public function add($expression)
		{
			$this->buildExpressions($expression);
			return $this;
		}
		
		public function order($order)
		{
			if(is_array($order))
				$this->orders = array_merge($this->orders, $order);
			else
				$this->orders[] = $order;
			return $this;
		}
		
		public function page($page)
		{
			$this->page = $page;
			return $this;
		}
		
		public function perPage($perPage)
		{
			$this->perPage = $perPage;
			return $this;
		}
		
		public function table($table)
		{
			$this->table = $table;
			return $this;
		}
		
		public function sql($sql)
		{
			$this->sql = $sql;
			return $this;
		}
		
		public function distinct($field = 'id')
		{
			$this->distinct = $field;
			return $this;
		}
		
		public function field($fields)
		{
			if(is_array($fields))
				$this->fields = array_merge($this->fields, $fields);
			else
				$this->fields[] = $fields;
			return $this;
		}
		
		/*proxy to Db_Field*/
		public function eq($criteria)
		{
			return $this->proxy($criteria, 'eq');
		}
		
		public function ne($criteria)
		{
			return $this->proxy($criteria, 'ne');
		}
		
		public function gt($criteria)
		{
			return $this->proxy($criteria, 'gt');
		}
		
		public function lt($criteria)
		{
			return $this->proxy($criteria, 'lt');
		}
		
		public function ge($criteria)
		{
			return $this->proxy($criteria, 'ge');
		}
		
		public function le($criteria)
		{
			return $this->proxy($criteria, 'le');
		}
		
		public function in($criteria)
		{
			return $this->proxy($criteria, 'in');
		}
		
		public function like($criteria)
		{
			return $this->proxy($criteria, 'like');
		}
		
		public function eqLike($criteria)
		{
			return $this->proxy($criteria, 'eqLike');
		}
		
		public function likeLeft($criteria)
		{
			return $this->proxy($criteria, 'likeLeft');
		}
		
		public function likeRight($criteria)
		{
			return $this->proxy($criteria, 'likeRight');
		}
		
		public function isNull($field)
		{
			$this->buildExpressions(\Db\Exp::isNull($field));
			return $this;
		}
		
		public function isNotNull($field)
		{
			$this->buildExpressions(\Db\Exp::isNotNull($field));
			return $this;
		}
		
		private function proxy($criteria, $operation = 'eq')
		{
			foreach($criteria as $field => $value)
				$this->buildExpressions($this->resolveOperation($field, $value, $operation));
			return $this;
		}
		
		private function resolveOperation($field, $value, $operation)
		{
			switch($operation)
			{
				case 'eq' :
					return \Db\Exp::eq($field, $value);
				case 'lt' :
					return \Db\Exp::lt($field, $value);
				case 'gt' :
					return \Db\Exp::gt($field, $value);
				case 'ne' :
					return \Db\Exp::ne($field, $value);
				case 'le' :
					return \Db\Exp::le($field, $value);
				case 'ge' :
					return \Db\Exp::ge($field, $value);
				case 'in' :
					return \Db\Exp::in($field, $value);
				case 'like' :
					return \Db\Exp::like($field, $value);
				case 'eqLike' :
					return \Db\Exp::eqLike($field, $value);
				case 'likeLeft' :
					return \Db\Exp::likeLeft($field, $value);
				case 'likeRight' :
					return \Db\Exp::likeRight($field, $value);
			}
			return \Db\Exp::eq($field, $value);
		}
		/**/
		
		private function buildExpressions($expression)
		{
			
			if(is_array($expression))
				foreach($expression as $field => $value)
				{
					if(is_int($field))
						$this->expressions[] = $value;
					else
						$this->expressions[] = \Db\Exp::eq($field, $value);
				}
			else
				$this->expressions[] = $expression;
		}
	}

?>