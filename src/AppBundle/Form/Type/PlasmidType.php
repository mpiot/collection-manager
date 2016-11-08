<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class PlasmidType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team', EntityType::class, array(
                'class' => 'AppBundle\Entity\Team',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('team')
                        ->leftJoin('team.members', 'members')
                        ->where('members = :user')
                            ->setParameter('user', $this->tokenStorage->getToken()->getUser())
                        ->orderBy('team.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => '-- select a team --',
            ))
            ->add('name')
            ->add('addGenBankFile', ChoiceType::class, array(
                'choices' => array(
                    'No' => 0,
                    'Yes' => 1,
                ),
                'multiple' => false,
                'expanded' => true,
                'label' => 'Send a GenBank file ?',
            ))
            ->add('genBankFile', GenBankFileType::class, array(
                'required' => false,
            ))
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $plasmid = $event->getData();

                if (null === $plasmid) {
                    return;
                }

                if (null !== $plasmid->getGenBankFile()) {
                    if (null !== $plasmid->getGenBankFile()->getPath()) {
                        $plasmid->setAddGenBankFile(true);
                    }
                }
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Plasmid',
        ));
    }
}