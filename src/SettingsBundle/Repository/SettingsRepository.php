<?php

declare(strict_types=1);

/*
 * This file is part of CSBill project.
 *
 * (c) 2013-2016 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\SettingsBundle\Repository;

use CSBill\CoreBundle\Util\ArrayUtil;
use Doctrine\ORM\EntityRepository;

class SettingsRepository extends EntityRepository
{
    /**
     * Gets section specific settings.
     *
     * @param string|Section $section
     * @param bool           $combineArray Should the settings be returned as a key => value array
     *
     * @return array
     */
    public function getSettingsBySection($section, bool $combineArray = true): array
    {
        $qb = $this->createQueryBuilder('s')
                    ->where('s.section = :section')
                    ->orderBy('s.key', 'ASC')
                    ->setParameter('section', $section);

        $query = $qb->getQuery()
                    ->useQueryCache(true);

        $result = $query->getResult();

        if (count($result) > 0 && $combineArray) {
            return array_combine(ArrayUtil::column($result, 'key'), ArrayUtil::column($result, 'value'));
        }

        return $result;
    }
}