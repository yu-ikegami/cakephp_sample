<?php
/**
 * @var $article \App\Model\Entity\Article
 * @var $tags \Cake\ORM\Query
 */
?>
<h1>記事の追加</h1>
<?php
echo $this->Form->create($article);
echo $this->Form->input('user_id', ['type' => 'hidden', 'value' => 1]);
echo $this->Form->input('title');
echo $this->Form->input('body', ['rows' => 3]);
echo $this->Form->input('tags._ids', ['options' => $tags]);
echo $this->Form->button(__('Save Article'));
echo $this->Form->end();
