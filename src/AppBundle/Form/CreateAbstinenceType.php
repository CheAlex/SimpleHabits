<?php

namespace AppBundle\Form;

use SimpleHabits\Application\Command\CreateNewAbstinenceCommand;
use SimpleHabits\Domain\Model\Abstinence\Abstinence;
use SimpleHabits\Domain\Model\User\UserId;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateAbstinenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'empty_data' => '',
                    'required' => false,
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => Abstinence::NAME_MIN_LENGTH,
                            'max' => Abstinence::NAME_MAX_LENGTH,
                        ]),
                    ],
                ]
            )
            ->add('save', SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null,
            'data_class' => CreateNewAbstinenceCommand::class,
            'empty_data' => function (FormInterface $form) {
                $userId = $form->getConfig()->getOption('user')->getId();

                return new CreateNewAbstinenceCommand(
                    new UserId($userId),
                    $form->get('name')->getData()
                );
            },
        ]);

        $resolver->setRequired(['user']);
    }
}
