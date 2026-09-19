<?php

namespace App\Enum;

enum Role: string {
    case MEMBER = 'member';
    case ADMIN = 'administrator';
    case VISITOR = 'visitor';
}