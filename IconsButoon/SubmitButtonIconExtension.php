<?php


namespace App\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmitButtonIconExtension extends AbstractTypeExtension
{

    public static function getExtendedTypes():iterable
    {
       return [SubmitType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['icon_before'] = $options['icon_before'] ?? '';
        $view->vars['icon_after'] = $options['icon_after'] ?? '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon_before' => null,
            'icon_after' => null
        ]);
    }
}
