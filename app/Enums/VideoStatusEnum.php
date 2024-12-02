<?php

namespace App\Enums;

enum VideoStatusEnum: string
{
    case Processing = 'processing';
    case Processed = 'processed';
    case Failed = 'failed';
    case Canceled = 'canceled';
}
