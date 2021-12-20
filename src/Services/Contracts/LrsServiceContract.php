<?php

namespace EscolaLms\Lrs\Services\Contracts;

interface LrsServiceContract
{
    public function launchParams(int $courseId, string $token): array;
}
