<div class="delete">
  <?php $note = $params['note'] ?? null; ?>
  <?php if($note): ?>
  <ul>
    <li>Title: <?php echo $note['title'] ?? ''?></li>
    <li>Description: <?php echo $note['description'] ?? ''?></li>
    <li>Created: <?php echo $note['created'] ?? ''?></li>
  </ul>
  <form method="POST" action="/?action=delete">
    <input type="hidden" name="id" value="<?php echo $note['id']?>"/>
    <input type="submit" value="Confirm"/>
  </form>
  <?php else: ?>
    <div>
      No notes to display.
    </div>
  <?php endif; ?>
  <a href="/" style="text-decoration: none">
    <button>Cancel</button>
  </a>
</div>