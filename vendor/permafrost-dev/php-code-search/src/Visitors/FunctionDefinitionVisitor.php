<?php


namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Results\Nodes\FunctionDefinitionNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use PhpParser\Node;

class FunctionDefinitionVisitor extends NodeVisitor
{
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Function_) {
            if (Arr::matches($node->name->toString(), $this->names, true)) {
                $resultNode = FunctionDefinitionNode::create($node);

                $this->results->add($resultNode, $resultNode->location());
            }
        }
    }
}
