<?= "<?php\n" ?>

namespace <?= $namespace ?>;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
<?php if (null !== $resource_class): ?>
use <?= $resource_full_class_name ?>;
<?php endif; ?>

final class <?= $class_name ?> implements ContextAwareDataPersisterInterface
{
    /**
    * {@inheritdoc}
    */
    public function supports($data, array $context = []): bool
    {
<?php if (null !== $resource_class): ?>
        return $data instanceof <?= $resource_class ?>::class; // Add your custom conditions here
<?php else : ?>
        return false; // Add your custom conditions here
<?php endif; ?>
    }

    /**
    * {@inheritdoc}
    */
    public function persist($data, array $context = [])<?php
   if (null !== $resource_class) {
       echo ": $resource_class";
   } elseif (\PHP_VERSION_ID >= 70200) {
       echo ': object';
   }?>

    {
        // call your persistence layer to save $data

        return $data;
    }

    /**
    * {@inheritdoc}
    */
    public function remove($data, array $context = []): void
    {
        // call your persistence layer to delete $data
    }
}
