<?php

use App\Helpers\Validator;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    public function testValidEmail()
    {
        $validator = new Validator();
        $validator->validate('email', 'khaldoun.noureddine@hotmail.com', null);
        $this->assertEmpty($validator->getErrors());

        $validator->validate('email', 'khaldoun', null);
        $this->assertNotEmpty($validator->getErrors());
    }

    public function testValidPassword()
    {
        $validator = new Validator();
        $validator->validate('password', '12345678', null);
        $this->assertEmpty($validator->getErrors());

        $validator->validate('password', '1234567', null);
        $this->assertNotEmpty($validator->getErrors());
    }

    public function testRequired()
    {
        $validator = new Validator();
        $var = 10;
        $validator->validateRequired($var, 'Variable');
        $this->assertEmpty($validator->getErrors());

        $var2 = null;
        $validator->unsetErrors();
        $validator->validateRequired($var2, 'Variable 2');
        $this->assertNotEmpty($validator->getErrors());
    }
}