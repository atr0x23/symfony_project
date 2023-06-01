<?php

namespace App\Form;

use App\Entity\Movies;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoviesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr'=>array('class'=>'form-control mb-3',
                    'placeholder'=>'enter movie title...'),
                'label'=>'Title'])
            ->add('description', TextareaType::class, [
                'attr'=>array('class'=>'form-control mb-5',
                    'placeholder'=>'enter movie description...','rows'=>'4'),
                'label'=>'Description'])
//            ->add('createdAt')
//            ->add('user')
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movies::class,
        ]);
    }
}
