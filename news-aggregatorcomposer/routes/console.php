<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('')->hourly('app:fetch-articles');
