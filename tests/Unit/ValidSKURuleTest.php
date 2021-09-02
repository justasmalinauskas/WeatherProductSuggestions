<?php


use App\Rules\ValidSKU;
use Tests\TestCase;

class ValidSKURuleTest extends TestCase {
    /**
     * @var ValidSKU
     */
    protected $rule;

    public function setUp() : void
    {
        $this->rule = new ValidSKU();
        parent::setUp();
    }

    /**
     *
     * @dataProvider validSKUs
     * @param string $sku
     * @return void
     */
    public function testValidSKUs(string $sku)
    {
        $this->assertTrue($this->rule->passes('test', $sku));
    }

    /**
     *
     * @dataProvider notValidSKUs
     * @param string $sku
     * @return void
     */
    public function testNotValidSKUs(string $sku)
    {
        $this->assertFalse($this->rule->passes('test', $sku));
    }

    public function validSKUs() {
        return [
            ["UM-123"],
            ["DD-1454"],
        ];
    }

    public function notValidSKUs() {
        return [
            ["TESTNOT-123"],
            ["A-1454"],
        ];
    }
}
