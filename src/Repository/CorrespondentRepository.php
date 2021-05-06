<?php

namespace App\Repository;

use App\Entity\Correspondent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Request\ParamFetcherInterface;

/**
 * @method Correspondent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Correspondent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Correspondent[]    findAll()
 * @method Correspondent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class CorrespondentRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'firstName_param' => 'firstName',
        'lastName_param'=>'lastName',
        'function_param'=>'function',
        'login_param'=>'login',
        'phone_param' => 'phone',
        'fax_param' => 'fax',
        'mail_param' => 'mail',
        'establishment_label_param'=>'establishment_label',
        'service_label_param'=>'service_label',
        'subDivision_label_param'=>'subDivision_label'
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Correspondent::class);
    }

}
