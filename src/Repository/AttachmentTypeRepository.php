<?php

namespace App\Repository;

use App\Entity\AttachmentType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AttachmentType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttachmentType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttachmentType[]    findAll()
 * @method AttachmentType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentTypeRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public const SEARCH_FIELDS = ['type_param' => 'type'];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttachmentType::class);
    }
}
