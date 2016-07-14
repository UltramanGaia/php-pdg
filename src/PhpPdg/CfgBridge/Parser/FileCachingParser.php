<?php

namespace PhpPdg\CfgBridge\Parser;

use PhpParser\Node;
use PhpParser\Parser;

class FileCachingParser implements FileParserInterface {
	private $cache_dir;
	/** @var FileParserInterface */
	private $wrapped_parser;

	/**
	 * FileCachingParser constructor.
	 * @param string $cache_dir
	 * @param FileParserInterface $wrapped_parser
	 * @param boolean $auto_create
	 */
	public function __construct($cache_dir, FileParserInterface $wrapped_parser, $auto_create = false) {
		$this->cache_dir = $cache_dir;
		$this->wrapped_parser = $wrapped_parser;
		if ($auto_create === true && is_dir($this->cache_dir) === false) {
			mkdir($this->cache_dir, 0777, true);
		}
	}

	public function parse($filename) {
		if (file_exists($filename) === false) {
			throw new \InvalidArgumentException("No such file: `$filename`");
		}
		$mtime = filemtime($filename);
		$cache_file = $this->cache_dir . '/' . strtr($filename, [
			'/' => '_',
			'\\' => '_',
			':' => '_',
		]) . '.cache';
		if (file_exists($cache_file) === true) {
			list($script, $cached_mtime) = unserialize(file_get_contents($cache_file));
			if ($mtime === $cached_mtime) {
				return $script;
			}
		}
		$script = $this->wrapped_parser->parse($filename);
		file_put_contents($cache_file, serialize([$script, $mtime]));
		return $script;
	}
}