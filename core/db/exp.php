<?php

namespace Core\Db;

	class Exp {
		public static function eq($field, $value, $string = false)
		{
			return "$field = " . self::prepareValue($value, $string);
		}
		
		public static function ne($field, $value, $string = false)
		{
			return "$field != " . self::prepareValue($value, $string);
		}
		
		public static function gt($field, $value, $string = false)
		{
			return "$field > " . self::prepareValue($value, $string);
		}
		
		public static function ge($field, $value, $string = false)
		{
			return "$field >= " . self::prepareValue($value, $string);
		}
		
		public static function lt($field, $value, $string = false)
		{
			return "$field < " . self::prepareValue($value, $string);
		}
		
		public static function le($field, $value, $string = false)
		{
			return "$field <= " . self::prepareValue($value, $string);
		}
		
		public static function like($field, $value)
		{
			return "$field LIKE '%$value%'";
		}
		
		public static function eqLike($field, $value)
		{
			return "$field LIKE '$value'";
		}
		
		public static function likeLeft($field, $value)
		{
			return "$field LIKE '$value%'";
		}
		
		public static function likeRight($field, $value)
		{
			return "$field LIKE '%$value'";
		}
		
		public static function sql($query)
		{
			return $query;
		}
		
		public static function isNotNull($field)
		{
			return "$field IS NOT NULL";
		}
		
		public static function isNull($field)
		{
			return "$field IS NULL";
		}
		
		public static function disunction($expressions)
		{
			$str = "";
			for($i = 0; $i < count($expressions); $i++)
			{
				if($i > 0)
					$str .= " OR ";
				$str .= $expressions[$i];
			}
			if($str != "")
				$str = "( " . $str . " )";
			return $str;
		}
		
		public static function conjunction($expressions)
		{
			$str = "";
			for($i = 0; $i < count($expressions); $i++)
			{
				if($i > 0)
					$str .= " AND ";
				$str .= $expressions[$i];
			}
			if($str != "")
				$str = "( " . $str . " )";
			return $str;
		}
		
		public static function in($field, $values)
		{
			$str = "";
			$n = count($values);
			for($i = 0; $i < $n; $i++)
				$str .= self::prepareValue($values[$i]) . ",";
			$str = preg_replace("/,$/", "", $str);
			return "$field in ($str)";
		}
		
		public static function decorateField($value)
		{
			return !is_numeric($value) || "".intval($value) !== $value;
		}
		
		public static function prepareValue($value, $string = false)
		{
			$value = "'".mysql_real_escape_string($value)."'";
			return $value;
		}
	}
?>