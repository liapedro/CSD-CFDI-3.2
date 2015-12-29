<?php

namespace spec\JorgeAndrade;

use PhpSpec\ObjectBehavior;

class CsdSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('JorgeAndrade\Csd');
    }

}
