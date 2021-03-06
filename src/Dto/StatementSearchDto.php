<?php

namespace EscolaLms\Lrs\Dto;

use Carbon\Carbon;
use EscolaLms\Core\Dtos\Contracts\DtoContract;
use EscolaLms\Core\Dtos\Contracts\InstantiateFromRequest;
use EscolaLms\Core\Dtos\CriteriaDto;
use EscolaLms\Core\Repositories\Criteria\Primitives\DateCriterion;
use EscolaLms\Lrs\Enums\QueryEnum;
use EscolaLms\Lrs\Repositories\Criteria\JsonCriteria;
use EscolaLms\Lrs\Repositories\Criteria\OrderCriterion;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StatementSearchDto extends CriteriaDto implements DtoContract, InstantiateFromRequest
{
    public static function instantiateFromRequest(Request $request): self
    {
        $criteria = new Collection();

        $criteria->push(
            new OrderCriterion(
                $request->get('order_by') ?? QueryEnum::DEFAULT_SORT,
                $request->get('order') ?? QueryEnum::DEFAULT_SORT_DIRECTION
            )
        );

        if ($request->get('verb')) {
            $criteria->push(
                new JsonCriteria('data->verb->display->en-US', $request->get('verb'))
            );
            $criteria->push(
                new JsonCriteria('data->verb->id', $request->get('verb'), null, false)
            );
        }
        if ($request->get('account')) {
            $criteria->push(
                new JsonCriteria('data->actor->account->name', $request->get('account'))
            );
            $criteria->push(
                new JsonCriteria('data->actor->account->homePage', $request->get('account'), null, false)
            );
        }
        if ($request->get('registration')) {
            $criteria->push(
                new JsonCriteria('data->context->registration', $request->get('registration'))
            );
        }
        if ($request->get('object')) {
            $criteria->push(
                new JsonCriteria('data->object->objectType', $request->get('object'))
            );
        }
        if ($request->get('version')) {
            $criteria->push(
                new JsonCriteria('data->version', $request->get('version'))
            );
        }
        if ($request->get('date_from')) {
            $criteria->push(
                new DateCriterion(
                    'created_at',
                    new Carbon($request->get('date_from')),
                    '>='
                )
            );
        }
        if ($request->get('date_to')) {
            $criteria->push(
                new DateCriterion(
                    'created_at',
                    new Carbon($request->get('date_to')),
                    '<='
                )
            );
        }

        return new static($criteria);
    }
}
