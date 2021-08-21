<section class="formSection">
  <h3>Edit note</h3>
  <?php $note = $params['note'] ?? null; ?>
  <div>
    <form class="note-form" action="/?action=edit" method="post">
    <input type="hidden" name="id" value="<?php echo $note['id']?>"/>
      <ul>
        <li>
          <label>Title <span class="required">*</span></label>
          <input type="text" name="title" class="field-long" value="<?php echo $note['title'] ?? ''?>"/>
        </li>
        <li>
          <label>Description</label>
          <textarea type="text" name="description" 
          class="field-long field-textarea"
          id="field5">
            <?php echo $note['title'] ?? ''?>
          </textarea>
        </li>
        <li>
          <input type="submit" value="Save"/>
        </li>
      </ul>
    </form>
  </div>
</section>