<?php namespace Clockwork\DataSource;

use Clockwork\Request\Request;

/**
 * Base data source class
 */
class DataSource implements DataSourceInterface
{
	// Whether the data source should collect stack traces (when applicable)
	protected $collectStackTraces;

	/**
	 * Adds data to the request and returns it, custom implementation should be provided in child classes
	 */
	public function resolve(Request $request)
	{
		return $request;
	}

	// Extends the request with additional data when being shown in the Clockwork app
	public function extend(Request $request)
	{
		return $request;
	}

	// Enable or disable collecting of stack traces
	public function collectStackTraces($enable = true)
	{
		$this->collectStackTraces = $enable;

		return $this;
	}

	/**
	 * Censors passwords in an array, identified by key containing "pass" substring
	 */
	public function removePasswords(array $data)
	{
		$keys = array_keys($data);
		$values = array_map(function ($value, $key) {
			return strpos($key, 'pass') !== false ? '*removed*' : $value;
		}, $data, $keys);

		return array_combine($keys, $values);
	}
}
