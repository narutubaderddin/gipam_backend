<?php

namespace App\Repository;

use App\Entity\Depositor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Depositor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depositor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depositor[]    findAll()
 * @method Depositor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepositorRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'name_param' => 'name',
        'acronym_param' => 'acronym',
        'mail_param' => 'mail',
        'phone_param' => 'phone',
        'fax_param' => 'fax',
        'contact_param' => 'contact',
        'address_param' => 'address',
        'distrib_param' => 'distrib',
        'department_param' => 'department',
        'city_param' => 'city',
        'comment_param' => 'comment',
        'depositType_label_param' => 'depositType_label',
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depositor::class);
    }
}
