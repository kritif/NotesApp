<section class="list">
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
          <th>Options</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <table cellpadding="0" cellspacing ="0" border="0">
      <tbody></tbody>
    </table>
  </div>
</section>