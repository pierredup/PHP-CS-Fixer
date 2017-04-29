<?php

namespace PhpCsFixer\Fixer\FunctionNotation;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

final class MultilineFunctionFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens)
    {
        foreach ($tokens as $index => $token) {
            // looking for start brace
            if (!$token->equals('(')) {
                continue;
            }

            if ($tokens[$tokens->getPrevMeaningfulToken($index)]->isGivenKind(T_FUNCTION) || !$tokens[$tokens->getNextNonWhitespace($index)]->isGivenKind(T_VARIABLE)) {
                continue;
            }

            $blockEnd = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $index);

            $insert = array();

            for ($i = $index; $i < $blockEnd; $i++) {
                if ($tokens[$i]->isWhitespace() && false === strpos($tokens[$i]->getContent(), "\n")) {
                    $tokens[$i]->clear();
                    continue;
                }

                if ($tokens[$i]->isGivenKind(T_VARIABLE) && false === strpos($tokens[$i - 1]->getContent(), "\n")) {
                    $insert[$i] = new Token([T_WHITESPACE, "\n"]);
                }
            }

            foreach ($insert as $k => $v) {
                $tokens->insertAt($k, $v);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        // This must be run before
        return 2;
    }


    /**
     * {@inheritdoc}
     */
    public function getDefinition()
    {
        return new FixerDefinition(
            "Split argument list across multiple lines.",
            array(
                new CodeSample(
                    "\"<?php foo(\n\t\$arg1,\n\t\$arg2,\n\t\$arg3\n);"
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens)
    {
        return $tokens->isAnyTokenKindsFound([T_STRING, T_VARIABLE]);
    }
}