<?php

namespace PhpPdg\ProgramDependence\ControlDependence\BlockFlowGraph;

use PHPCfg\Func;
use PhpPdg\Graph\GraphInterface;
use PhpPdg\Graph\Node\NodeInterface;

interface GeneratorInterface {
	/**
	 * @param Func $func
	 * @param NodeInterface $entry_node
	 * @param NodeInterface $stop_node
	 * @return GraphInterface
	 */
	public function generate(Func $func, NodeInterface $entry_node, NodeInterface $stop_node);
}