<section class="formSection">
  <h3>New note</h3>
  <div>
    <form class="note-form" action="/?action=create" method="post">
      <ul>
        <li>
          <label>Title <span class="required">*</span></label>
          <input type="text" name="title" class="field-long"/>
        </li>
        <li>
          <label>Description</label>
          <textarea type="text" name="description" 
          class="field-long field-textarea"
          id="field5"></textarea>
        </li>
        <li>
          <input type="submit" value="Submit"/>
        </li>
      </ul>
    </form>
  </div>
</section>