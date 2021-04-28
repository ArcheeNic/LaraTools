<?php

namespace Helper;

use ArcheeNic\LaraTools\Helper\JsonOrderedHelper;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class JsonOrderedHelperTest extends TestCase
{
    protected $array      = [];
    protected $checkArray = [];

    // region Setters
    protected function setUp()
    : void
    {
        $this->array      = ['a' => ['a' => ['a' => ['b', 'b', 'c' => 'd']], 'b' => 0]];
        $this->checkArray = [
            1 =>
                [
                    'key'   => 'a',
                    'value' =>
                        [
                            1 =>
                                [
                                    'key'   => 'a',
                                    'value' =>
                                        [
                                            1 =>
                                                [
                                                    'key'   => 'a',
                                                    'value' =>
                                                        [
                                                            1 =>
                                                                [
                                                                    'key'   => 0,
                                                                    'value' => 'b',
                                                                ],
                                                            2 =>
                                                                [
                                                                    'key'   => 1,
                                                                    'value' => 'b',
                                                                ],
                                                            3 =>
                                                                [
                                                                    'key'   => 'c',
                                                                    'value' => 'd',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                ],
                            2 =>
                                [
                                    'key'   => 'b',
                                    'value' => 0,
                                ],
                        ],
                ],

        ];
        parent::setUp();
    }

    // endregion Setters


    public function testConvertToKeyValue()
    {
        $data = JsonOrderedHelper::convertToKeyValue(json_encode($this->array));
        self::assertEquals($data,$this->checkArray);
    }

    public function testConvertFromKeyValue()
    {
        $data = JsonOrderedHelper::convertFromKeyValue($this->checkArray);
        self::assertEquals(json_encode($this->array),$data);
    }
}
