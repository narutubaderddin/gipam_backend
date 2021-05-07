<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = [
        'lastName_param' => 'lastName',
        'firstName_param' => 'firstName',
        'website_param' => 'website',
        'phone_param' => 'phone',
        'email_param' => 'email',
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }
}
