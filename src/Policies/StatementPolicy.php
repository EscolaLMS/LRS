<?php

namespace EscolaLms\Lrs\Policies;

use EscolaLms\Auth\Models\User;
use EscolaLms\Lrs\Enums\LrsPermissionEnum;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatementPolicy
{
    use HandlesAuthorization;

    public function list(User $user): bool
    {
        return $user->can(LrsPermissionEnum::STATEMENT_LIST);
    }
}
