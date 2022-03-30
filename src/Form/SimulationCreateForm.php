<?php

namespace App\Form;

use App\Entity\User;
use App\Service\Transaction\TransactionCreateDebtorData;
use App\Service\Transaction\TransactionData;
use App\Service\User\UserService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('ident', TextType::class)
            ->add('cashRegister', MoneyType::class)
            ->add('commitment', MoneyType::class)
            ->add('commitmentChange', TextType::class)
            ->add('leagues', ChoiceType::class, [
                'choices' => $this->getLeagueChoiceList(),
                'multiple' => true,
                'empty_data' => 0
            ])
            ->add('from', DateTimeType::class, [
                'required' => false,
                'widget' => 'choice',
                'years' => range(date('Y')-20, date('Y') + 1),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31)])
            ->add('until', DateTimeType::class, [
                'required' => false,
                'widget' => 'choice',
                'years' => range(date('Y')-20, date('Y') + 1),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31)])
            ->add('oddBorderLow', MoneyType::class)
            ->add('oddBorderHigh', MoneyType::class)
            ->add('submit', SubmitType::class, ['label' => 'Start']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimulationCreateData::class
        ]);
    }

    private function getLeagueChoiceList()
    {

        $choices = [
            'All' => null,
            'De1' => 15,
            'De2' => 16,
            'En1' => 3,
            'En2' => 4,
            'It1' => 33,
            'It2' => 34,
            'Es1' => 46,
            'Es2' => 47,
            'Fr1' => 27,
            'Fr2' => 28,
        ];
        return $choices;
    }
}