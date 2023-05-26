<?php

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DoctrineDenormalizer implements DenormalizerInterface
{

    /**
    * Instance de EntityManagerInterface
    *
    * @var EntityManagerInterface
    */
    private $entityManagerInterface;
    
    /**
    * Constructor
    */
    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }
    /**
     * Appel quand on a besoin de denormaliser
     *
     * @param mixed $data : la valeur que l'on tente de denormaliser (dans notre cas un ID)
     * @param string $type : le type que l'on veut obtenir (dans notre cas un entity)
     * @param string|null $format
     */
    public function supportsDenormalization($data, string $type, ?string $format = null): bool
    {
        $dataIsID = is_numeric($data);
        $typeIsEntity = (strpos($type, 'App\Entity') === 0);
        $typeIsNotArray = !(strpos($type, ']') === (strlen($type) -1));

        return $typeIsEntity && $dataIsID && $typeIsNotArray;
    }

    /**
     * Si je suis dans le cas où $data est un ID ET $type est un Entity
     *
     * @param mixed $data : la valeur que l'on tente de denormaliser (dans notre cas un ID)
     * @param string $type : le type que l'on veut obtenir (dans notre cas un entity)
     * @param string|null $format
     * @param array $context
     * 
     * @return mixed
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        $denormalizedEntity = $this->entityManagerInterface->find($type, $data);
        if ($denormalizedEntity === null)
        {
            throw new EntityNotFoundException($type . "#". $data ." not found");
        }
        return $denormalizedEntity;
    }
}