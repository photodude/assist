<?php

namespace Equip\Assist;

use ArrayObject;
use Equip;
use PHPUnit_Framework_TestCase as TestCase;

class ArrayTest extends TestCase
{
    private $hash = [
        'company' => 'Acme',
        'owner' => 'Dr Acme',
        'industry' => 'Financial',
    ];

    private $list = [
        ['a'],
        ['b'],
        ['c'],
    ];

    public function testGrab()
    {
        // Works with a single key
        $this->assertSame(
            ['company' => 'Acme'],
            Equip\grab($this->hash, 'company')
        );

        // Or an array of keys
        $this->assertSame(
            ['company' => 'Acme', 'owner' => 'Dr Acme'],
            Equip\grab($this->hash, ['company', 'owner'])
        );

        // Missing keys do not cause errors
        $this->assertSame(
            [],
            Equip\grab($this->hash, ['never', 'gonna'])
        );
    }

    public function testExcept()
    {
        // Works with a single key
        $this->assertSame(
            [
                'owner' => 'Dr Acme',
                'industry' => 'Financial',
            ],
            Equip\except($this->hash, 'company')
        );

        // Or an array of keys
        $this->assertSame(
            ['industry' => 'Financial'],
            Equip\except($this->hash, ['company', 'owner'])
        );

        // Missing keys do not cause errors
        $this->assertSame(
            [
                'company' => 'Acme',
                'owner' => 'Dr Acme',
                'industry' => 'Financial',
            ],
            Equip\except($this->hash, ['never', 'gonna'])
        );
    }

    public function testHead()
    {
        // Works with no value
        $this->assertNull(Equip\head([]));

        // Pulls the first key in hash
        $this->assertSame('Acme', Equip\head($this->hash));

        // Array is unchanged
        $this->assertSame('Acme', $this->hash['company']);

        // Works with collections
        $this->assertSame(['a'], Equip\head($this->list));
    }

    public function testTail()
    {
        // Works with no value
        $this->assertNull(Equip\tail([]));

        // Pulls the last key in hash
        $this->assertSame('Financial', Equip\tail($this->hash));

        // Array is unchanged
        $this->assertSame('Financial', $this->hash['industry']);

        // Works with collections
        $this->assertSame(['c'], Equip\tail($this->list));
    }

    public function dataArrays()
    {
        return [
            [ [] ],
            [ ['b'] ],
            [ ['b' => 'baz'] ],
        ];
    }

    /**
     * @dataProvider dataArrays
     */
    public function testToArray($value)
    {
        // Works with arrays
        $this->assertSame($value, Equip\to_array($value));

        $traversable = new ArrayObject($value);

        // And with traversables
        $this->assertSame($value, Equip\to_array($traversable));
    }

    public function dataPluck()
    {
        $data = [
            ['id' => 0, 'company' => 'Luna'],
            ['id' => 1, 'company' => 'Acme'],
            ['id' => 2, 'company' => 'Umbrella'],
        ];

        return [
            [
                $data,
                'company',
                ['Luna', 'Acme', 'Umbrella'],
            ],
            [
                $data,
                'wrong',
                [null, null, null]
            ]
        ];
    }


    /**
     * @dataProvider dataPluck
     */
    public function testPluck($source, $key, $expected)
    {
        $this->assertSame($expected, Equip\pluck($source, $key));
    }
}
