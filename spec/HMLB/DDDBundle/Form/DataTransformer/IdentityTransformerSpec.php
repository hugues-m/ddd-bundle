<?php

namespace spec\HMLB\DDDBundle\Form\DataTransformer;

use HMLB\DDD\Entity\Identity;
use PhpSpec\ObjectBehavior;

class IdentityTransformerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('HMLB\DDDBundle\Form\DataTransformer\IdentityTransformer');
        $this->shouldHaveType('Symfony\Component\Form\DataTransformerInterface');
    }

    public function it_transforms_identity_into_string()
    {
        $id = new Identity();
        $this->transform($id)->shouldBe($id->__toString());
    }

    public function it_transforms_null_into_null()
    {
        $this->transform(null)->shouldBe(null);
    }

    public function it_reverse_transforms_string_into_identity()
    {
        $string = 'Ae7r5fS£é"zzaéàçze!rsuiodESR';
        $result = $this->reverseTransform($string);
        $result->shouldHaveType('HMLB\DDD\Entity\Identity');
        $result->__toString()->shouldBe($string);
    }

    public function it_reverse_transforms_empty_into_null()
    {
        $this->reverseTransform(null)->shouldBe(null);
    }
}
