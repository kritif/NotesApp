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
        }
      }; 
    ?>
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
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>