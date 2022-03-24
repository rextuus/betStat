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
                'empty_data' => 0
            ])
            ->add('maxResults', IntegerType::class, ['required' => false, 'empty_data' => 100])
            ->add('round', IntegerType::class, ['required' => false, 'empty_data' => null])
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
        //            [15, 16, 3, 4, 33, 34, 46, 47, 27, 28]
//            [De1, De2, En1, En2, It1, It2, Sp1, Sp2, Fr1, Fr2]
        foreach ($leagues as $league){
            $choices[$league->getIdent()] = $league->getId();
        }
        ksort($choices);

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