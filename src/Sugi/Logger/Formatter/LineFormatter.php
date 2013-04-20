<?php

namespace Sugi\Logger\Formatter;

class LineFormatter extends \Monolog\Formatter\NormalizerFormatter
{
	// const SIMPLE_FORMAT = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
	const SIMPLE_FORMAT = "[{Y}-{m}-{d} {H}:{i}:{s}] [{ip}] [{level}] {message}";

	protected $format;

	/**
	 * @param string $format The format of the message
	 * @param string $dateFormat The format of the timestamp: one supported by DateTime::format
	 */
	public function __construct($format = null, $dateFormat = null)
	{
		$this->format = $format ?: static::SIMPLE_FORMAT;

		// old date format
		$this->format = str_replace("{Y}-{m}-{d} {H}:{i}:{s}", "{datetime}", $this->format);
		$this->format = str_replace("{level}", "{level_name}", $this->format);
		$this->format = str_replace("{ip}", "{extra.ip}", $this->format);
		$this->format = rtrim($this->format, "\n") . "\n";

		parent::__construct($dateFormat);
	}

	/**
	 * {@inheritdoc}
	 */
	public function format(array $record)
	{

		$vars = parent::format($record);

		$output = $this->format;
		foreach ($vars["extra"] as $var => $val) {
			if (false !== strpos($output, "{extra.".$var."}")) {
				$output = str_replace("{extra.".$var."}", $this->convertToString($val), $output);
				unset($vars["extra"][$var]);
			}
		}

		foreach ($vars as $var => $val) {
			$output = str_replace("{".$var."}", $this->convertToString($val), $output);
		}

		return $output;
	}

	public function formatBatch(array $records)
	{
		$message = "";
		foreach ($records as $record) {
			$message .= $this->format($record);
		}

		return $message;
	}

	protected function normalize($data)
	{
		if (is_bool($data) || is_null($data)) {
			return var_export($data, true);
		}

		if ($data instanceof \Exception) {
			return "[object] (".get_class($data).": ".$data->getMessage()." at ".$data->getFile().":".$data->getLine().")";
		}

		return parent::normalize($data);
	}

	protected function convertToString($data)
	{
		if (null === $data || is_scalar($data)) {
			return (string) $data;
		}

		$data = $this->normalize($data);
		if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
			return $this->toJson($data);
	 	}

		return str_replace('\\/', '/', json_encode($data));
	}
}
