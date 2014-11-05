<?php
class Validation_Rules
{
	public static function _validation_unique($val, $table_field, $except = null)
	{
		Validation::active()->set_message('unique', ':label \':value\' is already taken.');

		list($table, $field) = explode('.', $table_field);

		$query = DB::select()->from($table)->where(Str::lower($field), Str::lower($val));
		if ($except) $query->where(Str::lower($field), '!=', Str::lower($except));

		return $query->execute()->count() <= 0;
	}
}