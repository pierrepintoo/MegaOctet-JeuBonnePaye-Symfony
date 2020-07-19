<?php

namespace App\Form;

use App\Entity\Partie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class NombreDeToursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbTours', NumberType::class, [
                'label' => 'Nombre de tour(s) qui sera(ont) joué(s) pour la partie',
                'attr' => ['placeholder' => '5'],
                'constraints' => [
                    new Range(['min' => 1, 'max' => 10, 'notInRangeMessage' => 'Hehe et non petit charlatan ! On t\'a dis qu\'une partie ne pouvait être qu\'entre {{ min }} and {{ max }} tours.'])
                ]                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partie::class,
        ]);
    }
}
