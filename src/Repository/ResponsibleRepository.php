<?php

namespace App\Repository;

use App\Entity\Responsible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Responsible|null find($id, $lockMode = null, $lockVersion = null)
 * @method Responsible|null findOneBy(array $criteria, array $orderBy = null)
 * @method Responsible[]    findAll()
 * @method Responsible[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponsibleRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'firstName_param' => 'firstName',
        'lastName_param' => 'lastName',
        'login_param' => 'login',
        'phone_param' => 'phone',
        'fax_param' => 'fax',
        'mail_param' => 'mail',
        'region_name_param' => 'region_name',
        'departments_name_param' => 'departments_name',
        'buildings_name_param' => 'buildings_name',
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Responsible::class);
    }
}
