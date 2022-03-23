<?php

namespace App\Form;

use App\Service\League\LeagueService;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterForm extends AbstractType
{
    /**
     * @var LeagueService
     */
    private $leagueService;

    /**
     * @param LeagueService $leagueService
     */
    public function __construct(LeagueService $leagueService)
    {
        $this->leagueService = $leagueService;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oddDecorated', CheckboxType::class, [
                'label'    => 'Only bet decorated',
                'required' => false,
            ])
            ->add('played', CheckboxType::class, [
                'label'    => 'Only played',
                'required' => false,
            ])
            ->add('useDraws', CheckboxType::class, [
                'label'    => 'Use draws',
                'required' => false,
            ])
            ->add('from', DateTimeType::class, ['required' => false])
//            ->add('league', IntegerType::class)
            ->add('leagues', ChoiceType::class, [
                'choices' => $this->getLeagueChoiceList(),
                'multiple' => true,
            ])
            ->add('maxResults', IntegerType::class)
            ->add('round', IntegerType::class)
            ->add('season', IntegerType::class, ['required' => false])
            ->add('submit', SubmitType::class, ['label' => 'Search']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilterData::class
        ]);
    }

    private function getLeagueChoiceList(){
        $leagues = $this->leagueService->getAll();
        $choices = array();
        foreach ($leagues as $league){
            $choices[$league->getIdent()] = $league->getId();
        }
        ksort($choices);
        dump($choices);
        return $choices;
    }
}