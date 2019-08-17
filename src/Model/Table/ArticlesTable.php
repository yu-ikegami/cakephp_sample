<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function beforeSave($event, $entity, $object)
    {
        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = static::slug($entity->title);
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    public function validationDefault(Validator $validator)
    {
        $validator->allowEmpty('title', false)
            ->add('title', [
                'size' => ['rule' => ['lengthBetween', 10, 25]],
            ])
            ->allowEmpty('body', false)
            ->add('body', [
                'size' => ['rule' => ['minLength', 20]],
            ]);

        return $validator;
    }

    private static $_defaultTransliteratorId = 'Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove';

    private static function transliterate($string, $transliterator = null)
    {
        if (!$transliterator) {
            $transliterator = static::$_defaultTransliteratorId;
        }

        return transliterator_transliterate($transliterator, $string);
    }

    public static function slug($string, $options = [])
    {
        if (is_string($options)) {
            $options = ['replacement' => $options];
        }
        $options += [
            'replacement' => '-',
            'transliteratorId' => null,
            'preserve' => null
        ];
        if ($options['transliteratorId'] !== false) {
            $string = static::transliterate($string, $options['transliteratorId']);
        }
        $regex = '^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}';
        if ($options['preserve']) {
            $regex .= preg_quote($options['preserve'], '/');
        }
        $quotedReplacement = preg_quote($options['replacement'], '/');
        $map = [
            '/[' . $regex . ']/mu' => ' ',
            '/[\s]+/mu' => $options['replacement'],
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        ];
        $string = preg_replace(array_keys($map), $map, $string);
        return $string;
    }
}
