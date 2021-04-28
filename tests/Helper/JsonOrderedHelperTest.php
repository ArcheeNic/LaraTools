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
        $this->array      = ['a' => ['a' => ['a' => ['b', 'b', 'c' => 'd']], 'b' => 0,'c'=>null]];
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
                            3 =>
                                [
                                    'key'   => 'c',
                                    'value' => null,
                                ],
                        ],
                ],

        ];
        parent::setUp();
    }

    // endregion Setters


    public function testConvertToKeyValue()
    {
        $data = JsonOrderedHelper::convertToKeyValue($this->array);
        self::assertEquals($data,json_encode($this->checkArray));
    }

    public function testConvertFromKeyValue()
    {
        $data = JsonOrderedHelper::convertFromKeyValue(json_encode($this->checkArray));
        self::assertEquals($this->array,$data);

    }
}
