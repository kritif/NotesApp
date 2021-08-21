<div class="show">
  <?php $note = $params['note'] ?? null; ?>
  <?php if($note): ?>
  <ul>
    <li>Title: <?php echo $note['title'] ?? ''?></li>
    <li>Description: <?php echo $note['description'] ?? ''?></li>
    <li>Created: <?php echo $note['created'] ?? ''?></li>
  </ul>
  <?php else: ?>
    <div>
      No notes to display.
    </div>
  <?php endif; ?>
  <a href="/" style="text-decoration: none"><button>Back to list</button></a>
</div>