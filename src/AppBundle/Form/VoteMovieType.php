<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteMovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Symfony sait quel type de champ on a besoin avec les annotations qu'on a mises dans l'entity du comment.php
        $builder

            ->add('vote', IntegerType::class, ["label" => "Votre note de 1 à 10 où 10 est attribué à un chef d'oeuvre et 1 à un navet"]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\VoteMovie'
        ));
    }

    /**
     * ce bloc est utilisé comme id
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_vote';
    }


}
