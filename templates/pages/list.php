<section class="list">
  <div class="error">
    <?php
    if (!empty($params['error'])) {
      switch ($params['error']) {
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
    if (!empty($params['before'])) {
      switch ($params['before']) {
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
  $pageParams = $params['page'];
  $size = $pageParams['size'] ?? 10;
  $page = $pageParams['number'] ?? 1;
  $pages = $pageParams['pages'] ?? 1;

  $phrase = $pageParams['phrase'] ?? null;
  $paginationUrl = "&sortby=$by&sortorder=$order&page_size=$size&phrase=$phrase"
  ?>

  <div>
    <form class="settings-form" action="/" method="GET">
    <div>
      <label>Search: <input type="text" name="phrase" value="<?php echo $phrase?>"></label>
    </div>
      <div>
        <div>Sort by:</div>
        <label>Title: <input name="sortby" type="radio" value="title" <?php echo $by == 'title' ? 'checked' : '' ?> /></label>
        <label>Date: <input name="sortby" type="radio" value="created" <?php echo $by == 'created' ? 'checked' : '' ?> /></label>
      </div>
      <div>
        <div>Order:</div>
        <label>ascending<input name="sortorder" type="radio" value="asc" <?php echo $order == 'asc' ? 'checked' : '' ?> /></label>
        <label>descending<input name="sortorder" type="radio" value="desc" <?php echo $order == 'desc' ? 'checked' : '' ?> /></label>
      </div>
      <div>
        <div>Page size:</div>
        <label>1<input name="page_size" type="radio" value="1" <?php echo $size === 1 ? 'checked' : '' ?> /></label>
        <label>5<input name="page_size" type="radio" value="5" <?php echo $size === 5 ? 'checked' : '' ?> /></label>
        <label>10<input name="page_size" type="radio" value="10" <?php echo $size === 10 ? 'checked' : '' ?> /></label>
        <label>25<input name="page_size" type="radio" value="25" <?php echo $size === 25 ? 'checked' : '' ?> /></label>
      </div>
      <input type="submit" value="Send">
    </form>
  </div>
  <div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0">
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
    <table cellpadding="0" cellspacing="0" border="0">
      <tbody>
        <?php foreach ($params['notes'] ?? [] as $note) : ?>
          <tr>
            <td><?php echo $note['id'] ?? "" ?></td>
            <td><?php echo $note['title'] ?? "" ?></td>
            <td><?php echo $note['created'] ?? "" ?></td>
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
  <ul class="pagination">
    <?php if ($page !== 1) : ?>
      <li>
        <a href="<?php echo '/?page_number=1'.$paginationUrl ?>">
          <button>
            <<
          </button>
        </a>
      </li>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $pages; $i++) : ?>
      <li>
        <a href="<?php echo '/?page_number='.$i.$paginationUrl ?>">
          <button style="color: <?php echo $i === $page ? "black" : "white"?>"><?php echo $i ?></button>
        </a>
      </li>
    <?php endfor; ?>
    <?php if ($page !== $pages) : ?>
      <li>
        <a href="<?php echo '/?page_number='.$pages.$paginationUrl ?>">
          <button>
            >>
          </button>
        </a>
      </li>
    <?php endif; ?>
  </ul>
</section>