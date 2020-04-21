<?php


namespace App\Form;


use App\Entity\Categories;
use App\Entity\Users;
use App\Repository\CategoriesRepository;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Validator\Constraints\NotBlank;


class CategoriesFormType extends AbstractType
{


    private $categoriesRepository;

    private $usersRepository;

    private $security;

    public function __construct(CategoriesRepository $categoriesRepository, UsersRepository $usersRepository, Security $security)
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->usersRepository = $usersRepository;
//        $this->request = $request;
        $this->security = $security;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = Request::createFromGlobals();

        $path = $request->getPathInfo();
        $pattern = '/^\/admin\/categories-edit\//';
        $slug = preg_replace($pattern, '', $path);

        $builder
            ->add('category', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Wstaw',
                    ]),

                ]
            ])
            ->add('description')
            ->add('parent', EntityType::class, [
                'class' => Categories::class,
                'placeholder' => 'Brak',
                'label' => 'Nadrzędna kategoria',
                'choices' => $this->categoriesRepository->findAllButNoPresentSlug($slug),
                'required' => false,

            ])
            ->add('author', EntityType::class, [
                'class' => Users::class,
                'label' => 'Autor',
                'choices' => $this->usersRepository->findAll(),
                'placeholder' => 'Wybierz autora',
                'required' => false,
                'data' => $this->security->getUser(),


            ])
            ->add('isEnabled')
            ->add('save_close', SubmitType::class, [
                'icon_before' => 'far fa-angry',
                'attr' => ['class' => 'btn-danger btn-sm mt-5 d-inline'],
            ])
            ->add('save', SubmitType::class, [
                'icon_before' => 'far fa-angry',
                'attr' => ['class' => 'btn-success btn-sm mt-5 d-inline'],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }


}


