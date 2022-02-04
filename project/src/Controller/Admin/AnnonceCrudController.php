<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Form\AttachmentFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AnnonceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Annonce::class;
    }

    // public function deleteEntity(string $entityFqcn)
    // {

    // }

    public function configureFields(string $pageName): iterable
    {
        $imageFile = TextField::new('photoFile', 'Couverture')->setFormType(VichImageType::class, [
            'required' => true,
            'allow_delete' => false,
            'label' => 'app.photoFile',
        ]);
        $image = ImageField::new('photo', 'Couverture')->setBasePath('/uploads/annonces/');
        $fields = [
            TextField::new('titre'),
            TextField::new('contenu')->hideOnIndex(),
            BooleanField::new('onSale', 'En Vente'),
            MoneyField::new('prix')->setCurrency('EUR'),
            DateTimeField::new('CreatedAt', 'Date publication')->onlyOnDetail(),
            AssociationField::new('category', 'Catégorie'),
            SlugField::new('slug')->setTargetFieldName('titre')->hideOnIndex(),

            CollectionField::new('attachments', 'Photos')
            ->setEntryType(AttachmentFormType::class)
            ->onlyOnForms(),
            CollectionField::new('attachments', 'Galerie')
                ->setTemplatePath('base/vich-image-show.html.twig')
                ->onlyOnDetail(),
        ];
        if($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $image;
        } else {
            $fields[] = $imageFile;
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
                ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
                ->add(CRUD::PAGE_INDEX, 'detail');
    }
    
}
