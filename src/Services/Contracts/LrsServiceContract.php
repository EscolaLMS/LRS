<?php

namespace EscolaLms\Lrs\Services\Contracts;

interface LrsServiceContract
{
    public function launchParams(string $token, ?int $courseId = null, ?int $topicId = null): array;
}
