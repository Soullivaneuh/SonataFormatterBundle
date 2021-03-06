<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\FormatterBundle\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Sonata\FormatterBundle\Formatter\TwigFormatter;

class TwigFormatterTest extends TestCase
{
    public function testFormatter(): void
    {
        $loader = new MyStringLoader();
        $twig = new \Twig_Environment($loader);

        $formatter = new TwigFormatter($twig);

        // Checking, that formatter can process twig template, passed as string
        $this->assertSame('0,1,2,3,', $formatter->transform('{% for i in range(0, 3) %}{{ i }},{% endfor %}'));

        // Checking, that formatter does not changed loader
        if (class_exists('\Twig_Loader_String')) {
            $this->assertNotInstanceOf('\\Twig_Loader_String', $twig->getLoader());
        }
        $this->assertInstanceOf('Sonata\\FormatterBundle\\Tests\\Formatter\\MyStringLoader', $twig->getLoader());
    }

    public function testAddFormatterExtension(): void
    {
        $this->expectException(\RuntimeException::class);

        $loader = new MyStringLoader();
        $twig = new \Twig_Environment($loader);

        $formatter = new TwigFormatter($twig);

        $formatter->addExtension(new \Sonata\FormatterBundle\Extension\GistExtension());
    }

    public function testGetFormatterExtension(): void
    {
        $loader = new MyStringLoader();
        $twig = new \Twig_Environment($loader);

        $formatter = new TwigFormatter($twig);

        $extensions = $formatter->getExtensions();

        $this->assertSame(0, count($extensions));
    }
}

class MyStringLoader implements \Twig_LoaderInterface
{
    public function getSourceContext($name)
    {
        return $name;
    }

    public function getSource($name)
    {
        return $name;
    }

    public function getCacheKey($name)
    {
        return $name;
    }

    public function isFresh($name, $time)
    {
        return true;
    }

    public function exists($name)
    {
        return true;
    }
}
