<?php

namespace Permafrost\PhpCodeSearch\Support;

use Permafrost\PhpCodeSearch\Results\Nodes\ClassConstantNode;
use Permafrost\PhpCodeSearch\Results\Nodes\ClassMethodNode;
use Permafrost\PhpCodeSearch\Results\Nodes\ClassPropertyNode;
use Permafrost\PhpCodeSearch\Results\Nodes\ParameterNode;
use PhpParser\Node;

class StatementTransformer
{
    public static function parserNodeToResultNode(Node $node)
    {
        $map = [
            Node\Param::class => ParameterNode::class,
            Node\Stmt\Property::class => ClassPropertyNode::class,
            Node\Stmt\ClassMethod::class => ClassMethodNode::class,
            Node\Stmt\ClassConst::class => ClassConstantNode::class,
        ];

        foreach ($map as $parserNodeClass => $resultNodeClass) {
            if ($node instanceof $parserNodeClass) {
                return new $resultNodeClass($node);
            }
        }

        return $node;
    }

    public static function parserNodesToResultNode(array $nodes): array
    {
        return collection($nodes)->map(function ($node) {
            return self::parserNodeToResultNode($node);
        })->toArray();
    }
}
