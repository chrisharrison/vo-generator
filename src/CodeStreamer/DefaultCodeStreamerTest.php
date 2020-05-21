<?php

declare(strict_types=1);

namespace ChrisHarrison\VoGenerator\CodeStreamer;

use ArrayIterator;
use ChrisHarrison\VoGenerator\CodeStreamOut\CodeStreamOut;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

final class DefaultCodeStreamerTest extends TestCase
{
    public function test_output_is_called_in_sequence_based_on_iterator()
    {
        $namespace = 'NAMESPACE';

        $in = new ArrayIterator(['0', '1', '2', '3', '4']);

        $out = $this->prophesize(CodeStreamOut::class);

        $streamer = new DefaultCodeStreamer($namespace);

        $streamer->stream($in, $out->reveal());

        $out->setup($namespace)->shouldHaveBeenCalledOnce();
        $out->out(Argument::any())->shouldBeCalledTimes(5);
        $out->finish()->shouldHaveBeenCalledOnce();
    }
}
