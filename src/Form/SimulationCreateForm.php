<?php

namespace App\Form;

use App\Entity\User;
use App\Service\Transaction\TransactionCreateDebtorData;
use App\Service\Transaction\TransactionData;
use App\Service\User\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimulationCreateForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cashRegister', MoneyType::class)
            ->add('commitment', MoneyType::class)
            ->add('commitmentChange', TextType::class)
            ->add('betOnHome', CheckboxType::class, ['required' => false])
            ->add('betOnDraw', CheckboxType::class, ['required' => false])
            ->add('betOnAway', CheckboxType::class, ['required' => false])
            ->add('oddBorderLow', MoneyType::class)
            ->add('oddBorderHigh', MoneyType::class)
            ->add('submit', SubmitType::class, ['label' => 'Transaktion erstellen']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimulationCreateData::class
        ]);
    }
}