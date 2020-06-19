<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\IntegrationTests;

use ChrisHarrison\VoGenerator\App\DefaultApp;
use ChrisHarrison\VoGenerator\Definition\DefinitionName;
use ChrisHarrison\VoGenerator\InternalEvaluator\InternalEvaluator;
use PHPUnit\Framework\TestCase;
use ValueObjects\BooleanTest;
use ValueObjects\CompositeTest;
use ValueObjects\DateTimeTest;
use ValueObjects\EntitySetTest;
use ValueObjects\EntityTest;
use ValueObjects\EnumTest;
use ValueObjects\FloatTest;
use ValueObjects\IntegerTest;
use ValueObjects\SetTest;
use ValueObjects\StringTest;
use ValueObjects\UuidTest;

final class GenerationIntegrationTest extends TestCase
{
    private function generateValueObject(string $name): void
    {
        $app = (new DefaultApp())->make([
            'definitionsRoot' => __DIR__,
            'fileExtension' => 'voml-test',
        ]);
        $internalEvaluator = $app->get(InternalEvaluator::class);
        $internalEvaluator->evaluate(new DefinitionName($name));
    }

    public function test_it_generates_boolean()
    {
        $this->generateValueObject('BooleanTest');
        $instance = BooleanTest::null();
        $this->assertInstanceOf(BooleanTest::class, $instance);
    }

    public function test_it_generates_composite()
    {
        $this->generateValueObject('CompositeTest');
        $instance = CompositeTest::null();
        $this->assertInstanceOf(CompositeTest::class, $instance);
    }

    public function test_it_generates_datetime()
    {
        $this->generateValueObject('DateTimeTest');
        $instance = DateTimeTest::null();
        $this->assertInstanceOf(DateTimeTest::class, $instance);
    }

    public function test_it_generates_entityset()
    {
        $this->generateValueObject('EntitySetTest');
        $instance = EntitySetTest::null();
        $this->assertInstanceOf(EntitySetTest::class, $instance);
    }

    public function test_it_generates_entity()
    {
        $this->generateValueObject('EntityTest');
        $instance = EntityTest::null();
        $this->assertInstanceOf(EntityTest::class, $instance);
    }

    public function test_it_generates_enum()
    {
        $this->generateValueObject('EnumTest');
        $instance = EnumTest::null();
        $this->assertInstanceOf(EnumTest::class, $instance);
    }

    public function test_it_generates_float()
    {
        $this->generateValueObject('FloatTest');
        $instance = FloatTest::null();
        $this->assertInstanceOf(FloatTest::class, $instance);
    }

    public function test_it_generates_integer()
    {
        $this->generateValueObject('IntegerTest');
        $instance = IntegerTest::null();
        $this->assertInstanceOf(IntegerTest::class, $instance);
    }

    public function test_it_generates_set()
    {
        $this->generateValueObject('SetTest');
        $instance = SetTest::null();
        $this->assertInstanceOf(SetTest::class, $instance);
    }

    public function test_it_generates_string()
    {
        $this->generateValueObject('StringTest');
        $instance = StringTest::null();
        $this->assertInstanceOf(StringTest::class, $instance);
    }

    public function test_it_generates_uuid()
    {
        $this->generateValueObject('UuidTest');
        $instance = UuidTest::null();
        $this->assertInstanceOf(UuidTest::class, $instance);
    }
}
