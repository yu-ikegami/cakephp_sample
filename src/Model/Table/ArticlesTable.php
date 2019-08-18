<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ArticlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags');
    }

    public function beforeSave($event, $entity, $object)
    {
        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }

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

    public function findTagged(Query $query, array $options)
    {
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug',
        ];

        $query = $query
            ->select($columns)
            ->distinct($columns);

        if (empty($options['tags'])) {
            $query->leftJoin(
                ['ArticlesTags' => 'articles_tags'],
                ['ArticlesTags.article_id = Articles.id']
            )->leftJoin(
                ['Tags' => 'tags'],
                ['Tags.id = ArticlesTags.tag_id']
            )
                ->where(['Tags.title IS' => null]);
        } else {
            $query->leftJoin(
                ['ArticlesTags' => 'articles_tags'],
                ['ArticlesTags.article_id = Articles.id']
            )->leftJoin(
                ['Tags' => 'tags'],
                ['Tags.id = ArticlesTags.tag_id']
            )
                ->where(['Tags.title IN' => $options['tags']]);
        }

        return $query->group(['Artiels.id']);
    }

    private function _buildTags($tagString)
    {
        $newTags = array_map('trim', explode(',', $tagString));
        $newTags = array_filter($newTags);
        $newTags = array_unique($newTags);

        $out = [];
        $query = $this->Tags->find()
            ->where(['Tags.title IN ' => $newTags]);

        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        foreach ($query as $tag) {
            $out[] = $tag;
        }
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }
        return $out;
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
