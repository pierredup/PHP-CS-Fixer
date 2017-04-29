<?php

namespace PhpCsFixer\Tests\Fixer\FunctionNotation;

use PhpCsFixer\Test\AbstractFixerTestCase;

/**
 * @internal
 */
final class MultilineFunctionFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFix($expected, $input = null)
    {
        $this->doTest($expected, $input);
    }

    public function provideFixCases()
    {
        return array(
            array(
                '<?php foo(
$arg1,
$arg2,
$arg3
                );',
                '<?php foo($arg1, $arg2,
$arg3
                );',
            ),
            array(
                '<?php $foo(
$arg1,
$arg2,
$arg3
                );',
                '<?php $foo($arg1, $arg2,
$arg3
                );',
            )
        );
    }
}
