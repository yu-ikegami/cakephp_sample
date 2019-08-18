<?php
/**
 * @var $article \App\Model\Entity\Article
 * @var $tags \Cake\ORM\Query
 */
?>
<h1>記事の編集</h1>
<?php
echo $this->Form->create($article);
echo $this->Form->input('user_id', ['type' => 'hidden']);
echo $this->Form->input('title');
echo $this->Form->input('body', ['rows' => 3]);
echo $this->Form->input('tag_string', ['type' => 'text']);
echo $this->Form->button(__('Save Article'));
echo $this->Form->end();
