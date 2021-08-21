<div class="show">
  <?php $note = $params['note'] ?? null; ?>
  <?php if($note): ?>
  <ul>
    <li>Title: <?php echo $note['title'] ?? ''?></li>
    <li>Description: <?php echo $note['description'] ?? ''?></li>
    <li>Created: <?php echo $note['created'] ?? ''?></li>
  </ul>
  <a href="/?action=edit&id=<?php echo $note['id'] ?>" style="text-decoration: none">
    <button>Edit note</button>
  </a>
  <a href="/?action=delete&id=<?php echo $note['id'] ?>" style="text-decoration: none">
    <button>Delete note</button>
  </a>
  <?php else: ?>
    <div>
      No notes to display.
    </div>
  <?php endif; ?>
  <a href="/" style="text-decoration: none">
    <button>Back to list</button>
</a>
</div>