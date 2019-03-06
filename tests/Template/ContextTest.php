<?php

namespace Petk\Tests\Template;

use PHPUnit\Framework\TestCase;
use Petk\Template\Context;

class ContextTest extends TestCase
{
    protected function setUp()
    {
        $this->context = new Context(__DIR__.'/../fixtures/templates');
    }

    public function testBlock()
    {
        $this->context->start('foo');
        echo 'bar';
        $this->context->end('foo');

        $this->assertEquals('bar', $this->context->block('foo'));

        $this->context->append('foo');
        echo 'baz';
        $this->context->end('foo');

        $this->assertEquals('barbaz', $this->context->block('foo'));

        $this->context->start('foo');
        echo 'overridden';
        $this->context->end('foo');

        $this->assertEquals('overridden', $this->context->block('foo'));
    }

    public function testInclude()
    {
        ob_start();
        $this->context->include('includes/banner.php');
        $content = ob_get_clean();

        $this->assertEquals(file_get_contents(__DIR__.'/../fixtures/templates/includes/banner.php'), $content);
    }

    public function testIncludeReturn()
    {
        $variable = $this->context->include('includes/variable.php');

        $this->assertEquals(include __DIR__.'/../fixtures/templates/includes/variable.php', $variable);
    }

    public function testIncludeOnInvalidVariableCounts()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Variables with numeric names $0, $1... cannot be imported to scope includes/variable.php');

        $this->context->include('includes/variable.php', ['var1', 'var2', 'var3']);
    }

    public function testCallOnUndefinedMethod()
    {
        $this->assertNull($this->context->undefinedMethod());
    }

    /**
     * @dataProvider attacksProvider
     */
    public function testEscaping($malicious, $escaped, $noHtml)
    {
        $this->assertEquals($escaped, $this->context->e($malicious));
    }

    /**
     * @dataProvider attacksProvider
     */
    public function testNoHtml($malicious, $escaped, $noHtml)
    {
        $this->assertEquals($noHtml, $this->context->noHtml($malicious));
    }

    public function attacksProvider()
    {
        return [
            [
                '<iframe src="javascript:alert(\'Xss\')";></iframe>',
                '&lt;iframe src=&quot;javascript:alert(&#039;Xss&#039;)&quot;;&gt;&lt;/iframe&gt;',
                '&lt;iframe src&equals;&quot;javascript&colon;alert&lpar;&apos;Xss&apos;&rpar;&quot;&semi;&gt;&lt;&sol;iframe&gt;'
            ]
        ];
    }
}
