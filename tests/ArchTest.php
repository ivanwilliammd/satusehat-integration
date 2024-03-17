<?php

it('will not use debugging functions')
    ->expect(['dd', 'dump'])
    ->not->toBeUsed();
