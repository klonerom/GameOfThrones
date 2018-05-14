<?php

namespace AppBundle\Form;

use AppBundle\Entity\Kingdom;
use AppBundle\Entity\Person;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PersonType extends AbstractType
{
    /**
     * {@inheritdoc} Including all fields from Person entity.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                    'label' => 'Name'
                )
            ))
            ->add('firstname', TextType::class, array(
                'attr' => array(
                    'label' => 'Firstname'
                )
            ))
            ->add('gender', IntegerType::class, array(
                'attr' => array(
                    'min' => 0,
                    'max' => 1,
                    'label' => 'Gender'
                )
            ))
            ->add('biography', TextareaType::class, array(
                'attr' => array(
                    'label' => 'Biography'
                )
            ))
            ->add('kingdom', EntityType::class, array(//classement par ordre croissant des noms des users présents dans la liste
                'class' => Kingdom::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ));
    }

    /**
     * {@inheritdoc} Targeting Person entity
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Person::class
        ));
    }

    /**
     * {@inheritdoc} getName() is now deprecated
     */
    public function getBlockPrefix()
    {
        return 'appbundle_person';
    }


}