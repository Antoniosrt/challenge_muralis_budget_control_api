<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityFactory
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function createFromRequest(array $request, string $entityClass)
    {
        try {
            // Use injected serializer for deserialization
            $request = json_encode($request);
            $entity = $this->serializer->deserialize($request, $entityClass, 'json');

            // Validate the deserialized entity
            $errors = $this->validator->validate($entity);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
                return new JsonResponse(['errors' => $errorMessages], 400);
            }

            return $entity;

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
