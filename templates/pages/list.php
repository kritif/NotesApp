<section class="list">
  <div class="error">
    <?php
      if(!empty($params['error'])) {
        switch($params['error']) {
          case 'noteNotFound': 
            echo 'Note was not found.';
            break;
          case 'missingNoteId': 
            echo 'Note id is missing';
            break;
          }
        }; 
      ?>
  </div>
  <div class="message">
    <?php 
      if(!empty($params['before'])) {
        switch($params['before']) {
          case 'created': 
            echo 'Note created successful.';
            break;
          case 'edited':
            echo 'Note updated successful.';
            break;
          case 'deleted':
            echo 'Note delete successful.';
            break;
        }
      }; 
    ?>
  </div>

  <?php
    $sort = $params['sort'] ?? [];
    $by = $sort['by'] ?? 'title';
    $order = $sort['order'] ?? 'desc';
  ?>

  <div>
    <form class="settings-form" action="/" method ="GET">
    <div>
    <div>Sort by:</div>
      <label>Title: <input name="sortby" type="radio" value="title" <?php echo $by == 'title' ? 'checked' : ''?>/></label>
      <label>Date: <input name="sortby" type="radio" value="created" <?php echo $by == 'created' ? 'checked' : ''?>/></label>
    </div>
    <div>
    <div>Order:</div>
      <label>ascending<input name="sortorder" type="radio" value="asc" <?php echo $order == 'asc' ? 'checked' : ''?>/></label>
      <label>descending<input name="sortorder" type="radio" value="desc" <?php echo $order == 'desc' ? 'checked' : ''?>/></label>
    </div>
    <input type="submit" value="Send">
    </form>
  </div>
  <div class="tbl-header">
    <table cellpadding="0" cellspacing ="0" border="0">
      <thead>
        <tr>
          <th>Id</th>
          <th>Title</th>
          <th>Created</th>
          <th>Options</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <table cellpadding="0" cellspacing ="0" border="0">
      <tbody>
        <?php foreach ($params['notes'] ?? [] as $note): ?>
          <tr>
            <td><?php echo $note['id'] ?? ""?></td>
            <td><?php echo $note['title'] ?? ""?></td>
            <td><?php echo $note['created'] ?? ""?></td>
            <td>
              <a href="/?action=show&id=<?php echo $note['id'] ?>" style="text-decoration: none"> 
                <button>Show</button> 
              </a>
              <a href="/?action=delete&id=<?php echo $note['id'] ?>" style="text-decoration: none">
                <button>Delete</button>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>